<?php

namespace App\Controllers;

use App\Models\ModelAkun3;
use App\Models\ModelStatus;
use App\Models\ModelPenyesuaian;
use App\Models\ModelNilaiPenyesuaian;
use CodeIgniter\RESTful\ResourceController;

class Penyesuaian extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->objPenyesuaian = new ModelPenyesuaian();
        $this->objNilaiPenyesuaian = new ModelNilaiPenyesuaian();
        // $this->objAkun3 = new ModelAkun3();
        // $this->objStatus = new ModelStatus();
    }

    public function index()
    {
        $data['dtpenyesuaian'] = $this->objPenyesuaian->findAll();
        return view('penyesuaian/index', $data);
    }

    public function new()
    {
        return view('penyesuaian/new');
    }

    public function create()
    {
        $data1 = [
            'tanggal' => $this->request->getVar('tanggal'),
            'deskripsi' => $this->request->getVar('deskripsi'),
            'nilai' => $this->request->getVar('nilai'),
            'waktu' => $this->request->getVar('waktu'),
            'jumlah' => $this->request->getVar('jumlah'),
        ];

        // Simpan data ke tbl_penyesuaian
        $this->db->table('tbl_penyesuaian')->insert($data1);

        // Ambil ID dari tbl_penyesuaian
        $id_penyesuaian = $this->db->insertID(); // Menggunakan insertID dari koneksi DB
        $kode_akun3 = $this->request->getVar('kode_akun3');
        $debit = $this->request->getVar('debit');
        $kredit = $this->request->getVar('kredit');
        $id_status = $this->request->getVar('id_status');

        // Siapkan data untuk disimpan di tbl_nilai_penyesuaian
        $data2 = [];
        for ($i = 0; $i < count($kode_akun3); $i++) {
            $data2[] = [
                'id_penyesuaian' => $id_penyesuaian,
                'kode_akun3' => $kode_akun3[$i],
                'debit' => $debit[$i],
                'kredit' => $kredit[$i],
                'id_status' => $id_status[$i],
            ];
        }

        // Simpan batch data
        if (!empty($data2)) {
            $this->objNilaiPenyesuaian->insertBatch($data2);
        }

        return redirect()->to(site_url('transaksi'))->with('success', 'Data Berhasil disimpan');
    }
}
