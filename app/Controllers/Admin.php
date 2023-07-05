<?php

namespace App\Controllers;

// use App\Models\LayananModel;
// use App\Models\BerkasModel;
use App\Models\AuthModel;

class Admin extends BaseController
{
    // protected $LayananModel;
    // protected $BerkasModel;
    // public function __construct()
    // {
    //     $this->LayananModel = new LayananModel();
    //     $this->BerkasModel = new BerkasModel();
    // }

    public function __construct()
    {
        $this->AuthModel = new AuthModel;
    }

    public function index()
    {
        $today = date("Y-m-d");
        $row = $this->db->table('detail_penjualan');
        $barang = $this->db->table('barang');
        $cekRow = $row->getWhere(['detail_tanggal' => $today])->getNumRows();
        $jumlahBarang = $barang->select('SUM(stok) as total_stok')->get()->getRowArray();

        $query = $row->select('SUM(detail_total) as total')->where('detail_tanggal', $today)->get();
        $rowTotal = $query->getRowArray();
        $data = [
            'hari_ini' => $cekRow,
            'total_hari_ini' => $rowTotal['total'],
            'total_barang' => $jumlahBarang['total_stok'],
        ];
        return view('layout/home', $data);
    }

    public function user_management()
    {
        $data = [
            'users' => $this->AuthModel->getUser(),
        ];
        return view('users/users_management', $data);
    }

    public function tambah_data()
    {
        $data = array(
            'username' => htmlspecialchars($this->request->getPost('username')),
            'password' => htmlspecialchars(password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)),
            'level' => $this->request->getPost('role'),
        );
        $this->AuthModel->save_register($data);
        return redirect()->to('/admin/user_management');
    }

    public function delete()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $user = $this->db->table('user');
            $hapus = $user->delete(['id' => $id]);

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
