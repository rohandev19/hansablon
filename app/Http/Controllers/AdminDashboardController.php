<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Eloquent Eager Loading instead of manual joins
        $transaksi = Pesanan::with(['produk', 'alamat'])
            ->where('status', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        // Data Mockup untuk Chart.js (Showcase B2B/B2C Portfolio)
        $chartData = [
            'labels' => ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            'sales' => [1200000, 1900000, 3000000, 5000000, 2500000, 3200000, 4100000],
            'b2b_b2c' => [65, 35], // 65% B2B, 35% B2C
        ];

        return view('admin.dashboard', compact(['transaksi', 'chartData']));
    }

    public function laporan(Request $request)
    {
        // Eloquent Eager Loading
        $laporan = Pesanan::with(['produk', 'alamat'])
            ->whereBetween('updated_at', [$request->date_start, $request->date_end])
            ->where(function($query) {
                $query->where('status', 'selesai')
                      ->orWhere('status', 'Barang Dalam Pengiriman');
            })
            ->get();

        return view('admin.laporan.laporan', compact(['laporan']));
    }
}
