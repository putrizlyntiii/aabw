<?php

namespace App\Controllers;

use App\Models\ModelAkun3;
use App\Models\ModelNilai;
use App\Models\ModelStatus;
use App\Models\ModelTransaksi;
use App\Controllers\BaseController;
use TCPDF;

class NeracaLajur extends BaseController
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

        $rowdata = $this->objTransaksi->get_neracalajur($tglawal,$tglakhir);
        $data['dttransaksi'] = $rowdata;
        $data['tglawal'] = $tglawal;
        $data['tglakhir'] = $tglakhir;

        return view('neracalajur/index', $data);
    }

    public function neracalajurpdf()
    {
        $tglawal =$this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir =$this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';

        $rowdata = $this->objTransaksi->get_neracalajur($tglawal,$tglakhir);
        $data['dttransaksi'] = $rowdata;
        $data['tglawal'] = $tglawal;
        $data['tglakhir'] = $tglakhir;

        $html = view('neracalajur/neracalajurpdf', $data);
        // Create new PDF document
        $pdf = new \TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
                
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
        $pdf->Output('neracalajurpdf.pdf', 'I');
    }
        
}
