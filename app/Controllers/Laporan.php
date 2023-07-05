<?php

namespace App\Controllers;

use App\Models\DetailPenjualanModel;
use CodeIgniter\Config\Config;
use Config\Validation;

class Laporan extends BaseController
{
    public function __construct()
    {
        $this->DetailPenjualanModel = new DetailPenjualanModel;
    }
    public function index()
    {
        $query = $this->db->query("SELECT * from detail_penjualan");
        $row = $query->getResultArray();
        $data = [
            'filter' => $row,
            'caption' => "Laporan penjualan"
        ];
        return view('laporan/index', $data);
    }

    public function dataDetail()
    {
        $startdate = $this->request->getPost('startdate');
        $enddate = $this->request->getPost('enddate');


        $query = $this->db->query("SELECT * from detail_penjualan WHERE detail_tanggal BETWEEN '$startdate' and '$enddate' ORDER BY detail_tanggal ASC");

        $data = [
            'filter' => $query->getResultArray(),
            'caption' => "Laporan penjualan dari tanggal " . $startdate . " sampai tanggal " . $enddate

        ];

        $msg = [
            'data' => view('laporan/list-penjualan', $data)
        ];


        echo json_encode($msg);
    }
    public function dataDetailBulan()
    {
        $bulan = $this->request->getPost('bulan');
        $tahun = $this->request->getPost('tahun');

        $query = $this->db->query("SELECT * from detail_penjualan WHERE MONTH(detail_tanggal) ='$bulan' and YEAR(detail_tanggal) = '$tahun'");

        $caption = $bulan;

        switch ($caption) {
            case "01":
                $caption = "Januari";
                break;
            case "02":
                $caption = "Februari";
                break;
            case "03":
                $caption = "Maret";
                break;
            case "04":
                $caption = "April";
                break;
            case "05":
                $caption = "Mei";
                break;
            case "06":
                $caption = "Juni";
                break;
            case "07":
                $caption = "Juli";
                break;
            case "08":
                $caption = "Agustus";
                break;
            case "09":
                $caption = "September";
                break;
            case "10":
                $caption = "Oktober";
                break;
            case "11":
                $caption = "November";
                break;
            case "12":
                $caption = "Desember";
                break;

            default:
                $caption = "pilih bulan";;
        }

        $data = [
            'filter' => $query->getResultArray(),
            'caption' => "Laporan penjualan bulan " . $caption . " tahun " . $tahun
        ];

        $msg = [
            'data' => view('laporan/list-penjualan', $data)
        ];

        echo json_encode($msg);
    }

    public function delete()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $detail_penjualan = $this->db->table('detail_penjualan');
            $hapus = $detail_penjualan->delete(['detail_id' => $id]);

            if ($hapus) {
                $msg = [
                    'aa' => 'bb'
                ];
                session()->setFlashData('pesan', 'Data berhasil dihapus');

                echo json_encode($msg);
            }
        }
    }
}
