<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukNon extends Model
{
    protected $table = 'produknon'; // PASTIKAN INI BENAR
    protected $primaryKey = 'id_produknon'; // PASTIKAN INI BENAR

    public $incrementing = true;
    protected $keyType = 'int'; // Sesuaikan jika tipe data PK Anda bukan integer

    protected $fillable = [
        'nama_produk',
        'kategori',
        'harga_produk',
        'foto_produk1',
        'foto_produk2',
        'foto_produk3',
        'foto_produk4',
        'deskripsi',
    ];

    // Relasi ke Kategori (contoh)
    public function kategoriData()
    {
        return $this->belongsTo(Kategori::class, 'kategori', 'id_kategori');
    }

    // Relasi ke Komentar (contoh, sesuaikan foreign key)
    public function komentars()
    {
        // Ganti 'id_kolom_asing_di_komentar_yg_merujuk_ke_produknon' dengan nama kolom yang benar
        return $this->hasMany(Komentar::class, 'id_kolom_asing_di_komentar_yg_merujuk_ke_produknon', 'id_produknon');
    }
}