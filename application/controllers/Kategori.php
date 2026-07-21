<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[AllowDynamicProperties]
class Kategori extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kategori_model');
    }

    public function index()
    {
        $this->data['title']       = 'Kategori';
        $this->data['categories']  = $this->Kategori_model->get_all($this->user_id);
        $this->load->view('kategori/index', $this->data);
    }

    public function create()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Nama Kategori', 'required|trim');
            $this->form_validation->set_rules('type', 'Tipe', 'required|in_list[income,expense]');

            if ($this->form_validation->run() === TRUE) {
                $this->Kategori_model->create(array(
                    'user_id' => $this->user_id,
                    'name'    => $this->input->post('name', true),
                    'type'    => $this->input->post('type'),
                    'icon'    => $this->input->post('icon') ?: 'fa-wallet',
                    'color'   => $this->input->post('color') ?: '#0d6efd',
                ));
                $this->session->set_flashdata('success', 'Kategori berhasil ditambahkan.');
                redirect('kategori');
                return;
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }
        redirect('kategori');
    }

    public function update($id)
    {
        $kategori = $this->Kategori_model->get($id, $this->user_id);
        if (!$kategori) {
            show_404();
            return;
        }

        if ($this->input->post()) {
            $this->Kategori_model->update($id, $this->user_id, array(
                'name'  => $this->input->post('name', true),
                'type'  => $this->input->post('type'),
                'icon'  => $this->input->post('icon') ?: 'fa-wallet',
                'color' => $this->input->post('color') ?: '#0d6efd',
            ));
            $this->session->set_flashdata('success', 'Kategori berhasil diperbarui.');
        }
        redirect('kategori');
    }

    public function delete($id)
    {
        $ok = $this->Kategori_model->delete($id, $this->user_id);
        if ($ok) {
            $this->session->set_flashdata('success', 'Kategori berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Kategori tidak bisa dihapus karena masih dipakai pada transaksi.');
        }
        redirect('kategori');
    }
}
