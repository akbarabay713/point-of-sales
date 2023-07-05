<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
    protected $table = 'user';
    protected $useTimestamps = true;
    protected $allowedFields = ['username', 'password', 'level'];

    public function save_register($data)
    {
        $this->db->table('user')->insert($data);
    }

    public function getUser($id = false)
    {
        if ($id === false) {
            return $this->findAll();
        }
        return $this->where(['id' => $id])->first();
    }

    // public function login($email, $password)
    // {
    //     return $this->db->table('user')->where([
    //         'email' => $email,
    //         'password' => $password,
    //     ])->get()->getRowArray();
    // }
}
