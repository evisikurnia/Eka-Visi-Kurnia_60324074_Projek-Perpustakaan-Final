<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara manual karena Laravel mendeteksi defaultnya 'kategoris'
    protected $table = 'kategori';

    // Kolom yang boleh diisi sekaligus (Mass Assignment)
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'icon',
        'warna',
    ];
}
