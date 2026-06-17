<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionModel;

class LaporanController extends BaseController
{
    public function pendapatan()
    {
        $model         = new TransactionModel();
        $tanggal_awal  = $this->request->getGet('tanggal_awal');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir');
        $laporan = [];

        if ($tanggal_awal && $tanggal_akhir) {
            $laporan = $model
                ->where('status >=', 1)
                ->where('created_at >=', $tanggal_awal . ' 00:00:00')
                ->where('created_at <=', $tanggal_akhir . ' 23:59:59')
                ->findAll();
        }

        return view('laporan_pendapatan', [
            'laporan'       => $laporan,
            'tanggal_awal'  => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir
        ]);
    }

    public function exportPdf()
    {
        $tanggal_awal  = $this->request->getGet('tanggal_awal');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir');
        $model  = new \App\Models\TransactionModel();
        $laporan = $model
            ->where('status >=', 1)
            ->where('created_at >=', $tanggal_awal . ' 00:00:00')
            ->where('created_at <=', $tanggal_akhir . ' 23:59:59')
            ->findAll();

        $dompdf = new \Dompdf\Dompdf();
        $html   = view('laporan_pdf', [
            'laporan'       => $laporan,
            'tanggal_awal'  => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir
        ]);
        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream("laporan-pendapatan.pdf");
    }

    public function exportExcel()
    {
        $tanggal_awal  = $this->request->getGet('tanggal_awal');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir');
        $model  = new \App\Models\TransactionModel();
        $laporan = $model
            ->where('status >=', 1)
            ->where('created_at >=', $tanggal_awal . ' 00:00:00')
            ->where('created_at <=', $tanggal_akhir . ' 23:59:59')
            ->findAll();

        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=laporan-pendapatan.xls");
        echo view('laporan_excel', ['laporan' => $laporan]);
    }
}
