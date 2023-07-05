<?php

namespace App\Controllers;

use App\Models\AuthModel;
use CodeIgniter\Config\Config;
use Config\Validation;

class Auth extends BaseController
{

    public function __construct()
    {
        $this->AuthModel = new AuthModel;
    }

    public function index()
    {
        session();
        $data = [
            'validation' => \Config\Services::validation()
        ];
        return view('auth/login', $data);
    }

    public function login()
    {
        if (!$this->validate([
            'username' => 'required',
            'password' => 'required',
        ])) {
            $validation = \Config\Services::validation();
            return redirect()->to('/auth/index')->withInput()->with('validation', $validation);
        }

        $username = htmlspecialchars($this->request->getVar('username'));
        $password = htmlspecialchars($this->request->getVar('password'));


        $users = new AuthModel;
        $user = $users->where(['username' => $username])->first();
        if ($user) {
            if (password_verify($password, $user[('password')])) {
                $data = [
                    // 'log' => true,
                    'username' => $user['username'],
                    'level' => $user['level'],
                ];
                session()->set($data);
                if ($user['level'] == 1) {
                    return redirect()->to('/admin');
                }
                // else if ($user["level"] == 2) {
                //     return redirect()->to('kasir');
                // }
            } else {
                return redirect()->to('/auth');
            }
        } else {
            return redirect()->to('/auth');
        }
    }

    function logout()
    {
        session()->destroy();
        return redirect()->to('auth');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function save_register()
    {
        $data = array(
            'username' => htmlspecialchars($this->request->getPost('username')),
            'password' => htmlspecialchars(password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)),
            'level' => 1,
        );
        $this->AuthModel->save_register($data);
        return redirect()->to('/auth');
    }

    public function user_management()
    {
        $data = [
            'users' => $this->AuthModel->getUser(),
        ];
        return view('users/users_management', $data);
    }



    // public function registrasion()
    // {
    // if (!$this->validate([
    //     'username' => [
    //         'rules' => 'required|min_length[4]|max_length[20]|is_unique[users.username]',
    //         'errors' => [
    //             'required' => '{field} Harus diisi',
    //             'min_length' => '{field} Minimal 4 Karakter',
    //             'max_length' => '{field} Maksimal 20 Karakter',
    //             'is_unique' => 'Username sudah digunakan sebelumnya'
    //         ]
    //     ],
    //     'password' => [
    //         'rules' => 'required|min_length[4]|max_length[50]',
    //         'errors' => [
    //             'required' => '{field} Harus diisi',
    //             'min_length' => '{field} Minimal 4 Karakter',
    //             'max_length' => '{field} Maksimal 50 Karakter',
    //         ]
    //     ],
    //     'password_conf' => [
    //         'rules' => 'matches[password]',
    //         'errors' => [
    //             'matches' => 'Konfirmasi Password tidak sesuai dengan password',
    //         ]
    //     ],
    //     'name' => [
    //         'rules' => 'required|min_length[4]|max_length[100]',
    //         'errors' => [
    //             'required' => '{field} Harus diisi',
    //             'min_length' => '{field} Minimal 4 Karakter',
    //             'max_length' => '{field} Maksimal 100 Karakter',
    //         ]
    //     ],
    // ])) {
    //     session()->setFlashdata('error', $this->validator->listErrors());
    //     return redirect()->back()->withInput();
    // }
    //     $users = new AuthModel();
    //     $users->insert([
    //         'name' => $this->request->getVar('name'),
    //         'email' => $this->request->getVar('email'),
    //         'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
    //         'image' => 'image.jpg',
    //         'role_id' => 2,
    //         'is_active' => 1,
    //     ]);
    //     return redirect()->to('/login');
    // }

    // public function cek_login()
    // {
    //     $email = $$this->request->getPost('email');
    //     $password = $$this->request->getPost('password');
    //     $cek = $this->AuthModel->login($email,$password);
    //     if ($cek) 
    //     {
    //         session()->set('log',true);
    //         session()->set('nama' $cek['nama']);
    //         session()->set('email' $cek['email']);
    //         session()->set('image' $cek['image']);
    //         session()->set('role' $cek['role']);

    //         return redirect()->to(base_url('home'))
    //     }else{
    //         return redirect()->to(base_url('auth/login'))

    //     }
    // }
}
