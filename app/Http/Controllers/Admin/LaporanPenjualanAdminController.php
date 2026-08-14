<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class LaporanPenjualanAdminController extends Controller
{
    public function laporan_penjualan()
    {
        // Eloquent Eager Loading instead of manual joins
        $transaksi = Pesanan::with(['produk', 'alamat'])
            ->where('status', 'selesai')
            ->orWhere('status', 'Barang Dalam Pengiriman')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.laporan_penjualan.laporan_penjualan', compact(['transaksi']));
    }
}
