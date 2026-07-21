<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    protected $table = 'users';

    public function __construct()
    {
        parent::__construct();
    }

    public function get($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    public function get_by_username($username)
    {
        return $this->db->where('username', $username)->get($this->table)->row();
    }

    public function verify_login($username, $password)
    {
        $user = $this->get_by_username($username);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user->password)) {
            return false;
        }

        return $user;
    }

    public function create($data)
    {
        $data['password']   = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id', $id)->update($this->table, $data);
    }
}
