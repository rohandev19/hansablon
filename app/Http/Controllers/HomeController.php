<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Komentar;
use App\Models\Produk;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        // Eloquent Eager Loading instead of manual joins
        $produk = Produk::with('kategoriData')
            ->where('tipe_produk', 'grosir')
            ->orderBy('id_produk', 'desc')
            ->limit(5)
            ->get();
            
        return view('home.index', compact('produk'));
    }

    public function detail_produk($id)
    {
        // Eloquent Eager Loading instead of manual joins
        $komentar = Komentar::with(['user', 'produk'])
            ->where('id_produk', $id)
            ->orderBy('id_komentar', 'desc')
            ->limit(3)
            ->get();
            
        $detail = Produk::with('kategoriData')->findOrFail($id);
        
        return view('home.detail_product', compact(['detail','komentar']));
    }

    public function grosir()
    {
        $produk = Produk::with('kategoriData')->where('tipe_produk', 'grosir')->latest()->paginate(9);
        $kategori = Kategori::orderBy('jenis_kategori', 'asc')->get();
        return view('home.produk', compact(['kategori','produk']));
    }

    public function cari_kategori($id)
    {
        $produk = Produk::with('kategoriData')->where('tipe_produk', 'grosir')->where('kategori', $id)->paginate(9);
        $kategori = Kategori::orderBy('jenis_kategori', 'asc')->get();
        return view('home.kategori', compact(['kategori','produk']));
    }

    public function non_grosir()
    {
        $produk = Produk::with('kategoriData')->where('tipe_produk', 'eceran')->latest()->paginate(9);
        $kategori = Kategori::orderBy('jenis_kategori', 'asc')->get();
        return view('home.produk_non', compact(['kategori','produk']));
    }

    public function detail_produk_non($id)
    {
        $detail = Produk::with('kategoriData')->where('tipe_produk', 'eceran')->findOrFail($id);
        return view('home.detail_product_non', compact(['detail']));
    }

    public function cari_kategori_non($id)
    {
        $produk = Produk::with('kategoriData')->where('tipe_produk', 'eceran')->where('kategori', $id)->paginate(9);
        $kategori = Kategori::orderBy('jenis_kategori', 'asc')->get();
        return view('home.kategori_non', compact(['kategori','produk']));
    }

    public function contact()
    {
        return view('home.contact');
    }
}
