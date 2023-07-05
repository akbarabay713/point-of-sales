<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table = 'penjualan';
    protected $useTimestamps = true;
    protected $allowedFields = ['faktur', 'tanggal', 'total_kotor', 'total_bersih'];

    // public function getPenjualan($id = false)
    // {
    //     if ($id === false) {
    //         return $this->findAll();
    //     }
    //     return $this->where(['id' => $id])->first();
    // }
}
