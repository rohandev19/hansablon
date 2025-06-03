<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Komentar;
use App\Models\ProdukNon;
use Illuminate\Http\Request;

class CustomerNonGrosirController extends Controller
{
    public function index()
    {
        $produk = ProdukNon::latest()->paginate(9);
        $kategori = Kategori::orderBy('jenis_kategori', 'asc')->get();
        return view('customer.produknongrosir.produk_non_grosir', compact('produk', 'kategori'));
    }

    public function detail_produk($id)
    {
        $produk = ProdukNon::join('kategori', 'produk_non_grosir.kategori', '=', 'kategori.id_kategori')
            ->select('produk_non_grosir.*', 'kategori.jenis_kategori')
            ->find($id);

        $komentar = Komentar::join('produk_non_grosir', 'produk_non_grosir.id_produk', '=', 'komentar.id_produk')
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
        $produk = ProdukNon::where('kategori', $id)->paginate(9);
        $kategori = Kategori::orderBy('jenis_kategori', 'asc')->get();
        return view('customer.produknongrosir.kategori_produk_non_grosir', compact('produk', 'kategori', 'id'));
    }
}
