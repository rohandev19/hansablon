<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePesananRequest;
use App\Http\Requests\UpdatePesananRequest;
use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Rekening;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PesananCustomerController extends Controller
{
    private function getOngkir(string $id_kota, int $berat): array
    {
        // Simulasi Ekspedisi: Bypass RajaOngkir API untuk Showcase Mode
        return [
            'rajaongkir' => [
                'results' => [
                    [
                        'costs' => [
                            0 => ['cost' => [['value' => 15000]]],
                            1 => ['cost' => [['value' => 20000]]], // JNE REG default mock
                        ]
                    ]
                ]
            ]
        ];
    }

    public function index(): View
    {
        // Eloquent Eager Loading - Optimized from 4 queries to 1 query
        $allPesanan = Pesanan::with(['produk', 'alamat'])
            ->where('id_user', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->get();

        $pesanan_paid = $allPesanan->whereIn('status', ['menunggu pembayaran', 'Bukti Pembayaraan Sedang Di Tinjau', 'Pesanan Di Tolak']);
        $ongoing = $allPesanan->where('status', 'Pesanan Di Terima');
        $kirim = $allPesanan->where('status', 'Barang Dalam Pengiriman');
        $tagihan = $allPesanan->where('dp_status', 'tagihan deliver');

        return view('customer.pesanan.pesanan', compact(['pesanan_paid', 'ongoing', 'kirim', 'tagihan']));
    }

    public function create()
    {
        //
    }

    public function store(StorePesananRequest $request): RedirectResponse
    {
        $keranjang = Keranjang::where('id_user', Auth::id())->find($request->validated('id_keranjang'));

        if (!$keranjang) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang tidak ditemukan atau sudah diproses.');
        }

        $produk = Produk::find($keranjang->id_produk);
        if ($keranjang->total > $produk->stok) {
            return back()->with('error', 'Gagal membuat pesanan! Stok produk ' . $produk->nama_produk . ' tidak mencukupi.');
        }

        DB::beginTransaction();
        try {
            $harga_variasi = $request->validated('variasi_harga');
            $harga_sablon = $request->validated('sablon_harga');
            $total_variasi = $harga_variasi ? array_sum(explode(',', $harga_variasi)) : 0;
            $total_sablon = $harga_sablon ? array_sum(explode(',', $harga_sablon)) : 0;

            $kota_result = explode('|', $request->validated('alamat_kirim'));
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
            $ongkir_response = $this->getOngkir($id_kota, $berat);

            $harga_ongkir = 0;
            if (isset($ongkir_response['rajaongkir']['results'][0]['costs'][1]['cost'][0]['value'])) {
                $harga_ongkir = $ongkir_response['rajaongkir']['results'][0]['costs'][1]['cost'][0]['value'];
            }

            $total_bayar = $jumlah + $harga_ongkir + $total_variasi + $total_sablon;

            Pesanan::create([
                'id_user' => Auth::id(),
                'id_produk' => $keranjang->id_produk,
                'quantity' => $keranjang->total,
                'id_alamat' => $id_alamat,
                'id_kota' => $id_kota,
                'variasi' => $request->validated('variasi'),
                'variasi_harga' => $harga_variasi,
                'variasi_total' => $total_variasi,
                'sablon' => $request->validated('sablon'),
                'sablon_harga' => $harga_sablon,
                'sablon_total' => $total_sablon,
                'note_sablon_variasi' => $request->validated('note'),
                'bayar' => $jumlah,
                'ongkir' => $harga_ongkir,
                'total_bayar' => $total_bayar,
                'status' => "menunggu pembayaran",
            ]);

            $produk->decrement('stok', $keranjang->total);
            
            // Catat history stok keluar
            \App\Models\StockHistory::create([
                'produk_id' => $produk->id_produk,
                'type' => 'out',
                'quantity' => $keranjang->total,
                'description' => 'Pembelian produk oleh User ID: ' . Auth::id(),
            ]);

            $keranjang->delete();

            DB::commit();

            return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat membuat pesanan. Silakan coba lagi. Error: ' . $e->getMessage());
        }
    }

    public function show(int $id): View
    {
        // SECURITY FIRST: IDOR Prevention + Eager Loading
        $pesanan = Pesanan::with(['produk', 'alamat'])
            ->where('id_user', Auth::id())
            ->findOrFail($id);

        return view('customer.pesanan.pesanan_cetak', compact('pesanan'));
    }

    public function edit(int $id): View|RedirectResponse
    {
        // SECURITY FIRST: IDOR Prevention + Eager Loading
        $pesanan = Pesanan::with(['produk', 'alamat'])
            ->where('id_user', Auth::id())
            ->findOrFail($id);

        if (!$pesanan->alamat) {
            return redirect()->route('pesanan.index')->with('error', 'Alamat pengiriman untuk pesanan ini kemungkinan telah dihapus.');
        }

        if (!$pesanan->produk) {
            return redirect()->route('pesanan.index')->with('error', 'Produk untuk pesanan ini telah dihapus.');
        }

        $rekening = Rekening::get();
        $id_kota = $pesanan->id_kota;
        $berat = $pesanan->quantity * 145;
        $ongkir_data = $this->getOngkir($id_kota, $berat);

        $ongkir = 0;
        if (isset($ongkir_data['rajaongkir']['results'][0]['costs'][1]['cost'][0]['value'])) {
            $ongkir = $ongkir_data['rajaongkir']['results'][0]['costs'][1]['cost'][0]['value'];
        }
        $pesanan->ongkir = $ongkir;

        return view('customer.pesanan.pesanan_edit', compact(['pesanan', 'rekening']));
    }

    public function update(UpdatePesananRequest $request, int $id): RedirectResponse
    {
        // SECURITY FIRST: IDOR Prevention
        $pesanan = Pesanan::where('id_user', Auth::id())->findOrFail($id);

        $desainName = $pesanan->desain;
        if ($request->hasFile('desain')) {
            $desainFile = $request->file('desain');
            // SECURITY FIRST: Safe File Naming
            $desainName = time() . '-' . Str::random(10) . '.' . $desainFile->extension();
            $desainFile->move(public_path('desain'), $desainName);
        }

        $metode = $request->validated('metode');

        $updatePayload = [
            'desain' => $desainName,
            'request_user' => $request->validated('request_desain'),
            // Simulasi Payment Gateway: Langsung set status ke terbayar
            'status' => 'Pesanan Sedang Di Proses',
            'tipe_pembayaran' => $metode,
            'bukti_bayar' => 'MOCKUP-PAYMENT-GATEWAY.png',
            'bukti_bayar_dp' => $metode == 'dp' ? 'MOCKUP-PAYMENT-GATEWAY.png' : null,
        ];

        $pesanan->update($updatePayload);

        return redirect()->route('pesanan.index')->with('success', 'Pembayaran berhasil dikonfirmasi secara otomatis (Simulasi).');
    }

    public function destroy(int $id)
    {
        // Function intentionally empty in original code
    }
}