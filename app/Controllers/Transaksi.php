<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;

class Transaksi extends ResourceController
{
    protected $db;
    protected $objTransaksi;
    protected $objNilai;
    protected $objAkun3; 
    protected $objStatus;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->objTransaksi = model('ModelTransaksi');
        $this->objNilai = model('ModelNilai');
        $this->objAkun3 = model('ModelAkun3'); 
        $this->objStatus = model('ModelStatus');
    }

    public function index()
    {
        $data['dttransaksi'] = $this->objTransaksi->findAll();
        return view('transaksi/index', $data);
    }

    public function show($id = null)
    {
        $transaksi = $this->objTransaksi->find($id);
        if (!$transaksi) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $akun3 = $this->objAkun3->findAll();
        $status = $this->objStatus->findAll();
        $nilai = $this->objNilai->ambilrelasiid($id);

        $data = [
            'dtakun3' => $akun3,
            'dstatus' => $status,
            'dttransaksi' => $transaksi,
            'dtnilai' => $nilai,
        ];

        return view('transaksi/show', $data);
    }

    public function new()
    {
        return view('transaksi/new');
    }

    public function create()
    {
        // Validasi input
        $validation = \Config\Services::validation();
        $rules = [
            'tanggal' => 'required',
            'deskripsi' => 'required',
            'ketjurnal' => 'required',
            'kode_akun3' => 'required',
            'debit' => 'required|numeric',
            'kredit' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $validation->getErrors())->withInput();
        }

        $this->db->transStart(); // Start transaction

        // Prepare data for tbl_transaksi
        $dataTransaksi = [
            'kwitansi' => $this->objTransaksi->noKwitansi(),
            'tanggal' => $this->request->getVar('tanggal'),
            'deskripsi' => $this->request->getVar('deskripsi'),
            'ketjurnal' => $this->request->getVar('ketjurnal'),
        ];

        // Insert transaction data
        $this->db->table('tbl_transaksi')->insert($dataTransaksi);
        $id_transaksi = $this->db->insertID();

        if ($this->db->affectedRows() <= 0) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to save transaction data')->withInput();
        }

        // Prepare data for tbl_nilai (details)
        $kode_akun3 = $this->request->getVar('kode_akun3');
        $debit = $this->request->getVar('debit');
        $kredit = $this->request->getVar('kredit');
        $id_status = $this->request->getVar('id_status');

        $dataNilai = [];
        for ($i = 0; $i < count($kode_akun3); $i++) {
            $dataNilai[] = [
                'id_transaksi' => $id_transaksi,
                'kode_akun3' => $kode_akun3[$i],
                'debit' => $debit[$i],
                'kredit' => $kredit[$i],
                'id_status' => $id_status[$i],
            ];
        }

        // Insert batch of records into tbl_nilai
        if (!$this->objNilai->insertBatch($dataNilai)) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Failed to save transaction details')->withInput();
        }

        // Commit transaction
        $this->db->transComplete();

        return redirect()->to(site_url('transaksi'))->with('success', 'Data Berhasil di Simpan');
    }

    public function edit($id = null)
    {
        $transaksi = $this->objTransaksi->find($id);
        if (!$transaksi) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $akun3 = $this->objAkun3->findAll();
        $status = $this->objStatus->findAll();
        $nilai = $this->objNilai->where('id_transaksi', $id)->findAll();

        $data = [
            'dtakun3' => $akun3,
            'dstatus' => $status,
            'dttransaksi' => $transaksi,
            'dtnilai' => $nilai,
        ];

        return view('transaksi/edit', $data);
    }

    public function delete($id = null)
    {
        if (!$this->objTransaksi->find($id)) {
            return redirect()->back()->with('error', 'Transaction not found');
        }

        $this->objTransaksi->delete($id);
        return redirect()->to(site_url('transaksi'))->with('success', 'Data Berhasil Dihapus');
    }

    public function update($id = null)
    {
        // Validasi input
        $validation = \Config\Services::validation();
        $rules = [
            'tanggal' => 'required',
            'deskripsi' => 'required',
            'ketjurnal' => 'required',
            'kode_akun3' => 'required',
            'debit' => 'required|numeric',
            'kredit' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $validation->getErrors())->withInput();
        }

        $dataTransaksi = [
            'tanggal' => $this->request->getVar('tanggal'),
            'deskripsi' => $this->request->getVar('deskripsi'),
            'ketjurnal' => $this->request->getVar('ketjurnal'),
        ];
        
        $this->db->table('tbl_transaksi')->where(['id_transaksi' => $id])->update($dataTransaksi);

        $ids = $this->request->getVar('id_nilai');
        $kode_akun3 = $this->request->getVar('kode_akun3');
        $debit = $this->request->getVar('debit');
        $kredit = $this->request->getVar('kredit');
        $id_status = $this->request->getVar('id_status');

        $dataNilai = [];
        foreach ($ids as $key => $value) {
            $dataNilai[] = [
                'id_nilai' => $ids[$key],
                'kode_akun3' => $kode_akun3[$key],
                'debit' => $debit[$key],
                'kredit' => $kredit[$key],
                'id_status' => $id_status[$key],
            ];
        }

        $this->objNilai->updateBatch($dataNilai, 'id_nilai');

        return redirect()->to(site_url('transaksi'))->with('success', 'Data Berhasil Di Update');
    }

    public function akun3()
    {
        $akun3Model = model('ModelAkun3');
        $result = $akun3Model->findAll();
        return $this->response->setJSON($result);
    }

    public function status()
    {
        $statusModel = model('ModelStatus');
        $result = $statusModel->findAll();
        return $this->response->setJSON($result);
    }
}