<?= $this->extend('layout/menu') ?>


<?= $this->section('judul') ?>
<h3><i class="fa fa-fw fa-table"></i> Manajemen Pembelian Barang</h3>
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
    <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#addModal">Tambah Data</button>


    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tabel List Riwayat Pembelian Barang</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $no = 1;
                        foreach ($barang->getResultArray() as $row) :
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $row['nama_barang']; ?></td>
                                <td><?= number_format($row['jumlah'], 0, ',', '.'); ?></td>
                                <td>Rp.<?= number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td><?= $row['tanggal']; ?></td>


                                <td>
                                    <!-- <a class="btn btn-primary my-2" href="/barang/edit/<?= $row['id']; ?>">
                                        <i class="fas fa-edit">edit</i>
                                    </a> -->

                                    <button type="button" class="btn btn-danger" onclick="hapus(<?= $row['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>


                    </tbody>

                    <!-- <tfoot>
                        <tr>
                            <th class="text-center" colspan="5">Total</th>
                            <th>Nama Barang</th>
                            <th>Kode Barang</th>
                            <th>Stock</th>
                            <th>Harga Awal</th>
                            <th>Harga Jual</th>

                            <th>Aksi</th>
                        </tr>
                    </tfoot> -->
                </table>
            </div>
        </div>
    </div>



</div>
<!-- /.container-fluid -->

<!-- Modal Add Product-->
<form action="/barang/pembelianBarang" method="post" enctype="multipart/form-data">
    <?= csrf_field(); ?>
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label>Nama Barang</label>
                        <!-- <select name="nama_barang" class="form-control" required>
                            <option value="">------pilih barang------</option>
                            <?php
                            foreach ($barang->getResultArray() as $row) : ?>
                                <option value="<?= $row['nama_barang']; ?>"><?= $row['nama_barang']; ?></option>
                            <?php endforeach; ?>
                        </select> -->

                        <input type="text" class="form-control" name="nama_barang" required>
                    </div>

                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="number" class="form-control" name="jumlah" required>
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="text" class="form-control" name="harga">
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </div>
        </div>
    </div>
</form>


<!-- End Modal Add Product-->


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
                    url: "<?= site_url('barang/deleteRiwayatPembelian') ?>",
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
                    title: 'Budi Luhur PetShop',
                    exportOptions: {
                        stripHtml: false,
                        columns: [0, 1, 2, 3, 4, 5, 6]
                        //specify which column you want to print

                    }
                },

                {
                    extend: 'pdfHtml5',
                    footer: true,
                    title: 'Budi Luhur PetShop',
                    exportOptions: {
                        stripHtml: false,
                        columns: [0, 1, 2, 3, 4, 5, 6]
                        //specify which column you want to print

                    }
                },
                {
                    extend: 'print',
                    footer: true,
                    title: '<img src="<?= base_url('assets') ?>/dist/img/paw.png" alt="AdminLTE Logo" class="brand-image " width="100px">',
                    exportOptions: {
                        stripHtml: false,
                        columns: [0, 1, 2, 3, 4, 5, 6]
                        //specify which column you want to print

                    }
                },
            ],
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>





<?= $this->endSection(); ?>