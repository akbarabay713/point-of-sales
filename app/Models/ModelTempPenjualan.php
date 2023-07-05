<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table = 'temp_penjualan';
    protected $useTimestamps = true;
    protected $allowedFields = ['temp_faktur', 'code_barcode', 'harga_beli', 'harga_jual', 'jumlah', 'total'];

    // public function getPenjualan($id = false)
    // {
    //     if ($id === false) {
    //         return $this->findAll();
    //     }
    //     return $this->where(['id' => $id])->first();
    // }
}
