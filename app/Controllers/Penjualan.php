<?php

namespace App\Controllers;

use App\Models\DataBarangModel;
use App\Models\PenjualanModel;
use CodeIgniter\Config\Config;
use CodeIgniter\Database\Query;
use Config\Validation;
use Config\Services;

// require __DIR__ . '../../autoload.php';

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\Printer;

class Penjualan extends BaseController
{

    public function index()
    {
        $data = [
            'nofaktur' => $this->buatFaktur()
        ];
        return view('penjualan/input', $data);
    }

    public function buatFaktur()
    {
        $tgl = date('Y-m-d');
        $query = $this->db->query("SELECT MAX(faktur) AS nofaktur FROM penjualan WHERE DATE_FORMAT(tanggal, '%Y-%m-%d') = '$tgl'");

        $hasil = $query->getRowArray();
        $data = $hasil['nofaktur'];

        $lastNoUrut = substr($data, -4);

        $nextNoUrut = intval($lastNoUrut) + 1;

        $fakturPenjualan = 'blps' . date('dmy', strtotime($tgl)) . sprintf('%05s', $nextNoUrut);
        // $msg = ['fakturpenjualan' => $fakturPenjualan];
        // echo json_encode($msg);

        return $fakturPenjualan;
    }

    public function dataDetail()
    {
        $nofaktur = $this->request->getPost('nofaktur');
        $tempTransaksi = $this->db->table('temp_penjualan');

        $queryTampil = $tempTransaksi->select('temp_id, code_barcode, nama_barang, temp_harga_jual, total, jumlah',)->join('barang', 'code_barcode=barcode')->where('temp_faktur', $nofaktur)->orderBy('temp_id', 'asc');

        $data = [
            'datadetail' => $queryTampil->get()
        ];

        $msg = [
            'data' => view('penjualan/view-detail', $data)
        ];

        echo json_encode($msg);
    }

    public function viewDataBarang()
    {
        if ($this->request->isAJAX()) {
            $msg = [
                'viewmodal' => view('penjualan/view-modalcaribarang')
            ];

            echo json_encode($msg);
        }
    }

