<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProdukRequest;
use App\Http\Requests\UpdateProdukRequest;
use App\Models\Kategori;
use App\Models\Produk;
use App\Services\ProdukService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProdukNonController extends Controller
{
    public function __construct(
        private readonly ProdukService $produkService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Eager load category to prevent N+1 queries
        $produk = Produk::with('kategoriData')->where('tipe_produk', 'eceran')->get();
        return view('admin.produk_non.produk_non', compact('produk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $kategori = Kategori::orderBy('id_kategori', 'desc')->get();
        return view('admin.produk_non.produk_non_create', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProdukRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['tipe_produk'] = 'eceran';
        $this->produkService->createProduk($data);

        return to_route('produk_non.index')->with('success', 'Berhasil Menambahkan Produk Baru');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        // Not implemented in original code
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $produk = Produk::where('tipe_produk', 'eceran')->findOrFail($id);
        $kategori = Kategori::orderBy('id_kategori', 'desc')->get();

        return view('admin.produk_non.produk_non_edit', compact(['produk', 'kategori']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProdukRequest $request, int $id): RedirectResponse
    {
        $produk = Produk::where('tipe_produk', 'eceran')->findOrFail($id);
        $data = $request->validated();
        $data['tipe_produk'] = 'eceran';
        
        $this->produkService->updateProduk($produk, $data);

        return to_route('produk_non.index')->with('success', 'Berhasil Memperbaharui Produk Non Grosir');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $produk = Produk::where('tipe_produk', 'eceran')->findOrFail($id);
        $produk->delete();
        
        return to_route('produk_non.index')->with('delete', 'Berhasil Menghapus Produk');
    }
}
