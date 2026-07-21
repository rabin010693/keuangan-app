<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[AllowDynamicProperties]
class Auth extends Public_Controller
{
    private $google_client;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form'); //
        $this->load->model('User_model'); //[cite: 1]

        // Inisialisasi Google Client
        $this->google_client = new Google\Client();
        $this->google_client->setClientId('1072567994284-lco81gqe4b59ajfj1sa076dlqo7l031o.apps.googleusercontent.com');
        $this->google_client->setClientSecret('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');
        $this->google_client->setRedirectUri(site_url('auth/google_callback'));
        $this->google_client->addScope('email');
        $this->google_client->addScope('profile');
    }

    public function login()
    {
        // Jika sudah login, langsung ke dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
            return;
        }

        $data = array('error' => null);

        if ($this->input->post()) {
            $this->form_validation->set_rules('username', 'Username', 'required|trim');
            $this->form_validation->set_rules('password', 'Password', 'required|trim');

            if ($this->form_validation->run() === TRUE) {
                $username = $this->input->post('username', true);
                $password = $this->input->post('password');

                $user = $this->User_model->verify_login($username, $password);

                if ($user) {
                    $this->session->set_userdata(array(
                        'logged_in' => true,
                        'user_id'   => $user->id,
                        'user_name' => $user->name,
                    ));
                    redirect('dashboard');
                    return;
                }

                $data['error'] = 'Username atau password salah.';
            } else {
                $data['error'] = validation_errors();
            }
        }

        $this->load->view('auth/login', $data);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }

    public function register()
    {
        // Jika sudah login, lempar ke dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
            return;
        }

        $data = array('error' => null);

        if ($this->input->post()) {
            // Atur aturan validasi input
            $this->form_validation->set_rules('name', 'Nama Lengkap', 'required|trim');
            $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[users.username]', array(
                'is_unique' => 'Username ini sudah terpakai, pilih username lain.'
            ));
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]', array(
                'is_unique' => 'Email ini sudah terdaftar.'
            ));
            //$this->form_validation->set_rules('saldo_awal', 'Saldo Awal', 'required|numeric');
            // KODE BARU (Sudah Diperbaiki):
            $this->form_validation->set_rules('saldo_awal', 'Saldo Awal', 'numeric|permit_empty');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('passconf', 'Konfirmasi Password', 'required|matches[password]', array(
                'matches' => 'Konfirmasi password tidak sesuai.'
            ));

            if ($this->form_validation->run() === TRUE) {
                // Data disesuaikan dengan atribut tabel `users`
                $userData = array(
                    'name'       => $this->input->post('name', true),
                    'username'   => $this->input->post('username', true),
                    'email'      => $this->input->post('email', true),
                    'password'   => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                    // 'saldo_awal' => $this->input->post('saldo_awal', true),
                    'saldo_awal' => !empty($saldo_input) ? $saldo_input : NULL,
                    
                );

                // Menggunakan method create() yang sudah ada di User_model kamu
                $insert = $this->User_model->create($userData);

                if ($insert) {
                    // Set flashdata pesan sukses dan arahkan ke login
                    $this->session->set_flashdata('success', 'Pendaftaran berhasil! Silakan login.');
                    redirect('login');
                    return;
                } else {
                    $data['error'] = 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.';
                }
            } else {
                $data['error'] = validation_errors();
            }
        }

        $this->load->view('auth/register', $data);
    }

    // Redirect user ke halaman Google Login
    public function google_login()
    {
        redirect($this->google_client->createAuthUrl());
    }

    // Callback setelah user sukses login di Google
    public function google_callback()
    {
        if (isset($_GET['code'])) {
            $token = $this->google_client->fetchAccessTokenWithAuthCode($_GET['code']);
            
            if (!isset($token['error'])) {
                $this->google_client->setAccessToken($token['access_token']);
                $google_service = new Google\Service\Oauth2($this->google_client);
                $google_data = $google_service->userinfo->get();

                // Cek apakah email user sudah ada di database
                $user = $this->db->get_where('users', ['email' => $google_data->email])->row();

                if ($user) {
                    // JIKA USER SUDAH ADA: Langsung Login
                    $this->session->set_userdata(array(
                        'logged_in' => true,
                        'user_id'   => $user->id,
                        'user_name' => $user->name,
                    ));
                    redirect('dashboard');
                } else {
                    // JIKA USER BELUM ADA: Auto Register Akun Baru
                    $username = strtolower(explode('@', $google_data->email)[0]) . rand(100, 999);
                    
                    $userData = array(
                        'name'       => $google_data->name,
                        'username'   => $username,
                        'email'      => $google_data->email,
                        'saldo_awal' => 0, // Default saldo awal
                        'password'   => password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT) // Random password
                    );

                    // Buat akun baru[cite: 2]
                    $user_id = $this->User_model->create($userData); //[cite: 2]

                    $this->session->set_userdata(array(
                        'logged_in' => true,
                        'user_id'   => $user_id,
                        'user_name' => $google_data->name,
                    ));

                    redirect('dashboard');
                }
            }
        }

        $this->session->set_flashdata('error', 'Gagal masuk dengan Google.');
        redirect('login');
    }
}