    public function getListbarang()
    {
        $request = Services::request();
        $model_databarang = new DataBarangModel($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $model_databarang->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->barcode;
                $row[] = $list->nama_barang;
                $row[] = number_format($list->stok, 0, ',', '.');
                $row[] = "Rp." . number_format($list->harga_jual, 0, ',', '.');
                $row[] = "<button type=\"button\" class=\"btn btn-primary\" onclick=\"getItem('" . $list->barcode . "', '" . $list->nama_barang . "')\">pilih</button>";
                $data[] = $row;
            }
            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => $model_databarang->count_all(),
                "recordsFiltered" => $model_databarang->count_filtered(),
                "data" => $data
            ];
            echo json_encode($output);
        }
    }

    public function tempSimpan()
    {
        if ($this->request->isAJAX()) {
            $barcode = $this->request->getPost('barcode');
            $jumlah = $this->request->getPost('jumlah');
            $nofaktur = $this->request->getPost('nofaktur');

            $barang = $this->db->table('barang');

            // if (strlen($nama_barang) > 0) {
            //     $query = $this->db->table('barang')->where('barcode', $barcode)->where('nama_barang', $$nama_barang)->get();
            // } else {

            //     $query = $this->db->table('barang')->like('barcode', $barcode)->orLike('nama_barang', $barcode)->get();
            // }

            // $query = $this->db->table('barang')->like('barcode', $barcode)->get();
            $query = $this->db->table('barang')->like('barcode', $barcode)->orLike('nama_barang', $barcode)->get();

            $getnamabarang = $barang->select('nama_barang')->where('barcode', $barcode)->get();

            $nama_barang = $getnamabarang->getRowArray();



            $totalquery = $query->getNumRows();

            if ($totalquery > 1) {
                $msg = [
                    'totalquery' => 'lebihdarisatu'
                ];
            } else if ($totalquery == 1) {
                $temp_transaksi = $this->db->table('temp_penjualan');
                $row = $query->getRowArray();

                if (intval($row['stok']) == 0 || intval($row['stok']) < $jumlah) {
                    $msg = [
                        'error' => 'stok tidak mencukupi'
                    ];
                } else {
                    $insertData = [
                        'temp_faktur' => $nofaktur,
                        'temp_nama_barang' => $nama_barang,
                        'code_barcode' => $row['barcode'],
                        'temp_harga_beli' => $row['harga_beli'],
                        'temp_harga_jual' => $row['harga_jual'],
                        'jumlah' => $jumlah,
                        'total' => floatval($row['harga_jual']) * $jumlah
                    ];
                    $temp_transaksi->insert($insertData);

                    $msg = ['nais' => 'berhasil'];
                }
            } else {
                $msg = [
                    'error' => 'item tidak di temukan'
                ];
            }
            echo json_encode($msg);
        }
    }

    function totalBayar()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');
            $tempTransaksi = $this->db->table('temp_penjualan');

            $query = $tempTransaksi->select('SUM(total) as totalbayar')->where('temp_faktur', $nofaktur)->get();

            $row = $query->getRowArray();

            $msg = [
                'data' => 'Rp.' . number_format($row['totalbayar'], 0, ',', '.')
            ];

            echo json_encode($msg);
        }
    }

    public function delete()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $tempTransaksi = $this->db->table('temp_penjualan');
            $hapus = $tempTransaksi->delete(['temp_id' => $id]);

            if ($hapus) {
                $msg = [
                    'aa' => 'bb'
                ];

                echo json_encode($msg);
            }
        }
    }

    public function deleteTransaksi()
    {
        if ($this->request->isAJAX()) {
            // $nofaktur = $this->request->getPost('nofaktur');
            $tempTransaksi = $this->db->table('temp_penjualan');

            $hapus = $tempTransaksi->emptyTable();

            if ($hapus) {
                $msg = ['data' => 'berhasil'];
            }

            echo json_encode($msg);
        }
    }

    public function bayar()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');
            $tanggalfaktur = $this->request->getPost('tanggal');
            $tempTransaksi = $this->db->table('temp_penjualan');
            $cekRow = $tempTransaksi->getWhere(['temp_faktur' => $nofaktur]);

            #queri total bayar
            $query = $tempTransaksi->select('SUM(total) as totalbayar')->where('temp_faktur', $nofaktur)->get();
            $row = $query->getRowArray();


            if ($cekRow->getNumRows() > 0) {
                $data = [
                    'nofaktur' => $nofaktur,
                    'totalbayar' => $row['totalbayar']
                ];

                $msg = [
                    'data' => view('penjualan/view-modalbayar', $data)
                ];
            } else {
                $msg = [
                    'error' => 'item kosong'
                ];
            }

            echo json_encode($msg);
        }
    }

    public function saveBayar()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');
            $totalbersih = $this->request->getPost('totalbersih');
            $jumlah_uang = $this->request->getPost('jumlah_uang');
            $sisa_uang = $this->request->getPost('sisa_uang');

            $tempTransaksi = $this->db->table('temp_penjualan');
            $penjualan = $this->db->table('penjualan');
            $detail_penjualan = $this->db->table('detail_penjualan');




            $dataPenjualan = [
                'faktur' => $nofaktur,
                'tanggal' => date('Y-m-d H:i:s'),
                'total_bersih' => $totalbersih,
                'jumlah_uang' => $jumlah_uang,
                'sisa_uang' => $sisa_uang
            ];
            #masukkan ke tabel penjualan
            $penjualan->insert($dataPenjualan);

            $getDataTemp = $tempTransaksi->getWhere(['temp_faktur' => $nofaktur]);

            #masukkan ke table detail penjualan

            $dataDetailPenjualan = [];
            foreach ($getDataTemp->getResultArray() as $row) {
                $dataDetailPenjualan[] = [
                    'detail_faktur' => $row['temp_faktur'],
                    'detail_nama_barang' => $row['temp_nama_barang'],
                    'detail_barcode' => $row['code_barcode'],
                    'detail_harga_beli' => $row['temp_harga_beli'],
                    'detail_harga_jual' => $row['temp_harga_jual'],
                    'detail_jumlah' => $row['jumlah'],
                    'detail_total' => $row['total'],
                    'detail_tanggal' => date('Y-m-d H:i:s'),
                ];
            }
            $detail_penjualan->insertBatch($dataDetailPenjualan);

            #hapus data tabel temp
            $tempTransaksi->emptyTable();

            $msg = [
                'data' => 'berhasil',
                'nofaktur' => $nofaktur,
            ];

            echo json_encode($msg);
        }
    }

    public function cetakStruk()
    {

        function buatBaris1Kolom($kolom1)
        {
            // Mengatur lebar setiap kolom (dalam satuan karakter)
            $lebar_kolom_1 = 35;

            // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
            $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);

            // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
            $kolom1Array = explode("\n", $kolom1);

            // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
            $jmlBarisTerbanyak = count($kolom1Array);

            // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
            $hasilBaris = array();

            // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
            for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

                // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
                $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");

                // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
                $hasilBaris[] = $hasilKolom1;
            }

            // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
            return implode("\n", $hasilBaris) . "\n";
        }

        function buatBaris3Kolom($kolom1, $kolom2, $kolom3)
        {
            // Mengatur lebar setiap kolom (dalam satuan karakter)
            $lebar_kolom_1 = 5;
            $lebar_kolom_2 = 8;
            $lebar_kolom_3 = 12;

            // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
            $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
            $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
            $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);

            // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
            $kolom1Array = explode("\n", $kolom1);
            $kolom2Array = explode("\n", $kolom2);
            $kolom3Array = explode("\n", $kolom3);

            // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
            $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array));

            // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
            $hasilBaris = array();

            // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
            for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

                // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
                $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
                // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
                $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ", STR_PAD_LEFT);

                $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);

                // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
                $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3;
            }

            // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
            return implode("\n", $hasilBaris) . "\n";
        }

        $profile = CapabilityProfile::load("simple");
        $connector = new WindowsPrintConnector("POS-58");
        $printer = new Printer($connector, $profile);


        $nofaktur = $this->request->getPost("nofaktur");
        $tblPenjualan = $this->db->table('penjualan');
        $tblDetailPenjualan = $this->db->table('detail_penjualan');

        $jumlahuang = $this->request->getPost('jumlahuang');
        $sisa_uang = $this->request->getPost('sisa_uang');



        $queryPenjualan = $tblPenjualan->getwhere(['faktur' => $nofaktur]);
        $rowPenjualan = $queryPenjualan->getRowArray();

        $printer->text(buatBaris1Kolom("Budi Luhur PetShop"));
        $printer->text(buatBaris1Kolom("Faktur : $nofaktur"));
        $printer->text(buatBaris1Kolom("Faktur : $rowPenjualan[tanggal]"));

        $printer->text(buatBaris1Kolom("--------------------------------"));

        $queryDetailPenjualan = $tblDetailPenjualan->select('detail_nama_barang,detail_jumlah,detail_harga_jual,detail_total')->join('barang', 'barcode=detail_barcode')->where('detail_faktur', $nofaktur)->get();

        $totalBayar = 0;
        foreach ($queryDetailPenjualan->getResultArray() as $row) {
            $printer->text(buatBaris1Kolom("$row[detail_nama_barang]"));
            $printer->text(buatBaris3Kolom(number_format($row['detail_jumlah'], 0) . ' ' . 'pcs', number_format($row['detail_harga_jual'], 0), number_format($row['detail_total'], 0)));
            $printer->text("\n");
            $totalBayar += $row['detail_total'];
        }

        $printer->text(buatBaris1Kolom("--------------------------------"));
        $printer->text(buatBaris3Kolom("", "total:", "Rp." . number_format($totalBayar, 0)));
        $printer->text(buatBaris3Kolom("", "tunai:", "Rp." . number_format($jumlahuang, 0)));
        $printer->text(buatBaris3Kolom("", "kembali:", "Rp." . number_format($sisa_uang, 0)));
        $printer->text("\n");
        $printer->text(buatBaris1Kolom("terima kasih telah berbelanja"));

        $printer->feed();
        $printer->cut();
        $printer->close();
    }


    // public function tesss()
    // {
    //     $profile = CapabilityProfile::load("simple");
    //     $connector = new WindowsPrintConnector("POS-58");
    //     $printer = new Printer($connector, $profile);
    //     $printer->text("Hello World!\n");
    //     $printer->feed(4);
    //     $printer->cut();
    //     $printer->close();
    // }

    // public function print()
    // {

    //     $nofaktur = $this->request->getPost('nofaktur');
    //     $jumlahuang = $this->request->getPost('jumlahuang');
    //     $sisa_uang = $this->request->getPost('sisa_uang');
    //     $tblPenjualan = $this->db->table('penjualan');
    //     $tblDetailPenjualan = $this->db->table('detail_penjualan');

    //     $queryPenjualan = $tblPenjualan->getwhere(['faktur' => $nofaktur]);
    //     $rowPenjualan = $queryPenjualan->getRowArray();
    //     $queryDetailPenjualan = $tblDetailPenjualan->select('detail_nama_barang,detail_jumlah,detail_harga_jual,detail_total')->join('barang', 'barcode=detail_barcode')->where('detail_faktur', $nofaktur)->get();

    //     $data = [
    //         'nofaktur' => $nofaktur,
    //         'tanggal' => $rowPenjualan['tanggal'],
    //         'detail' => $queryDetailPenjualan,
    //         'jumlahuang' => $jumlahuang,
    //         'sisa_uang' => $sisa_uang
    //     ];

    //     // return view("layout/print", $data);

    //     $msg = [
    //         'data' => view('layout/print', $data)
    //     ];

    //     echo json_encode($msg);
    // }
}
