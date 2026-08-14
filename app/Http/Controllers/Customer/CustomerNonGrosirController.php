<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Komentar;
use App\Models\Produk;
use Illuminate\Http\Request;

class CustomerNonGrosirController extends Controller
{
    public function index()
    {
        $produk = Produk::where('tipe_produk', 'eceran')->latest()->paginate(9);
        $kategori = Kategori::orderBy('jenis_kategori', 'asc')->get();
        return view('customer.produknongrosir.produk_non_grosir', compact('produk', 'kategori'));
    }

    public function detail_produk($id)
    {
        $produk = Produk::join('kategori', 'produk.kategori', '=', 'kategori.id_kategori')
            ->select('produk.*', 'kategori.jenis_kategori')
            ->where('tipe_produk', 'eceran')
            ->find($id);

        $komentar = Komentar::join('produk', 'produk.id_produk', '=', 'komentar.id_produk')
            ->join('users', 'users.id', '=', 'komentar.id_user')
            ->select('users.nama', 'komentar.*')
            ->where('komentar.id_produk', $id)
            ->orderBy('komentar.id_komentar', 'desc')
            ->limit(3)
            ->get();

        return view('customer.produknongrosir.detail_produk_non_grosir', compact('produk', 'komentar'));
    }

    public function kategori_produk($id)
    {
        $produk = Produk::where('tipe_produk', 'eceran')->where('kategori', $id)->paginate(9);
        $kategori = Kategori::orderBy('jenis_kategori', 'asc')->get();
        return view('customer.produknongrosir.kategori_produk_non_grosir', compact('produk', 'kategori', 'id'));
    }
}