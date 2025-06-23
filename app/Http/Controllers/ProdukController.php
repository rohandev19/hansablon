<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Import class Str untuk membuat slug

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Kode ini sudah benar, tidak ada perubahan
        $produk = Produk::join('kategori', 'produk.kategori', '=', 'kategori.id_kategori')
            ->select('produk.*', 'kategori.jenis_kategori')
            ->get();
        return view('admin.produk.produk', compact('produk'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Kode ini sudah benar, tidak ada perubahan
        $kategori = Kategori::orderBy('id_kategori', 'desc')->get();
        return view('admin.produk.produk_create', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. VALIDASI DITAMBAHKAN UNTUK STOK
        $request->validate([
            'nama_produk' => 'required|unique:produk,nama_produk',
            'pilih_kategori' => 'required',
            'deskripsi_produk' => 'required',
            'stok' => 'required|integer|min:0', // Validasi untuk stok
        ]);

        // Mengambil data harga (logika Anda dipertahankan)
        $harga1 = $request->harga_produk1 ? substr(preg_replace('/[Rp.,]/', '', $request->harga_produk1), 0, -2) : null;
        $harga2 = $request->harga_produk2 ? substr(preg_replace('/[Rp.,]/', '', $request->harga_produk2), 0, -2) : null;
        $harga3 = $request->harga_produk3 ? substr(preg_replace('/[Rp.,]/', '', $request->harga_produk3), 0, -2) : null;
        $harga4 = $request->harga_produk4 ? substr(preg_replace('/[Rp.,]/', '', $request->harga_produk4), 0, -2) : null;
        $harga5 = $request->harga_produk5 ? substr(preg_replace('/[Rp.,]/', '', $request->harga_produk5), 0, -2) : null;

        // 2. LOGIKA UPLOAD GAMBAR DIPERBAIKI AGAR LEBIH AMAN
        $foto1 = null;
        $foto2 = null;
        $foto3 = null;
        $foto4 = null;

        if ($request->hasFile('img1')) {
            $file1 = $request->file('img1');
            $foto1 = Str::slug($request->nama_produk) . '-1-' . time() . '.' . $file1->getClientOriginalExtension();
            $file1->move(public_path('produk'), $foto1);
        }
        if ($request->hasFile('img2')) {
            $file2 = $request->file('img2');
            $foto2 = Str::slug($request->nama_produk) . '-2-' . time() . '.' . $file2->getClientOriginalExtension();
            $file2->move(public_path('produk'), $foto2);
        }
        if ($request->hasFile('img3')) {
            $file3 = $request->file('img3');
            $foto3 = Str::slug($request->nama_produk) . '-3-' . time() . '.' . $file3->getClientOriginalExtension();
            $file3->move(public_path('produk'), $foto3);
        }
        if ($request->hasFile('img4')) {
            $file4 = $request->file('img4');
            $foto4 = Str::slug($request->nama_produk) . '-4-' . time() . '.' . $file4->getClientOriginalExtension();
            $file4->move(public_path('produk'), $foto4);
        }

        // 3. MENAMBAHKAN 'stok' SAAT MEMBUAT PRODUK BARU
        Produk::create([
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->pilih_kategori,
            'deskripsi' => $request->deskripsi_produk,
            'stok' => $request->stok, // 'stok' ditambahkan di sini
            'harga_produk1' => $harga1,
            'harga_produk2' => $harga2,
            'harga_produk3' => $harga3,
            'harga_produk4' => $harga4,
            'harga_produk5' => $harga5,
            'foto_produk1' => $foto1,
            'foto_produk2' => $foto2,
            'foto_produk3' => $foto3,
            'foto_produk4' => $foto4,
        ]);

        return to_route('produk.index')->with('success', 'Berhasil Menambahkan Produk Baru');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $produk = Produk::find($id);
        $kategori = Kategori::orderBy('id_kategori', 'desc')->get();
        return view('admin.produk.produk_edit', compact(['produk', 'kategori']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // MENAMBAHKAN 'stok' PADA FUNGSI UPDATE JUGA
        $request->validate([
            'nama_produk' => 'required',
            'pilih_kategori' => 'required',
            'deskripsi_produk' => 'required',
            'stok' => 'required|integer|min:0', // Tambahkan validasi stok juga di sini
        ]);

        $produk = Produk::find($id);

        // Siapkan data untuk diupdate
        $updateData = $request->only('nama_produk', 'pilih_kategori', 'deskripsi_produk', 'stok');
        // Rename key agar sesuai dengan kolom database
        $updateData['kategori'] = $updateData['pilih_kategori'];
        $updateData['deskripsi'] = $updateData['deskripsi_produk'];
        unset($updateData['pilih_kategori'], $updateData['deskripsi_produk']);

        // Update harga
        $updateData['harga_produk1'] = $request->harga_produk1 ? substr(preg_replace('/[Rp.,]/', '', $request->harga_produk1), 0, -2) : $produk->harga_produk1;
        $updateData['harga_produk2'] = $request->harga_produk2 ? substr(preg_replace('/[Rp.,]/', '', $request->harga_produk2), 0, -2) : $produk->harga_produk2;
        $updateData['harga_produk3'] = $request->harga_produk3 ? substr(preg_replace('/[Rp.,]/', '', $request->harga_produk3), 0, -2) : $produk->harga_produk3;
        $updateData['harga_produk4'] = $request->harga_produk4 ? substr(preg_replace('/[Rp.,]/', '', $request->harga_produk4), 0, -2) : $produk->harga_produk4;
        $updateData['harga_produk5'] = $request->harga_produk5 ? substr(preg_replace('/[Rp.,]/', '', $request->harga_produk5), 0, -2) : $produk->harga_produk5;

        // Cek dan update gambar jika ada yang baru diupload
        if ($request->hasFile('img1')) {
            $file1 = $request->file('img1');
            $updateData['foto_produk1'] = Str::slug($request->nama_produk) . '-1-' . time() . '.' . $file1->getClientOriginalExtension();
            $file1->move(public_path('produk'), $updateData['foto_produk1']);
        }
        if ($request->hasFile('img2')) {
            $file2 = $request->file('img2');
            $updateData['foto_produk2'] = Str::slug($request->nama_produk) . '-2-' . time() . '.' . $file2->getClientOriginalExtension();
            $file2->move(public_path('produk'), $updateData['foto_produk2']);
        }

        $produk->update($updateData);

        return to_route('produk.index')->with('success', 'Berhasil Memperbaharui Produk');
    }

    // ===================================================================
    // == INI FUNGSI YANG HILANG, SAYA TAMBAHKAN KEMBALI DI SINI ==
    // ===================================================================
    /**
     * Menambah jumlah stok untuk produk tertentu.
     */
    public function tambahStok(Request $request, Produk $produk)
    {
        // Validasi input
        $request->validate([
            'jumlah_tambah' => 'required|integer|min:1'
        ]);

        // Tambah stok menggunakan increment (lebih aman untuk database)
        $produk->increment('stok', $request->jumlah_tambah);

        // Redirect kembali dengan pesan sukses
        return back()->with('success', 'Stok untuk produk ' . $produk->nama_produk . ' berhasil ditambahkan.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Kode ini sudah benar, tidak ada perubahan
        Produk::where('id_produk', $id)->delete();
        return back()->with('delete', 'Berhasil Menghapus Produk');
    }
}