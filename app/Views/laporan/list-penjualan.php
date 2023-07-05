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


<!-- End Modal Add Product-->