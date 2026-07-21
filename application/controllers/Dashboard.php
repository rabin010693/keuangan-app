<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[AllowDynamicProperties]
class Dashboard extends MY_Controller
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

        $summary        = $this->Transaksi_model->get_summary($this->user_id, $bulan, $tahun);
        $saldo_total     = $this->Transaksi_model->get_total_balance($this->user_id, $this->user->saldo_awal);
        $cashflow        = $this->Transaksi_model->get_monthly_cashflow($this->user_id, $tahun);
        $expense_by_cat  = $this->Transaksi_model->get_expense_by_category($this->user_id, $bulan, $tahun);
        $recent          = $this->Transaksi_model->get_recent($this->user_id, 8);

        $this->data['bulan']            = $bulan;
        $this->data['tahun']            = $tahun;
        $this->data['total_income']     = $summary['total_income'];
        $this->data['total_expense']    = $summary['total_expense'];
        $this->data['saldo_bulan']      = $summary['total_income'] - $summary['total_expense'];
        $this->data['saldo_total']      = $saldo_total;
        $this->data['cashflow']         = $cashflow;
        $this->data['expense_by_cat']   = $expense_by_cat;
        $this->data['recent']           = $recent;
        $this->data['title']            = 'Dashboard';

        $this->load->view('dashboard/index', $this->data);
    }
}
