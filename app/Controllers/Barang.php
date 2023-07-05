<?php

namespace App\Controllers;

use App\Models\BarangModel;
use CodeIgniter\Config\Config;
use Config\Validation;

class Barang extends BaseController
{
    public function __construct()
    {
        $this->BarangModel = new BarangModel;
    }
    public function index()
    {
        $data = [
            'barang' => $this->BarangModel->getBarang(),
        ];
        return view('barang/list-barang', $data);
    }


    public function save()
    {
        $this->BarangModel->save([
            'nama_barang' => htmlspecialchars($this->request->getVar('nama_barang')),
            'barcode' => $this->request->getVar('barcode'),
            'stok' => htmlspecialchars($this->request->getVar('stok')),
            'harga_beli' => htmlspecialchars($this->request->getVar('harga_beli')),
            'harga_jual' => htmlspecialchars($this->request->getVar('harga_jual')),
        ]);
        session()->setFlashData('pesan', 'Data berhasil Ditambahkan');
        return redirect()->to('/barang');
    }

    // public function delete($id)
    // {

    //     $this->BarangModel->delete($id);
    //     session()->setFlashData('pesan', 'Data berhasil dihapus');
    //     return redirect()->to('/barang');
    // }

    public function delete()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $tempTransaksi = $this->db->table('barang');
            $hapus = $tempTransaksi->delete(['id' => $id]);

            if ($hapus) {
                $msg = [
                    'aa' => 'bb'
                ];
                session()->setFlashData('pesan', 'Data berhasil dihapus');

                echo json_encode($msg);
            }
        }
    }

    public function edit($id)
    {
        $data = [
            'barang' => $this->BarangModel->getBarang($id),
        ];

        return view('barang/barang-edit', $data);
    }

    public function update($id)
    {
        $this->BarangModel->save([
            'id' => $id,
            'nama_barang' => htmlspecialchars($this->request->getVar('nama_barang')),
            'barcode' => $this->request->getVar('barcode'),
            'stok' => htmlspecialchars($this->request->getVar('stok')),
            'harga_beli' => htmlspecialchars($this->request->getVar('harga_beli')),
            'harga_jual' => htmlspecialchars($this->request->getVar('harga_jual')),

        ]);
        session()->setFlashData('pesan', 'Data berhasil Diupdate');
        return redirect()->to('/barang');
    }

    public function stok()
    {
        $barang = $this->db->table('pembelian_barang');
        $query = $barang->get();
        $data = [
            'barang' => $query,
        ];
        return view('barang/pembelian-barang', $data);
    }

    public function pembelianBarang()
    {
        $nama_barang = $this->request->getPost('nama_barang');
        $jumlah = $this->request->getPost('jumlah');
        $harga = $this->request->getPost('harga');

        $barang = $this->db->table('pembelian_barang');
        $barangLama = $this->db->table('barang');
        $query = $this->db->table('barang')->like('nama_barang', $nama_barang)->get();

        $totalquery = $query->getNumRows();

        if ($totalquery == 1) {
            $row = $query->getRowArray();
            $stok_lama = $row['stok'];

            $insertData = [
                // 'id' => $row['id'],
                'nama_barang' => $nama_barang,
                'jumlah' => $jumlah,
                'harga' => $harga,
                'tanggal' => date('Y-m-d'),
            ];

            $insertDataLama = [
                'id' => $row['id'],
                'nama_barang' => $nama_barang,
                'barcode' => $row['barcode'],
                'stok' => $jumlah + $stok_lama,
                'harga_beli' => $harga,
                'harga_jual' =>  $row['harga_jual'],
                // 'tanggal' => date('Y-m-d'),
            ];



            $barangLama->delete(['nama_barang' => $nama_barang]);

            $barang->insert($insertData);
            $barangLama->insert($insertDataLama);
        } else {
            $data_pembelian = [
                'nama_barang' => $nama_barang,
                'jumlah' => $jumlah,
                'harga' => $harga,
                'tanggal' => date('Y-m-d'),
            ];

            $row = $query->getRowArray();
            // $stok_lama = $row['stok'];

            $insertDataBaru = [
                // 'id' => $row['id'],
                'nama_barang' => $nama_barang,
                'barcode' => 0,
                'stok' => $jumlah,
                'harga_beli' => $harga,
                'harga_jual' => 0,
                // 'tanggal' => date('Y-m-d'),
            ];

            $barang->insert($data_pembelian);
            $barangLama->insert($insertDataBaru);
        }



        session()->setFlashData('pesan', 'Data berhasil Diupdate');
        return redirect()->to('/barang/stok');
    }

    public function deleteRiwayatPembelian()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $tempData = $this->db->table('pembelian_barang');
            $hapus = $tempData->delete(['id' => $id]);

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
