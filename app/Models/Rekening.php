<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_rekening';
    protected $table = 'rekening';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // GANTI BARIS LAMA ANDA DENGAN YANG INI
    protected $fillable = [
        'nama_rek',
        'no_rek',
        'atas_nama', // <-- IZINKAN KOLOM INI
        'logo',      // <-- IZINKAN KOLOM INI
    ];
}