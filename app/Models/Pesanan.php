<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pesanan extends Model
{
    use HasFactory, SoftDeletes;
    protected $table='pesanan';
    protected $primaryKey = 'id_pesanan';
    protected $fillable=['id_user','id_produk','quantity','id_alamat','id_kota','ongkir','bayar',
    'total_bayar','bukti_bayar','no_resi','desain','request_user','status',
    'variasi','variasi_harga','variasi_total','sablon','sablon_harga',
    'sablon_total','note_sablon_variasi','total_dp','bukti_bayar_dp',
    'bukti_bayar_dp_lunas','dp_status','tipe_pembayaran'];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function alamat(): BelongsTo
    {
        return $this->belongsTo(Alamat::class, 'id_alamat', 'id_user_alamat');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
