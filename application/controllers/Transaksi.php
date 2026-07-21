<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[AllowDynamicProperties]
class Transaksi extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Transaksi_model');
        $this->load->model('Kategori_model');
        $this->load->library('pagination');
    }

    public function index()
    {
        $filters = array(
            'month'       => $this->input->get('bulan'),
            'year'        => $this->input->get('tahun'),
            'type'        => $this->input->get('tipe'),
            'category_id' => $this->input->get('kategori'),
            'keyword'     => $this->input->get('q'),
        );

        $per_page     = 15;
        $current_page = (int) ($this->input->get('page') ?: 1);
        $offset       = ($current_page - 1) * $per_page;

        $total_rows = $this->Transaksi_model->count_all($this->user_id, $filters);
        $list       = $this->Transaksi_model->get_all($this->user_id, $filters, $per_page, $offset);

        $config = array(
            'base_url'       => site_url('transaksi'),
            'total_rows'     => $total_rows,
            'per_page'       => $per_page,
            'use_page_numbers' => true,
            'page_query_string' => true,
            'query_string_segment' => 'page',
            'full_tag_open'  => '<ul class="pagination">',
            'full_tag_close' => '</ul>',
            'first_tag_open' => '<li class="page-item">', 'first_tag_close' => '</li>',
            'last_tag_open'  => '<li class="page-item">', 'last_tag_close' => '</li>',
            'next_tag_open'  => '<li class="page-item">', 'next_tag_close' => '</li>',
            'prev_tag_open'  => '<li class="page-item">', 'prev_tag_close' => '</li>',
            'cur_tag_open'   => '<li class="page-item active"><span class="page-link">', 'cur_tag_close' => '</span></li>',
            'num_tag_open'   => '<li class="page-item">', 'num_tag_close' => '</li>',
            'attributes'     => array('class' => 'page-link'),
        );
        $this->pagination->initialize($config);

        $this->data['title']       = 'Data Transaksi';
        $this->data['list']        = $list;
        $this->data['pagination']  = $this->pagination->create_links();
        $this->data['total_rows']  = $total_rows;
        $this->data['categories']  = $this->Kategori_model->get_all($this->user_id);
        $this->data['filters']     = $filters;

        $this->load->view('transaksi/index', $this->data);
    }

    public function create()
    {
        if ($this->input->post()) {
            $this->_validate();

            if ($this->form_validation->run() === TRUE) {
                $category = $this->Kategori_model->get($this->input->post('category_id'), $this->user_id);
                $this->Transaksi_model->create(array(
                    'user_id'     => $this->user_id,
                    'category_id' => $this->input->post('category_id'),
                    'type'        => $category ? $category->type : $this->input->post('type'),
                    'amount'      => (float) str_replace(array('.', ','), array('', '.'), $this->input->post('amount')),
                    'description' => $this->input->post('description', true),
                    'trx_date'    => $this->input->post('trx_date'),
                ));
                $this->session->set_flashdata('success', 'Transaksi berhasil ditambahkan.');
                redirect('transaksi');
                return;
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }
        redirect('transaksi');
    }

    public function update($id)
    {
        $trx = $this->Transaksi_model->get($id, $this->user_id);
        if (!$trx) { show_404(); return; }

        if ($this->input->post()) {
            $this->_validate();
            if ($this->form_validation->run() === TRUE) {
                $category = $this->Kategori_model->get($this->input->post('category_id'), $this->user_id);
                $this->Transaksi_model->update($id, $this->user_id, array(
                    'category_id' => $this->input->post('category_id'),
                    'type'        => $category ? $category->type : $this->input->post('type'),
                    'amount'      => (float) str_replace(array('.', ','), array('', '.'), $this->input->post('amount')),
                    'description' => $this->input->post('description', true),
                    'trx_date'    => $this->input->post('trx_date'),
                ));
                $this->session->set_flashdata('success', 'Transaksi berhasil diperbarui.');
            } else {
                $this->session->set_flashdata('error', validation_errors());
            }
        }
        redirect('transaksi');
    }

    public function delete($id)
    {
        $this->Transaksi_model->delete($id, $this->user_id);
        $this->session->set_flashdata('success', 'Transaksi berhasil dihapus.');
        redirect('transaksi');
    }

    private function _validate()
    {
        $this->form_validation->set_rules('category_id', 'Kategori', 'required|integer');
        $this->form_validation->set_rules('amount', 'Jumlah', 'required');
        $this->form_validation->set_rules('trx_date', 'Tanggal', 'required');
    }
}
