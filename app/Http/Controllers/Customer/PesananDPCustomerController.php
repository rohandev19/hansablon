<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PesananDPCustomerController extends Controller
{
    public function update_sisa(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // SECURITY FIRST: Prevent IDOR.
        $pesanan = Pesanan::where('id_user', Auth::id())->findOrFail($id);

        $bukit_pembayaran = null;
        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            // SECURITY FIRST: Safe File Naming to prevent path traversal
            $bukit_pembayaran = time() . '-' . Str::random(10) . '.' . $file->extension();
            $file->move(public_path('bukti_bayar'), $bukit_pembayaran);
        }

        $pesanan->update([
            'bukti_bayar_dp_lunas' => $bukit_pembayaran,
            'dp_status' => 'tagihan send',
        ]);

        return to_route('pesanan.index')->with('success', 'Bukti pelunasan DP berhasil diunggah.');
    }
}
