<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_model extends CI_Model
{
    protected $table = 'transactions';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Query dasar dengan filter umum (bulan, tahun, tipe, kategori, pencarian)
     */
    private function apply_filters($user_id, $filters = array())
    {
        $this->db->where('t.user_id', $user_id);

        if (!empty($filters['month'])) {
            $this->db->where("EXTRACT(MONTH FROM t.trx_date) =", $filters['month']);
        }
        if (!empty($filters['year'])) {
            $this->db->where("EXTRACT(YEAR FROM t.trx_date) =", $filters['year']);
        }
        if (!empty($filters['type'])) {
            $this->db->where('t.type', $filters['type']);
        }
        if (!empty($filters['category_id'])) {
            $this->db->where('t.category_id', $filters['category_id']);
        }
        if (!empty($filters['keyword'])) {
            $this->db->like('t.description', $filters['keyword']);
        }
        if (!empty($filters['date_from'])) {
            $this->db->where('t.trx_date >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $this->db->where('t.trx_date <=', $filters['date_to']);
        }
    }

    public function get_all($user_id, $filters = array(), $limit = null, $offset = 0)
    {
        $this->db->select('t.*, c.name as category_name, c.icon as category_icon, c.color as category_color')
                 ->from($this->table . ' t')
                 ->join('categories c', 'c.id = t.category_id', 'left');
                 

        $this->apply_filters($user_id, $filters);

        $this->db->order_by('t.trx_date', 'DESC')->order_by('t.id', 'DESC');

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result();
    }

    public function count_all($user_id, $filters = array())
    {
        $this->db->from($this->table . ' t');
        $this->apply_filters($user_id, $filters);
        return $this->db->count_all_results();
    }

    public function get($id, $user_id)
    {
        return $this->db->select('t.*, c.name as category_name, c.type as category_type')
                    ->from($this->table . ' t')
                    ->join('categories c', 'c.id = t.category_id', 'left')
                    ->where('t.id', $id)->where('t.user_id', $user_id)
                    ->get()->row();
    }

    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $user_id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id', $id)->where('user_id', $user_id)->update($this->table, $data);
    }

    public function delete($id, $user_id)
    {
        return $this->db->where('id', $id)->where('user_id', $user_id)->delete($this->table);
    }

    /**
     * Total pemasukan & pengeluaran user pada bulan & tahun tertentu
     */
    public function get_summary($user_id, $month = null, $year = null)
    {
        $this->db->select("
                SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense
            ", false)
            ->where('user_id', $user_id);

        if ($month) $this->db->where("EXTRACT(MONTH FROM trx_date) =", $month);
        if ($year)  $this->db->where("EXTRACT(YEAR FROM trx_date) =", $year);

        $row = $this->db->get($this->table)->row();

        return array(
            'total_income'  => $row->total_income ? (float) $row->total_income : 0,
            'total_expense' => $row->total_expense ? (float) $row->total_expense : 0,
        );
    }

    /**
     * Saldo total keseluruhan (saldo awal + semua pemasukan - semua pengeluaran)
     */
    public function get_total_balance($user_id, $saldo_awal = 0)
    {
        $summary = $this->get_summary($user_id);
        return $saldo_awal + $summary['total_income'] - $summary['total_expense'];
    }

    /**
     * Data cashflow per bulan dalam satu tahun (untuk grafik)
     */
    public function get_monthly_cashflow($user_id, $year)
    {
        $this->db->select("
                EXTRACT(MONTH FROM trx_date) as bulan,
                SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
            ", false)
            ->where('user_id', $user_id)
            ->where("EXTRACT(YEAR FROM trx_date) =", $year)
            ->group_by("EXTRACT(MONTH FROM trx_date)")
            ->order_by('bulan', 'ASC');

        $rows = $this->db->get($this->table)->result();

        // Susun 12 bulan penuh walau datanya kosong
        $result = array();
        for ($i = 1; $i <= 12; $i++) {
            $result[$i] = array('income' => 0, 'expense' => 0);
        }
        foreach ($rows as $r) {
            $result[(int) $r->bulan] = array(
                'income'  => (float) $r->income,
                'expense' => (float) $r->expense,
            );
        }
        return $result;
    }

    /**
     * Breakdown pengeluaran per kategori pada bulan tertentu (untuk pie chart)
     */
    public function get_expense_by_category($user_id, $month, $year)
    {
        return $this->db->select('c.name, c.color, SUM(t.amount) as total', false)
            ->from($this->table . ' t')
            ->join('categories c', 'c.id = t.category_id', 'left')
            ->where('t.user_id', $user_id)
            ->where('t.type', 'expense')
            ->where("EXTRACT(MONTH FROM t.trx_date) =", $month)
            ->where("EXTRACT(YEAR FROM t.trx_date) =", $year)
            ->group_by('c.name, c.color')
            ->order_by('total', 'DESC')
            ->get()->result();
    }

    /**
     * 5 transaksi terbaru
     */
    public function get_recent($user_id, $limit = 5)
    {
        return $this->db->select('t.*, c.name as category_name, c.icon as category_icon, c.color as category_color')
            ->from($this->table . ' t')
            ->join('categories c', 'c.id = t.category_id', 'left')
            ->where('t.user_id', $user_id)
            ->order_by('t.trx_date', 'DESC')
            ->order_by('t.id', 'DESC')
            ->limit($limit)
            ->get()->result();
    }

    /**
     * Semua transaksi bulan tertentu untuk laporan (tanpa limit)
     */
    public function get_for_report($user_id, $month, $year)
    {
        return $this->get_all($user_id, array('month' => $month, 'year' => $year));
    }
}