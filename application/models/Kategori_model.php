<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_model extends CI_Model
{
    protected $table = 'categories';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all($user_id, $type = null)
    {
        $this->db->where('user_id', $user_id);
        if ($type) {
            $this->db->where('type', $type);
        }
        return $this->db->order_by('type', 'ASC')->order_by('name', 'ASC')->get($this->table)->result();
    }

    public function get($id, $user_id)
    {
        return $this->db->where('id', $id)->where('user_id', $user_id)->get($this->table)->row();
    }

    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $user_id, $data)
    {
        return $this->db->where('id', $id)->where('user_id', $user_id)->update($this->table, $data);
    }

    public function delete($id, $user_id)
    {
        // Cegah hapus kategori yang masih dipakai transaksi
        $count = $this->db->where('category_id', $id)->count_all_results('transactions');
        if ($count > 0) {
            return false;
        }
        return $this->db->where('id', $id)->where('user_id', $user_id)->delete($this->table);
    }

    public function is_used($id)
    {
        return $this->db->where('category_id', $id)->count_all_results('transactions') > 0;
    }
}
