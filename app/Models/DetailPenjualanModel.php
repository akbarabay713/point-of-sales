<?php

namespace App\Models;

use CodeIgniter\Model;

class detailPenjualanModel extends Model
{
    protected $table = 'detail_penjualan';
    protected $useTimestamps = true;
    protected $allowedFields = ['detail_faktur', 'detail_barcode', 'detail_harga_beli', 'detail_harga_jual', 'detail_jumlah', 'detail_total', 'detail_tanggal'];

    public function getBarang($id = false)
    {
        if ($id === false) {
            return $this->findAll();
        }
        return $this->where(['id' => $id])->first();
    }

    public function filterByTanggal($startdate, $enddate)
    {
        $query = $this->db->query("SELECT * from detail_penjualan WHERE detail_tanggal BETWEEN '$startdate' and '$enddate' ORDER BY detail_tanggal ASC");

        return $query;
    }

    public function filterByBulan($tahun1, $bulanawal, $bulanakhir)
    {
        $query = $this->db->query("SELECT * from detail_penjualan WHERE YEAR(detail_tanggal) = '$tahun1' and MONTH(detail_tanggal) BETWEEN '$bulanawal' and '$bulanakhir' ORDER BY detail_tanggal ASC");

        return $query->getResult();
    }
}
