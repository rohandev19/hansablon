<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKeranjangRequest;
use App\Http\Requests\UpdateKeranjangRequest;
use App\Models\Alamat;
use App\Models\Keranjang;
use App\Models\Produk;
use App\Models\Sablon;
use App\Models\Variasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KeranjangCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Eloquent Eager Loading instead of Join
        $keranjang = Keranjang::with('produk')
            ->where('id_user', Auth::id())
            ->orderBy('id_keranjang', 'desc')
            ->paginate(10);

        return view('customer.keranjang.keranjang', compact('keranjang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKeranjangRequest $request): RedirectResponse
    {
        $jumlahBeli = $request->validated('demo0');
        $produkId = $request->validated('produk');
        
        $produk = Produk::findOrFail($produkId);

        if ($jumlahBeli > $produk->stok) {
            return back()->with('error', 'Maaf, jumlah pembelian melebihi stok yang tersedia (' . $produk->stok . ' pcs).');
        }

        // B2B Logic (Role & MOQ Showcase)
        if ($produk->tipe_produk == 'grosir') {
            if (!Auth::user()->is_b2b) {
                return back()->with('error', 'Maaf, produk grosir hanya bisa dibeli oleh akun B2B/Reseller.');
            }
            if ($jumlahBeli < 6) { // MOQ for B2B is 6
                return back()->with('error', 'Minimum Order Quantity (MOQ) untuk produk Grosir B2B adalah 6 pcs.');
            }
        }

        Keranjang::create([
            'id_user' => Auth::id(),
            'id_produk' => $produkId,
            'total' => $jumlahBeli,
        ]);

        return to_route('keranjang.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }



    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $alamat = Alamat::where('id_user', Auth::id())
            ->orderBy('id_user_alamat', 'DESC')
            ->get();

        // SECURITY FIRST: Object-Level Authorization (IDOR Prevention)
        $keranjangItem = Keranjang::with('produk')
            ->where('id_user', Auth::id())
            ->findOrFail($id);
            
        // We wrap it in a collection because the original blade view expects a collection
        $keranjang = collect([$keranjangItem]);

        $variasi = Variasi::get();
        $sablon = Sablon::get();

        return view('customer.checkout.checkout', compact(['alamat', 'id', 'keranjang', 'variasi', 'sablon']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKeranjangRequest $request, int $id): RedirectResponse
    {
        // SECURITY FIRST: Object-Level Authorization (IDOR Prevention)
        $keranjang = Keranjang::where('id_user', Auth::id())->findOrFail($id);
        
        $produk = Produk::find($keranjang->id_produk);

        $pembelian = $request->validated('pembelian');

        if($produk && $pembelian > $produk->stok) {
            return back()->with('error', 'Gagal! Jumlah melebihi stok yang tersedia (' . $produk->stok . ' pcs).');
        }

        if ($produk->tipe_produk == 'grosir' && $pembelian < 6) {
            return back()->with('error', 'Minimum Order Quantity (MOQ) untuk produk Grosir B2B adalah 6 pcs.');
        }

        $keranjang->update([
            'total' => $pembelian
        ]);

        return back()->with('success', 'Berhasil memperbaharui jumlah pembelian.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        // SECURITY FIRST: Object-Level Authorization (IDOR Prevention)
        $keranjang = Keranjang::where('id_user', Auth::id())->findOrFail($id);
        $keranjang->delete();

        return to_route('keranjang.index')->with('success', 'Berhasil Menghapus Keranjang');
    }
}