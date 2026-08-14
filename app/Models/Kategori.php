<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_kategori';
    protected $table='kategori';
    protected $fillable = ['jenis_kategori'];

    /**
     * Get the products associated with the category.
     */
    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class, 'kategori', 'id_kategori');
    }
}
