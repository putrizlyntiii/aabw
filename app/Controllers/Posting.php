<?php

namespace App\Controllers;

use App\Models\ModelAkun3;
use App\Models\ModelNilai;
use App\Models\ModelStatus;
use App\Models\ModelTransaksi;
use App\Controllers\BaseController;
use TCPDF;

class Posting extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->objTransaksi = new ModelTransaksi();
        $this->objNilai = new ModelNilai();
        $this->objAkun3 = new ModelAkun3();
        $this->objStatus = new ModelStatus();
    }

    public function index()
    {
        $tglawal =$this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir =$this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';
        $tglawal =$this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $kode_akun3 =$this->request->getVar('kode_akun3') ? $this->request->getVar('kode_akun3') : '';

        $rowdata = $this->objTransaksi->get_posting($tglawal,$tglakhir,$kode_akun3);
        $data['dttransaksi'] = $rowdata;
        $data['tglawal'] = $tglawal;
        $data['tglakhir'] = $tglakhir;
        $data['kode_akun3'] = $kode_akun3;
        $data['dtakun3'] = $this->objAkun3->ambilrelasi();

        return view('posting\index', $data);
    }

    public function postingpdf()
    {
        $tglawal =$this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir =$this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';
        $tglawal =$this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $kode_akun3 =$this->request->getVar('kode_akun3') ? $this->request->getVar('kode_akun3') : '';

        $rowdata = $this->objTransaksi->get_posting($tglawal,$tglakhir,$kode_akun3);
        $data['dttransaksi'] = $rowdata;
        $data['tglawal'] = $tglawal;
        $data['tglakhir'] = $tglakhir;
        $data['kode_akun3'] = $kode_akun3;
        $data['dtakun3'] = $this->objAkun3->ambilrelasi();

       $html = view('posting\postingpdf', $data);

        // Create new PDF document
        $pdf = new \TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set margins
        $pdf->SetMargins(30,4, 3);
        
        // Set font
        $pdf->SetFont('helvetica', '', 8);
        
        // Add a page
        $pdf->AddPage();
        
        // Print text using writeHTML()
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Set PDF headers
        $this->response->setContentType('application/pdf');
        // Output PDF
        $pdf->Output('posting.pdf', 'I');
    }



}