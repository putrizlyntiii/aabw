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
        $this->objAkun3 = new ModelAkun3();
        $this->objStatus = new ModelStatus();
    }


    public function index()
    {
        $data['dtpenyesuaian'] = $this->objPenyesuaian->findAll();
        return view('penyesuaian/index', $data);
    }

    public function show($id = null)
    {
        $penyesuaian = $this->objPenyesuaian->find($id);
        $akun3 = $this->objAkun3->findAll();
        $status = $this->objStatus->findAll();
        $nilai = $this->objNilaiPenyesuaian->ambilrelasiid($id);

        if ($penyesuaian) {
            $data['dtpenyesuaian'] = $penyesuaian; // Perbaikan di sini
            $data['dtnilaipenyesuaian'] = $nilai;
            $data['dtakun3'] = $akun3;
            $data['dtstatus'] = $status;

            return view('penyesuaian/show', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
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

        return redirect()->to(site_url('penyesuaian'))->with('success', 'Data Berhasil disimpan');
    }

        public function edit($id = null)
        {
        $penyesuaian = $this->objPenyesuaian->find($id);
        $akun3 = $this->objAkun3->findAll();
        $status = $this->objStatus->findAll();
        $nilai = $this->objNilaiPenyesuaian->findAll();
        
        if (is_object($penyesuaian)) {
            $data = [
                'dtpenyesuaian' => $penyesuaian, // Sesuaikan nama variabel
                'dtakun3' => $akun3,
                'dtstatus' => $status,
                'dtnilai' => $nilai // Pastikan ini sesuai dengan yang ada di view
            ];

                return view('penyesuaian/edit', $data);
            } else {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }


        public function update($id = null)
        {
            $data1 = [
                'tanggal' => $this->request->getVar('tanggal'),
                'deskripsi' => $this->request->getVar('deskripsi'),
                'nilai' => $this->request->getVar('nilai'),
                'waktu' => $this->request->getVar('waktu'),
                'jumlah' => $this->request->getVar('jumlah'),
            ];
        
            // Simpan data ke tbl_penyesuaian
            $this->db->table('tbl_penyesuaian')->where(['id_penyesuaian' => $id])->update($data1);
        
            // Ambil data nilai penyesuaian
            $ids = $this->request->getVar('id'); // Pastikan ini adalah array
            $kode_akun3 = $this->request->getVar('kode_akun');
            $debit = $this->request->getVar('debit');
            $kredit = $this->request->getVar('kredit');
            $id_status = $this->request->getVar('status'); // Sesuaikan dengan nama input di form
        
            $result = []; // Inisialisasi array hasil
            foreach ($ids as $key => $value) {
                $result[] = [
                    'id' => $ids[$key],
                    'kode_akun3' => $kode_akun3[$key],
                    'debit' => $debit[$key],
                    'kredit' => $kredit[$key],
                    'id_status' => $id_status[$key],
                ];
            }
        
            // Pastikan ada data untuk diupdate
            if (!empty($result)) {
                $this->objNilaiPenyesuaian->updateBatch($result, 'id');
            }
        
            return redirect()->to(site_url('penyesuaian'))->with('success', 'Data Berhasil di Update');
        }
        
    public function delete($id = null)
    {
        $this->objPenyesuaian->where(['id_penyesuaian' => $id])->DELETE();
        return redirect()->to(site_url('penyesuaian'))->with('success', 'Data Berhasil Di Hapus');
    }
}