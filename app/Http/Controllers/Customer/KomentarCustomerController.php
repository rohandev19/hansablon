<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Komentar;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomentarCustomerController extends Controller
{
    public function store_komentar(Request $request)
    {
        $request->validate([
            'id_pesanan' => 'required|exists:pesanan,id_pesanan',
            'id_produk' => 'required|exists:produk,id_produk',
            'komentar' => 'required|string|max:1000'
        ]);

        $id_pesanan = $request->id_pesanan;

        // SECURITY FIRST: Prevent IDOR. Ensure the order belongs to the authenticated user.
        $pesanan = Pesanan::where('id_user', Auth::id())->find($id_pesanan);

        if (!$pesanan) {
            return back()->with('error', 'Pesanan tidak valid atau bukan milik Anda.');
        }

        Komentar::create([
            'id_pesanan' => $id_pesanan,
            'id_produk' => $request->id_produk,
            'id_user' => Auth::id(), // Never trust client for user ID
            'komentar_produk' => $request->komentar
        ]);

        $pesanan->update([
            'status' => 'selesai',
        ]);

        return to_route('customer.produk')->with('success', 'Komentar berhasil dikirim dan pesanan diselesaikan.');
    }
}
