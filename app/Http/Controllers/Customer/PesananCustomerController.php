<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Rekening;
use App\Models\Variasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PesananCustomerController extends Controller
{
    public function get_ongkir($id_kota, $berat)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=399&destination=" . $id_kota . "&weight=" . $berat . "&courier=jne",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: f201c33f7b1021a48e2a76125bfa5e15" // Ganti dengan API Key RajaOngkir Anda
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return ['error' => true, 'message' => "cURL Error #:" . $err];
        } else {
            return json_decode($response, true);
        }
    }

    public function index()
    {
        $userId = Auth::user()->id;
        $baseQuery = Pesanan::join('produk', 'produk.id_produk', '=', 'pesanan.id_produk')
            ->join('user_alamat', 'user_alamat.id_user_alamat', '=', 'pesanan.id_alamat')
            ->where('pesanan.id_user', $userId)
            ->select('pesanan.*', 'produk.nama_produk', 'produk.foto_produk1', 'user_alamat.nama_prov', 'user_alamat.nama_kota')
            ->orderBy('pesanan.updated_at', 'desc');

        $pesanan_paid = (clone $baseQuery)->whereIn('pesanan.status', ['menunggu pembayaran', 'Bukti Pembayaraan Sedang Di Tinjau', 'Pesanan Di Tolak'])->get();
        $ongoing = (clone $baseQuery)->where('pesanan.status', 'Pesanan Di Terima')->get();
        $kirim = (clone $baseQuery)->where('pesanan.status', 'Barang Dalam Pengiriman')->get();
        $tagihan = (clone $baseQuery)->where('pesanan.dp_status', 'tagihan deliver')->get();

        return view('customer.pesanan.pesanan', compact(['pesanan_paid', 'ongoing', 'kirim', 'tagihan']));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        if ($request->alamat_kirim == NULL) {
            return back()->with('error', 'Proses Gagal! Wajib memilih salah satu alamat pengiriman.');
        }

        $id_keranjang = $request->id_keranjang;
        $keranjang = Keranjang::find($id_keranjang);

        if (!$keranjang) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang tidak ditemukan atau sudah diproses.');
        }

        $produk = Produk::find($keranjang->id_produk);
        if ($keranjang->total > $produk->stok) {
            return back()->with('error', 'Gagal membuat pesanan! Stok produk ' . $produk->nama_produk . ' tidak mencukupi. Sisa stok: ' . $produk->stok . ' pcs.');
        }

        DB::beginTransaction();
        try {
            $harga_variasi = $request->variasi_harga;
            $harga_sablon = $request->sablon_harga;
            $total_variasi = $harga_variasi ? array_sum(explode(',', $harga_variasi)) : 0;
            $total_sablon = $harga_sablon ? array_sum(explode(',', $harga_sablon)) : 0;

            $kota_result = explode('|', $request->alamat_kirim);
            $id_kota = $kota_result[0];
            $id_alamat = $kota_result[1];

            $total = $keranjang->total;
            if ($total <= 11) {
                $harga = $produk->harga_produk1;
            } elseif ($total <= 23) {
                $harga = $produk->harga_produk2;
            } elseif ($total <= 50) {
                $harga = $produk->harga_produk3;
            } elseif ($total <= 100) {
                $harga = $produk->harga_produk4;
            } else {
                $harga = $produk->harga_produk5;
            }
            $jumlah = $harga * $total;

            $berat = $total * 145;
            $ongkir_response = $this->get_ongkir($id_kota, $berat);

            $harga_ongkir = 0;
            if (isset($ongkir_response['rajaongkir']['results'][0]['costs'][1]['cost'][0]['value'])) {
                $harga_ongkir = $ongkir_response['rajaongkir']['results'][0]['costs'][1]['cost'][0]['value'];
            }

            $total_bayar = $jumlah + $harga_ongkir + $total_variasi + $total_sablon;

            Pesanan::create([
                'id_user' => Auth::user()->id,
                'id_produk' => $keranjang->id_produk,
                'quantity' => $keranjang->total,
                'id_alamat' => $id_alamat,
                'id_kota' => $id_kota,
                'variasi' => $request->variasi,
                'variasi_harga' => $harga_variasi,
                'variasi_total' => $total_variasi,
                'sablon' => $request->sablon,
                'sablon_harga' => $harga_sablon,
                'sablon_total' => $total_sablon,
                'note_sablon_variasi' => $request->note,
                'bayar' => $jumlah,
                'ongkir' => $harga_ongkir,
                'total_bayar' => $total_bayar,
                'status' => "menunggu pembayaran",
            ]);

            $produk->decrement('stok', $keranjang->total);

            $keranjang->delete();

            DB::commit();

            return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat membuat pesanan. Silakan coba lagi. Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $pesanan = Pesanan::join('produk', 'produk.id_produk', '=', 'pesanan.id_produk')
            ->join('user_alamat', 'user_alamat.id_user_alamat', '=', 'pesanan.id_alamat')
            ->join('users', 'users.id', '=', 'pesanan.id_user')
            ->select('pesanan.*', 'produk.*', 'user_alamat.no_telp', 'user_alamat.alamat', 'user_alamat.nama_penerima', 'user_alamat.nama_prov', 'user_alamat.nama_kota', 'users.*')
            ->find($id);

        if (!$pesanan)
            abort(404);

        return view('customer.pesanan.pesanan_cetak', compact(['pesanan']));
    }

    // ===== METHOD EDIT YANG SUDAH DIPERBAIKI TOTAL =====
    public function edit($id)
    {
        $pesanan = Pesanan::leftJoin('produk', 'pesanan.id_produk', '=', 'produk.id_produk')
            ->leftJoin('user_alamat', 'pesanan.id_alamat', '=', 'user_alamat.id_user_alamat')
            ->select(
                'pesanan.*',
                'produk.id_produk as produk_id_produk',
                'produk.nama_produk',
                'produk.foto_produk1', // KOLOM FOTO SUDAH DI-SELECT
                'user_alamat.id_user_alamat',
                'user_alamat.nama_prov',
                'user_alamat.nama_kota',
                'user_alamat.alamat',
                'user_alamat.kode_pos',
                'user_alamat.nama_penerima',
                'user_alamat.no_telp'
            )
            ->where('pesanan.id_pesanan', $id)
            ->first();

        if (!$pesanan) {
            abort(404, 'Pesanan yang Anda cari tidak ditemukan.');
        }

        if ($pesanan->id_user_alamat === null) {
            return redirect()->route('pesanan.index')->with('error', 'Tidak bisa memproses pesanan #' . $pesanan->id_pesanan . '. Alamat pengiriman untuk pesanan ini kemungkinan telah dihapus.');
        }

        if ($pesanan->produk_id_produk === null) {
            return redirect()->route('pesanan.index')->with('error', 'Tidak bisa memproses pesanan #' . $pesanan->id_pesanan . '. Produk untuk pesanan ini telah dihapus.');
        }

        $rekening = Rekening::get();
        $id_kota = $pesanan->id_kota;
        $berat = $pesanan->quantity * 145;
        $ongkir_data = $this->get_ongkir($id_kota, $berat);

        $ongkir = 0;
        if (isset($ongkir_data['rajaongkir']['results'][0]['costs'][1]['cost'][0]['value'])) {
            $ongkir = $ongkir_data['rajaongkir']['results'][0]['costs'][1]['cost'][0]['value'];
        }
        $pesanan->ongkir = $ongkir;

        return view('customer.pesanan.pesanan_edit', compact(['pesanan', 'rekening']));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'metode' => 'required'
        ]);

        $pesanan = Pesanan::find($id);
        if (!$pesanan)
            return redirect()->route('pesanan.index')->with('error', 'Pesanan tidak ditemukan.');

        $fileName = null;
        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('bukti_bayar'), $fileName);
        }

        $desainName = $pesanan->desain;
        if ($request->hasFile('desain')) {
            $desainFile = $request->file('desain');
            $desainName = time() . '_' . $desainFile->getClientOriginalName();
            $desainFile->move(public_path('desain'), $desainName);
        }

        $updatePayload = [
            'desain' => $desainName,
            'request_user' => $request->request_desain,
            'status' => 'Bukti Pembayaraan Sedang Di Tinjau',
            'tipe_pembayaran' => $request->metode,
        ];

        if ($request->metode == 'dp') {
            $updatePayload['bukti_bayar_dp'] = $fileName;
        } else {
            $updatePayload['bukti_bayar'] = $fileName;
        }

        $pesanan->update($updatePayload);

        return redirect()->route('pesanan.index')->with('success', 'Bukti pembayaran berhasil diupload.');
    }

    public function destroy($id)
    {
        //
    }
}