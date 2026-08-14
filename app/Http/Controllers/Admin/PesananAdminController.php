<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PesananAdminController extends Controller
{
    public function index()
    {
        // Eloquent Eager Loading - Optimized from 5 queries to 1 query
        $allPesanan = Pesanan::with(['produk', 'alamat', 'user'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $konfirmasi = $allPesanan->where('status', 'Bukti Pembayaraan Sedang Di Tinjau')->where('tipe_pembayaran', 'lunas');
        $ongoing = $allPesanan->where('status', 'Pesanan Di Terima')->where('tipe_pembayaran', 'lunas');
        $kirim = $allPesanan->where('status', 'Barang Dalam Pengiriman');
        $konfirmasi_dp = $allPesanan->where('status', 'Bukti Pembayaraan Sedang Di Tinjau')->where('tipe_pembayaran', 'dp');
        $ongoing_dp = $allPesanan->where('status', 'Pesanan Di Terima')->where('tipe_pembayaran', 'dp');

        return view('admin.pesanan.pesanan', compact(['konfirmasi','ongoing','kirim','konfirmasi_dp','ongoing_dp']));
    }

    public function konfirm_pembayaran(int $id)
    {
        Pesanan::findOrFail($id)->update([
            'status' => 'Pesanan Di Terima'
        ]);
        return back()->with('success', 'Pembayaran diterima.');
    }

    public function tolak_pembayaran(int $id)
    {
        Pesanan::findOrFail($id)->update([
            'status' => 'Pesanan Di Tolak'
        ]);
        return back()->with('success', 'Pembayaran ditolak.');
    }

    public function cetak_pesanan(int $id)
    {
        $pesanan = Pesanan::with(['produk', 'alamat', 'user'])->findOrFail($id);
        return view('admin.pesanan.pesanan_cetak', compact(['pesanan']));
    }

    public function download_request(int $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        
        if (!$pesanan->desain) {
            return back()->with('error', 'File desain tidak ditemukan.');
        }

        $file = public_path() . "/desain/" . $pesanan->desain;
        
        if (!file_exists($file)) {
            return back()->with('error', 'File desain tidak ditemukan di server.');
        }

        return Response::download($file, '#P00' . $pesanan->id_pesanan . '-' . $pesanan->desain);
    }

    public function store_resi(Request $request, int $id)
    {
        $request->validate([
            'resi' => 'required|string|max:255'
        ]);

        Pesanan::findOrFail($id)->update([
            'status' => 'Barang Dalam Pengiriman',
            'no_resi' => $request->resi,
        ]);

        return back()->with('success', 'Resi berhasil disimpan.');
    }

    public function kirim_tagihan(int $id)
    {
        Pesanan::findOrFail($id)->update([
            'dp_status' => 'tagihan deliver',
        ]);

        return back()->with('success', 'Tagihan berhasil dikirim.');
    }

    public function tolak_sisa_dp(int $id)
    {
        Pesanan::findOrFail($id)->update([
            'dp_status' => 'tagihan sisa tolak',
        ]);

        return back()->with('success', 'Pelunasan ditolak.');
    }

    public function terima_sisa_dp(int $id)
    {
        Pesanan::findOrFail($id)->update([
            'dp_status' => 'lunas',
        ]);

        return back()->with('success', 'Pelunasan diterima.');
    }
}
