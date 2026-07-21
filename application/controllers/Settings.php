<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[AllowDynamicProperties]
class Settings extends MY_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $user    = $this->User_model->get($user_id);

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Nama Lengkap', 'required|trim');
            
            // Validasi unik username jika diubah
            if ($this->input->post('username') != $user->username) {
                $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[users.username]');
            }

            // Validasi password jika field new_password diisi
            if (!empty($this->input->post('new_password'))) {
                // Menambahkan rule callback _check_password_strength untuk standar keamanan
                $this->form_validation->set_rules('new_password', 'Password Baru', 'required|min_length[8]|callback__check_password_strength');
                $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required|matches[new_password]', array(
                    'matches' => 'Konfirmasi password tidak cocok dengan password baru.'
                ));
            }

            if ($this->form_validation->run() === TRUE) {
                $updateData = [
                    'name'     => $this->input->post('name', true),
                    'username' => $this->input->post('username', true),
                ];

                // Update Password jika diisi
                if (!empty($this->input->post('new_password'))) {
                    $updateData['password'] = $this->input->post('new_password');
                }

                // Upload Foto Profil
                if (!empty($_FILES['photo']['name'])) {
                    $config['upload_path']   = './uploads/profile/';
                    $config['allowed_types'] = 'jpg|jpeg|png|webp';
                    $config['max_size']      = 2048; // 2MB
                    $config['file_name']     = 'user_' . $user_id . '_' . time();

                    if (!is_dir($config['upload_path'])) {
                        mkdir($config['upload_path'], 0777, true);
                    }

                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('photo')) {
                        // Hapus foto lama jika ada
                        if ($user->photo && file_exists('./uploads/profile/' . $user->photo)) {
                            unlink('./uploads/profile/' . $user->photo);
                        }
                        $uploadData = $this->upload->data();
                        $updateData['photo'] = $uploadData['file_name'];
                    } else {
                        $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                        redirect('settings');
                        return;
                    }
                }

                $this->User_model->update($user_id, $updateData);
                $this->session->set_userdata('user_name', $updateData['name']);
                $this->session->set_flashdata('success', 'Profil berhasil diperbarui!');
                redirect('settings');
                return;
            }
        }

        $data['title'] = 'Settings Profil';
        $data['user']  = $user;
        
        $this->load->view('templates/header', $data); // sesuaikan template milikmu
        $this->load->view('settings/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Callback function untuk memeriksa kekuatan password.
     * Syarat: Minimal 1 huruf besar, 1 huruf kecil, dan 1 angka.
     */
    public function _check_password_strength($password)
    {
        // Cek huruf besar (A-Z)
        if (!preg_match('/[A-Z]/', $password)) {
            $this->form_validation->set_message('_check_password_strength', 'Field {field} harus mengandung minimal 1 huruf besar (A-Z).');
            return FALSE;
        }

        // Cek huruf kecil (a-z)
        if (!preg_match('/[a-z]/', $password)) {
            $this->form_validation->set_message('_check_password_strength', 'Field {field} harus mengandung minimal 1 huruf kecil (a-z).');
            return FALSE;
        }

        // Cek angka (0-9)
        if (!preg_match('/[0-9]/', $password)) {
            $this->form_validation->set_message('_check_password_strength', 'Field {field} harus mengandung minimal 1 angka (0-9).');
            return FALSE;
        }

        return TRUE;
    }
}