<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">

    <script src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>

    <link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
    <script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
    <title>Receipt example</title>

    <style>
        * {
            font-size: 12px;
            font-family: 'Times New Roman';
        }

        td,
        th,
        tr,
        table {
            border-top: 1px solid black;
            border-collapse: collapse;
            text-align: center;
            margin: auto;
        }

        td.description,
        th.description {
            width: 5px;
            max-width: 50px;
        }

        td.quantity,
        th.quantity {
            width: 20px;
            max-width: 20px;
            word-break: break-all;
        }

        td.price,
        th.price {
            width: 60px;
            max-width: 60px;
            word-break: break-all;
        }

        .centered {
            text-align: center;
            align-content: center;
        }

        .ticket {
            width: 250px;
            max-width: 250px;
            margin: auto;
        }

        img {
            max-width: inherit;
            width: inherit;
        }

        @media print {

            .hidden-print,
            .hidden-print * {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="ticket">
        <!-- <img src="./logo.png" alt="Logo"> -->
        <p class="centered">Budi Luhur PetShop
            <br>Faktur : <?= $nofaktur ?>
            <br>tanggal: <?= $tanggal ?>
        </p>
        <table>
            <thead>
                <tr>
                    <th class="description">Barang</th>
                    <th class="quantity">QTY</th>
                    <th class="price">Harga</th>
                    <th class="price">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                <!-- foreach ($queryDetailPenjualan->getResultArray() as $row) {
            $printer->text(buatBaris1Kolom("$row[detail_nama_barang]"));
            $printer->text(buatBaris3Kolom(number_format($row['detail_jumlah'], 0) . ' ' . 'pcs', number_format($row['detail_harga_jual'], 0), number_format($row['detail_total'], 0)));

            $totalBayar += $row['detail_total']; -->
                <?php
                $no = 1;
                $totalBayar = 0;
                foreach ($detail->getResultArray() as $row) :
                    $totalBayar += $row['detail_total'];
                ?>
                    <tr>
                        <!-- <td><?= $no++; ?></td> -->
                        <td class="description"><?= $row['detail_nama_barang']; ?></td>
                        <td class="quantity"><?= number_format($row['detail_jumlah']); ?></td>
                        <td class="price">Rp.<?= number_format($row['detail_harga_jual'], 0, ',', '.'); ?></td>
                        <td class="price">Rp.<?= number_format($row['detail_total'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>

                <tr>
                    <td class="quantity"></td>
                    <td class="description">TOTAL</td>
                    <td class="price"></td>
                    <td class="price">Rp.<?= number_format($totalBayar, 0, ',', '.'); ?></td>
                </tr>

                <br><br>
                <tr>
                    <td class="quantity"></td>
                    <td class="description">Uang Tunai</td>
                    <td class="price"></td>
                    <td class="price">Rp.<?= number_format($jumlahuang, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td class="quantity"></td>
                    <td class="description">Kembalian</td>
                    <td class="price"></td>
                    <td class="price">Rp.<?= number_format($sisa_uang, 0, ',', '.'); ?></td>
                </tr>

            </tbody>
        </table>
        <p class="centered">terima kasih sudah berbelanja
            <br>di toko kami
        </p>
    </div>
    <!-- <button id="btnPrint" class="hidden-print">Print</button> -->
    <script src="<?= base_url('assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url('assets') ?>/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?= base_url('assets') ?>/dist/js/demo.js"></script>


</body>

</html>