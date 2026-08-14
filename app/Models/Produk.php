<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id_produk';
    protected $table = 'produk';
    protected $fillable = [
        'nama_produk',
        'tipe_produk',
        'kategori',
        'deskripsi',
        "stok",
        'harga_eceran',
        'harga_produk1',
        'harga_produk2',
        'harga_produk3',
        'harga_produk4',
        'harga_produk5',
        'foto_produk1',
        'foto_produk2',
        'foto_produk3',
        'foto_produk4'
    ];

    /**
     * Get the category that owns the product.
     */
    public function kategoriData(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori', 'id_kategori');
    }
}
