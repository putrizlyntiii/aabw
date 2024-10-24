<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;

class Transaksi extends ResourceController
{
    protected $db;
    protected $objTransaksi;
    protected $objNilai;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->objTransaksi = model('ModelTransaksi');
        $this->objNilai = model('ModelNilai');
    }

    /**
     * Return an array of all transactions.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $data['dttransaksi'] = $this->objTransaksi->findAll();
        return view('transaksi/index', $data);
    }

    /**
     * Display the form for creating a new transaction.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        return view('transaksi/new');
    }

    /**
     * Store a newly created transaction and associated data in the database.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $this->db->transStart(); // Start a database transaction

        // Prepare data for tbl_transaksi
        $dataTransaksi = [
            'kwitansi' => $this->request->getVar('kwitansi'),
            'tanggal' => $this->request->getVar('tanggal'),
            'deskripsi' => $this->request->getVar('deskripsi'),
            'ketjurnal' => $this->request->getVar('ketjurnal'),
        ];

        // Insert transaction data
        $this->db->table('tbl_transaksi')->insert($dataTransaksi);
        $id_transaksi = $this->db->insertID();

        if (!$id_transaksi) {
            return redirect()->back()->with('error', 'Failed to save transaction data');
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
        $this->objNilai->insertBatch($dataNilai);

        // Commit transaction
        if ($this->db->transComplete()) {
            return redirect()->to(site_url('transaksi'))->with('success', 'Data Berhasil di Simpan');
        } else {
            return redirect()->back()->with('error', 'Failed to save data');
        }
    }

    /**
     * Delete a specific transaction and its associated data.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        if (!$this->objTransaksi->find($id)) {
            return redirect()->back()->with('error', 'Transaction not found');
        }

        $this->objTransaksi->delete($id);
        return redirect()->to(site_url('transaksi'))->with('success', 'Data Berhasil Dihapus');
    }

    /**
     * Return a JSON response of all akun3 records.
     *
     * @return ResponseInterface
     */
    public function akun3()
    {
        $akun3Model = model('ModelAkun3');
        $result = $akun3Model->findAll();
        return $this->response->setJSON($result);
    }

    /**
     * Return a JSON response of all status records.
     *
     * @return ResponseInterface
     */
    public function status()
    {
        $statusModel = model('ModelStatus');
        $result = $statusModel->findAll();
        return $this->response->setJSON($result);
    }
}