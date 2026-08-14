<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKategoriRequest;
use App\Http\Requests\UpdateKategoriRequest;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KategoriProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $kategori = Kategori::latest()->paginate(4);
        return view('admin.kategori.kategori', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKategoriRequest $request): RedirectResponse
    {
        Kategori::create([
            'jenis_kategori' => $request->validated('kategori'),
        ]);

        return back()->with('success', 'Berhasil Membuat Kategori Baru');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $update = Kategori::findOrFail($id);
        $kategori = Kategori::latest()->paginate(4);

        return view('admin.kategori.kategori_edit', compact(['update', 'kategori']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKategoriRequest $request, int $id): RedirectResponse
    {
        $kategori = Kategori::findOrFail($id);
        
        $kategori->update([
            'jenis_kategori' => $request->validated('kategori'),
        ]);

        return to_route('kategori.index')->with('success', 'Berhasil Perbaharaui Kategori');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $kategori = Kategori::findOrFail($id);
        
        // Cek apakah ada produk yang masih menggunakan kategori ini sebelum dihapus
        // (Ini contoh penerapan defensive programming sesuai skill resilience)
        if ($kategori->produk()->exists()) {
            return back()->with('error', 'Gagal Menghapus Kategori: Masih ada produk yang menggunakan kategori ini.');
        }

        $kategori->delete();

        return to_route('kategori.index')->with('delete', 'Berhasil Menghapus Kategori');
    }
}
