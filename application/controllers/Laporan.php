<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[AllowDynamicProperties]
class Laporan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Transaksi_model');
    }

    public function index()
    {
        $bulan = (int) ($this->input->get('bulan') ?: date('n'));
        $tahun = (int) ($this->input->get('tahun') ?: date('Y'));

        $this->data['bulan']   = $bulan;
        $this->data['tahun']   = $tahun;
        $this->data['title']   = 'Laporan Bulanan';
        $this->data['list']    = $this->Transaksi_model->get_for_report($this->user_id, $bulan, $tahun);
        $this->data['summary'] = $this->Transaksi_model->get_summary($this->user_id, $bulan, $tahun);
        $this->data['by_cat']  = $this->Transaksi_model->get_expense_by_category($this->user_id, $bulan, $tahun);

        $this->load->view('laporan/index', $this->data);
    }

    /**
     * Export laporan bulanan ke Excel (.xls, dibuka native oleh MS Excel)
     */
    public function export_excel()
    {
        $bulan = (int) ($this->input->get('bulan') ?: date('n'));
        $tahun = (int) ($this->input->get('tahun') ?: date('Y'));

        $list    = $this->Transaksi_model->get_for_report($this->user_id, $bulan, $tahun);
        $summary = $this->Transaksi_model->get_summary($this->user_id, $bulan, $tahun);

        $filename = 'Laporan_Keuangan_' . nama_bulan($bulan) . '_' . $tahun . '.xls';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $data = array(
            'list'    => $list,
            'summary' => $summary,
            'bulan'   => $bulan,
            'tahun'   => $tahun,
            'user'    => $this->user,
        );

        $this->load->view('laporan/export_excel', $data);
    }

    /**
     * Export laporan bulanan ke PDF menggunakan TCPDF
     */
    public function export_pdf()
    {
        $bulan = (int) ($this->input->get('bulan') ?: date('n'));
        $tahun = (int) ($this->input->get('tahun') ?: date('Y'));

        $list    = $this->Transaksi_model->get_for_report($this->user_id, $bulan, $tahun);
        $summary = $this->Transaksi_model->get_summary($this->user_id, $bulan, $tahun);
        $by_cat  = $this->Transaksi_model->get_expense_by_category($this->user_id, $bulan, $tahun);

        // Render bagian isi tabel ke HTML string dulu
        $html = $this->load->view('laporan/export_pdf', array(
            'list'    => $list,
            'summary' => $summary,
            'by_cat'  => $by_cat,
            'bulan'   => $bulan,
            'tahun'   => $tahun,
            'user'    => $this->user,
        ), true);

        require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Aplikasi Keuangan Pribadi');
        $pdf->SetAuthor($this->user->name);
        $pdf->SetTitle('Laporan Keuangan ' . nama_bulan($bulan) . ' ' . $tahun);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');

        $filename = 'Laporan_Keuangan_' . nama_bulan($bulan) . '_' . $tahun . '.pdf';
        $pdf->Output($filename, 'D'); // D = force download
    }
}
