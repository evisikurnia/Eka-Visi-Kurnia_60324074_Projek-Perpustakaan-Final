<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori; // Pastikan ini di-import

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoriList = [
            [
                'nama_kategori' => 'Programming',
                'deskripsi' => 'Kumpulan buku materi pemrograman komputer',
                'icon' => 'code-slash',
                'warna' => 'primary',
            ],
            [
                'nama_kategori' => 'Database',
                'deskripsi' => 'Buku seputar pengolahan basis data dan query',
                'icon' => 'database',
                'warna' => 'success',
            ],
            [
                'nama_kategori' => 'Web Design',
                'deskripsi' => 'Materi perancangan tampilan visual website',
                'icon' => 'palette',
                'warna' => 'info',
            ],
            [
                'nama_kategori' => 'Networking',
                'deskripsi' => 'Buku jaringan komputer dan keamanannya',
                'icon' => 'wifi',
                'warna' => 'warning',
            ],
            [
                'nama_kategori' => 'Data Science',
                'deskripsi' => 'Analisis data, statistik, dan machine learning',
                'icon' => 'graph-up',
                'warna' => 'danger',
            ],
        ];

        foreach ($kategoriList as $kategori) {
            Kategori::create($kategori);
        }
    }
}