<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatPesananController extends Controller
{
    public function index()
    {
        // Eloquent Eager Loading instead of manual DB joins for Clean Architecture
        $riwayat = Pesanan::with(['produk', 'alamat'])
            ->where('id_user', Auth::id())
            ->where('status', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('customer.riwayat.riwayat_pesanan', compact(['riwayat']));
    }
}
