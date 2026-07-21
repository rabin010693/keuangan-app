<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base Controller
 * Semua controller yang butuh login harus extend class ini.
 */
class MY_Controller extends CI_Controller
{
    protected $user_id;
    protected $user;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('User_model');

        // Cek session login
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
            return;
        }

        $this->user_id = $this->session->userdata('user_id');
        $this->user     = $this->User_model->get($this->user_id);

        if (!$this->user) {
            $this->session->sess_destroy();
            redirect('login');
            return;
        }

        // Data yang selalu tersedia di semua view yang extend layout
        $this->data['current_user'] = $this->user;
    }
}

/**
 * Public Controller (tanpa perlu login) - dipakai oleh Auth
 */
class Public_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
}
