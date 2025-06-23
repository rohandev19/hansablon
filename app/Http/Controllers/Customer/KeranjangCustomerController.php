<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use App\Models\Keranjang;
use App\Models\Produk; // 1. IMPORT MODEL PRODUK
use App\Models\Sablon;
use App\Models\Variasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $keranjang = Keranjang::join('produk', 'keranjang.id_produk', '=', 'produk.id_produk')
            ->select('keranjang.*', 'produk.*')
            ->where('keranjang.id_user', Auth::user()->id)
            ->orderBy('keranjang.id_keranjang', 'desc')
            ->paginate(10);

        return view('customer.keranjang.keranjang', compact(['keranjang']));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // =================================================================
        // == AWAL BLOK VALIDASI STOK (KODE YANG DIPERBARUI) ==
        // =================================================================

        // 2. Validasi input dasar dari request
        $request->validate([
            'produk' => 'required|exists:produk,id_produk',
            'demo0' => 'required|integer'
        ]);
        
        $jumlahBeli = $request->demo0;

        // 3. Ambil data produk dari database untuk cek stok
        $produk = Produk::find($request->produk);

        // 4. Lakukan semua validasi kuantitas
        if ($jumlahBeli < 6) {
            return back()->with('error', 'Maaf, pembelian produk minimal 6 pcs.');
        }
        
        if ($jumlahBeli > 200) {
            return back()->with('error', 'Maaf, pembelian produk maksimal 200 pcs per transaksi.');
        }

        if ($jumlahBeli > $produk->stok) {
            return back()->with('error', 'Maaf, jumlah pembelian melebihi stok yang tersedia (' . $produk->stok . ' pcs).');
        }

        // 5. Jika semua validasi lolos, baru masukkan ke keranjang
        Keranjang::create([
            'id_user' => Auth::user()->id,
            'id_produk' => $request->produk,
            'total' => $jumlahBeli,
        ]);

        return to_route('keranjang.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
        
        // =================================================================
        // == AKHIR BLOK VALIDASI STOK ==
        // =================================================================
    }


    /**
     * Store a newly created resource in storage.
     */
    public function nongrosir(Request $request)
    {
        // Jika Anda juga perlu validasi stok untuk produk non-grosir,
        // terapkan logika yang sama seperti di method store() di atas.

        Keranjang::create([
            'id_user' => Auth::user()->id,
            'id_produk' => $request->id_produknon,
            'total' => $request->demo0,
        ]);


        return to_route('keranjang.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = $id;

        $alamat = Alamat::where('id_user', Auth::user()->id)
            ->orderBy('id_user_alamat', 'DESC')
            ->get();

        $keranjang = Keranjang::join('produk', 'keranjang.id_produk', '=', 'produk.id_produk')
            ->select('keranjang.*', 'produk.*')
            ->where('keranjang.id_keranjang', $id)
            ->get();

        $variasi = Variasi::get();

        $sablon = Sablon::get();

        return view('customer.checkout.checkout', compact(['alamat', 'id', 'keranjang', 'variasi', 'sablon']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Anda juga bisa menambahkan validasi stok di sini saat customer mengubah jumlah di keranjang
        $request->validate(['pembelian' => 'required|integer|min:6']);
        
        $keranjang = Keranjang::findOrFail($id);
        $produk = Produk::find($keranjang->id_produk);

        if($request->pembelian > $produk->stok) {
            return back()->with('error', 'Gagal! Jumlah melebihi stok yang tersedia (' . $produk->stok . ' pcs).');
        }

        $keranjang->update([
            'total' => $request->pembelian
        ]);

        return back()->with('success', 'Berhasil memperbaharui jumlah pembelian.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Keranjang::where('id_keranjang', $id)->delete();

        return to_route('keranjang.index')->with('success', 'Berhasil Menghapus Keranjang');
    }
}