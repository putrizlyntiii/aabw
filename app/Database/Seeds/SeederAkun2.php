<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SeederAkun2 extends Seeder
{
    public function run()
    {
        $data = [
            [
                'kode_akun2' => 11,
                'nama_akun2' => 'Aktiva lancar',
                'kode_akun1' => 1, // Use an integer
            ],
            [
                'kode_akun2' => 12,
                'nama_akun2' => 'Aktiva Tetap',
                'kode_akun1' => 1,
            ],
            [
                'kode_akun2' => 21,
                'nama_akun2' => 'Utang Jangka Pendek',
                'kode_akun1' => 2,
            ],
            [
                'kode_akun2' => 22,
                'nama_akun2' => 'Utang Jangka Panjang',
                'kode_akun1' => 2,
            ],
            [
                'kode_akun2' => 31,
                'nama_akun2' => 'Modal Pemilik',
                'kode_akun1' => 3,
            ],
            [
                'kode_akun2' => 32,
                'nama_akun2' => 'Prive Pemilik',
                'kode_akun1' => 3,
            ],
            [
                'kode_akun2' => 41,
                'nama_akun2' => 'Pendapatan Usaha',
                'kode_akun1' => 4,
            ],
            [
                'kode_akun2' => 42,
                'nama_akun2' => 'Pendapatan diluar Usaha',
                'kode_akun1' => 4,
            ],
            [
                'kode_akun2' => 51,
                'nama_akun2' => 'Beban Usaha',
                'kode_akun1' => 5,
            ],
            [
                'kode_akun2' => 52,
                'nama_akun2' => 'Beban diluar Usaha', // Fixed string closure
                'kode_akun1' => 5,
            ], 
        ];

        // Using Query Builder to insert data
        $this->db->table('akun2s')->insertBatch($data);
    }
}
