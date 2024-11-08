<?php

namespace App\Controllers;

use App\Models\ModelAkun3;
use App\Models\ModelNilai;
use App\Models\ModelStatus;
use App\Models\ModelTransaksi;
use App\Controllers\BaseController;
use TCPDF;

class JurnalUmum extends BaseController
{
    protected $db;
    protected $objTransaksi;
    protected $objNilai;
    protected $objAkun3;
    protected $objStatus;

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
        $rowdata = $this->objTransaksi->get_jurnalumum($tglawal,$tglakhir);
        $i= 0;
        $temp1 = '';
        $temp2 = '';

        foreach ($rowdata as $row) {
            $tgl = ($temp1 == $row->tanggal && $temp2 == $row->kwitansi) ? '' : $row->tanggal;
            $temp1 = $row->tanggal;
            $temp2 = $row->kwitansi;
            $rowdata[$i]->tanggal =$tgl;
            $i++;
        }
        $data['dttransaksi'] = $rowdata;
        $data['tglawal'] = $tglawal;
        $data['tglakhir'] = $tglakhir;

        return view('App\Views\jurnalumum\index', $data);
    }

    public function cetakjupdf()
{
    $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
    $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';
    
    // Ensure $rowdata is fetched correctly
    $rowdata = $this->objTransaksi->get_jurnalumum($tglawal, $tglakhir);
    if (!$rowdata) {
        return $this->response->setStatusCode(404, 'Data not found.');
    }

    $i = 0;
    $temp1 = '';
    $temp2 = '';

    foreach ($rowdata as $row) {
        $tgl = ($temp1 === $row->tanggal && $temp2 === $row->kwitansi) ? '' : $row->tanggal;
        $temp1 = $row->tanggal;
        $temp2 = $row->kwitansi;
        $rowdata[$i]->tanggal = $tgl;
        $i++;
    }

    $data = [
        'dttransaksi' => $rowdata,
        'tglawal' => $tglawal,
        'tglakhir' => $tglakhir,
    ];

    $html = view('jurnalumum/cetakjupdf', $data);
    
    // Ensure TCPDF is loaded
    if (!class_exists('TCPDF')) {
        throw new \Exception('TCPDF class not found.');
    }

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
    $pdf->Output('jurnalumum.pdf', 'I');
}




}  