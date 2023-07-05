<?= $this->extend('layout/menu') ?>

<?= $this->section('judul') ?>
<h3><i class="fa fa-fw fa-table"></i> Laporan Penjualan</h3>
<?= $this->endSection() ?>
<!-- Custom styles for this page -->

<?= $this->section('isi') ?>


<!-- Begin Page Content -->
<div class="container-fluid">
    <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="alert alert-success" role="alert">
            <?= session()->getFlashdata('pesan'); ?>
        </div>
    <?php endif; ?>

    <?= form_open("laporan/dataDetailBulan", ['class' => 'filter-bulan']) ?>
    <table class="table table-striped">


        <td>
            <select name="bulan" id="bulan" class="form-control">
                <option selected="selected">Pilih Bulan</option>
                <option value='01'> Januari </option>
                <option value='02'> Februari </option>
                <option value='03'> Maret </option>
                <option value='04'> April </option>
                <option value='05'> Mei </option>
                <option value='06'> Juni </option>
                <option value='07'> Juli </option>
                <option value='08'> Agustus </option>
                <option value='09'> September </option>
                <option value='10'> Oktober </option>
                <option value='11'> November </option>
                <option value='12'> Desember </option>
            </select>
        </td>
        <td>
            <select name='tahun' id="tahun" class='form-control'>
                <?php
                $mulai = '2021';
                for ($i = $mulai; $i < $mulai + 20; $i++) {
                    $sel = $i == date('Y') ? ' selected="selected"' : '';
                    echo '<option value="' . $i . '"' . $sel . '>' . $i . '</option>';
                }
                ?>
            </select>
        </td>
        <td>

            <button class="btn btn-primary">
                <i class="fa fa-search"></i> Cari
            </button>

        </td>

    </table>
    <?= form_close() ?>


    <!-- <?= form_open("laporan/dataDetail", ['class' => 'filter-tanggal']) ?>

    <table class="table table-striped">
        <tr>
            <td>
                <label for="">dari tanggal</label>
                <input type="date" class="form-control" name="startdate" id="startdate">
            </td>
            <td>
                <label for="">sampai tanggal</label>
                <input type="date" class="form-control" name="enddate" id="enddate">
            </td>
            <td>

                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-search"></i> Cari
                </button>

            </td>
        </tr>
    </table>
    <?= form_close() ?> -->

    <div class="col-md-12 list-penjualan">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga Awal</th>
                    <th>Harga Jual</th>
                    <th>tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $totalhargaawal = 0;
                $totalhargajual = 0;
                $jumlah = 0;
                foreach ($filter as $row) :

                    $jumlah += $row['detail_jumlah'];
                    $totalhargaawal += $row['detail_harga_beli'] * $row['detail_jumlah'];
                    $totalhargajual += $row['detail_harga_jual'] * $row['detail_jumlah'];
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['detail_nama_barang'] ?></td>
                        <td><?= number_format($row['detail_jumlah']) ?></td>
                        <td>Rp.<?= number_format($row['detail_harga_beli'], 0, ',', '.') ?></td>
                        <td>Rp.<?= number_format($row['detail_harga_jual'], 0, ',', '.') ?></td>
                        <td><?= $row['detail_tanggal'] ?></td>
                        <td>
                            <button type="button" class="btn btn-danger" onclick="hapus(<?= $row['detail_id']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Total Terjual</th>
                    <th><?= $jumlah ?></th>
                    <th>Rp.<?= number_format($totalhargaawal, 0, ',', '.') ?></th>
                    <th>Rp.<?= number_format($totalhargajual, 0, ',', '.') ?></th>
                    </th>
                    <th style="background:#0bb365;color:#fff;">
                        laba : Rp.<?= number_format($totalhargajual - $totalhargaawal, 0, ',', '.') ?>
                    </th>
                    <th>Aksi</th>
                </tr>
            </tfoot>
        </table>
    </div>

</div>

<script>
    $(document).ready(function() {
        // detailPenjualan()

        $('.filter-tanggal').submit(function(e) {
            e.preventDefault()
            detailPenjualan()
        })
        $('.filter-bulan').submit(function(e) {
            e.preventDefault()
            detailPenjualanbulan()
        })
    })



    function detailPenjualan() {
        $.ajax({
            type: "post",
            url: "<?= site_url('Laporan/dataDetail') ?>",
            data: {
                startdate: $('#startdate').val(),
                enddate: $('#enddate').val(),
            },
            dataType: "json",
            beforeSend: function() {
                $('.list-penjualan').html('<i class="fa fa-spin fa-spinner" ></i>')
            },
            success: function(response) {
                if (response.data) {
                    $('.list-penjualan').html(response.data);
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    }

    function detailPenjualanbulan() {
        $.ajax({
            type: "post",
            url: "<?= site_url('Laporan/dataDetailBulan') ?>",
            data: {
                bulan: $('#bulan').val(),
                tahun: $('#tahun').val(),
            },
            dataType: "json",
            beforeSend: function() {
                $('.list-penjualan').html('<i class="fa fa-spin fa-spinner" ></i>')
            },
            success: function(response) {
                if (response.data) {
                    $('.list-penjualan').html(response.data);
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    }
</script>

<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["excel", "pdf", "print"],
            buttons: [

                {
                    extend: 'excelHtml5',
                    footer: true,
                    title: 'Budi Luhur PetShop'
                },

                {
                    extend: 'pdfHtml5',
                    footer: true,
                    title: 'Budi Luhur PetShop'
                },
                {
                    extend: 'print',
                    footer: true,
                    title: '<img src="<?= base_url('assets') ?>/dist/img/paw.png" alt="AdminLTE Logo" class="brand-image " width="100px">'
                },
            ],
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');


        $('#example2').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            'footer': true
        });

        $('#example1').append('<caption style="caption-side: top; font-size:25px; color:black;"><?= $caption; ?></caption>');


    });
</script>

<script>
    function hapus(id) {
        Swal.fire({
            title: 'anda yakin?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url: "<?= site_url('laporan/delete') ?>",
                    data: {
                        id: id,
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.aa == 'bb') {
                            location.reload();
                        }
                    },
                    error: function(xhr, thrownError, error) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                })
            }
        })

    }
</script>


<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url('assets'); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url('assets'); ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url('assets'); ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

<!-- DataTables  & Plugins -->
<script src="<?= base_url('assets'); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets'); ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('assets'); ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('assets'); ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url('assets'); ?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('assets'); ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url('assets'); ?>/plugins/jszip/jszip.min.js"></script>
<script src="<?= base_url('assets'); ?>/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= base_url('assets'); ?>/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= base_url('assets'); ?>/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url('assets'); ?>/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url('assets'); ?>/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>


<?= $this->endSection(); ?>