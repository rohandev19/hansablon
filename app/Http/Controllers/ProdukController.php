<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProdukRequest;
use App\Http\Requests\UpdateProdukRequest;
use App\Models\Kategori;
use App\Models\Produk;
use App\Services\ProdukService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProdukController extends Controller
{
    public function __construct(
        private readonly ProdukService $produkService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Use eager loading instead of manual joins to avoid N+1 and keep it object-oriented
        $produk = Produk::with('kategoriData')->get();
        return view('admin.produk.produk', compact('produk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $kategori = Kategori::orderBy('id_kategori', 'desc')->get();
        return view('admin.produk.produk_create', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProdukRequest $request): RedirectResponse
    {
        // Validation is already handled by StoreProdukRequest
        $this->produkService->createProduk($request->validated());

        return to_route('produk.index')->with('success', 'Berhasil Menambahkan Produk Baru');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $produk = Produk::findOrFail($id);
        $kategori = Kategori::orderBy('id_kategori', 'desc')->get();
        return view('admin.produk.produk_edit', compact(['produk', 'kategori']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProdukRequest $request, int $id): RedirectResponse
    {
        $produk = Produk::findOrFail($id);
        
        $this->produkService->updateProduk($produk, $request->validated());

        return to_route('produk.index')->with('success', 'Berhasil Memperbaharui Produk');
    }

    /**
     * Menambah jumlah stok untuk produk tertentu.
     */
    public function tambahStok(Request $request, Produk $produk): RedirectResponse
    {
        // For a simple single field, inline validation is acceptable, 
        // though a FormRequest could be used for consistency.
        $validated = $request->validate([
            'jumlah_tambah' => ['required', 'integer', 'min:1']
        ]);

        $produk->increment('stok', $validated['jumlah_tambah']);

        return back()->with('success', 'Stok untuk produk ' . $produk->nama_produk . ' berhasil ditambahkan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();
        
        return back()->with('delete', 'Berhasil Menghapus Produk');
    }
}