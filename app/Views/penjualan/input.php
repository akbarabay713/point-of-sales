<?= $this->extend('layout/menu') ?>

<?= $this->section('judul') ?>
<h3><i class="fa fa-fw fa-table"></i> Manajemen Kasir</h3>
<?= $this->endSection() ?>
<!-- Custom styles for this page -->

<?= $this->section('isi') ?>
<script src="<?= base_url('assets/plugins/autoNumeric.js') ?>"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card card-default color-palette-box">

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nofaktur">Faktur</label>
                                <input type="text" class="form-control form-control-sm" style="font-weight:bold;" name="nofaktur" id="nofaktur" readonly value="<?= $nofaktur; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tanggal">Tanggal</label>
                                <input type="date" class="form-control form-control-sm" name="tanggal" id="tanggal" readonly value="<?= date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <!-- <div class="col-md-3">
                            <div class="form-group">
                                <label for="napel">Pelanggan</label>
                                <div class="input-group mb-3">
                                    <input type="text" value="-" class="form-control form-control-sm" name="napel" id="napel" readonly>
                                    <input type="hidden" name="kopel" id="kopel" value="0">
                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-primary" type="button">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tanggal">Aksi</label>
                                <div class="input-group">
                                    <button class="btn btn-danger btn-sm" type="button" id="btnHapusTransaksi">
                                        <i class="fa fa-trash-alt"></i>
                                    </button>&nbsp;
                                    <button class="btn btn-success" type="button" id="btnSimpanTransaksi">
                                        <i class="fa fa-save"></i>
                                    </button>&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="kodebarcode">Kode Barang</label>
                                <input type="text" class="form-control form-control-sm" name="kodebarcode" id="kodebarcode" autofocus>
                            </div>
                        </div>
                        <!-- <div class="col-md-3">
                            <div class="form-group">
                                <label for="nama_barang">Nama Barang</label>
                                <input type="text" class="form-control form-control-sm" name="nama_barang" id="nama_barang" autofocus readonly>
                            </div>
                        </div> -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="jumlah">Jumlah</label>
                                <input type="number" class="form-control form-control-sm" name="jumlah" id="jumlah" value="1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="jml">Total Bayar</label>
                                <input type="text" class="form-control form-control-lg" name="totalbayar" id="totalbayar" style="text-align: right; color:red; font-weight : bold; font-size:30pt;" value="0" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 dataDetailPenjualan">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="viewmodal" style="display: none;"></div>
<div class="viewmodalbayar" style="display: none;"></div>

<script>
    $(document).ready(function() {

        dataDetailPenjualan()
        totalBayar()



        $('#kodebarcode').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault()
                cekBarcode()
            }
        })
    })

    $('#btnHapusTransaksi').click(function(e) {
        e.preventDefault()

        Swal.fire({
            title: 'Hapus Transaksi?',
            // text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url: "<?= site_url('penjualan/deleteTransaksi') ?>",
                    data: {
                        nofaktur: $('#nofaktur').val()
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.data == 'berhasil') {
                            window.location.reload()
                        }
                    },
                    error: function(xhr, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                })
            }
        })
    })

    $('#btnSimpanTransaksi').click(function(e) {
        e.preventDefault()
        bayar()
    })

    function bayar() {
        let nofaktur = $('#nofaktur').val()
        $.ajax({
            type: "post",
            url: "<?= site_url('penjualan/bayar') ?>",
            data: {
                nofaktur: nofaktur,
                tanggal: $('#tanggal').val()
            },
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodalbayar').html(response.data).show()
                    $('#modalbayar').modal('show');
                }

                if (response.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: response.error,

                    })
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    }


    function dataDetailPenjualan() {
        $.ajax({
            type: "post",
            url: "<?= site_url('penjualan/dataDetail') ?>",
            data: {
                nofaktur: $('#nofaktur').val()
            },
            dataType: "json",
            beforeSend: function() {
                $('.dataDetailPenjualan').html('<i class="fa fa-spin fa-spinner" ></i>')
            },
            success: function(response) {
                if (response.data) {
                    $('.dataDetailPenjualan').html(response.data);
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    }

    function cekBarcode() {
        let barcode = $('#kodebarcode').val()

        if (barcode.length == 0) {
            $.ajax({
                type: "post",
                url: "<?= site_url('penjualan/viewDataBarang') ?>",
                dataType: "json",
                success: function(response) {
                    $('.viewmodal').html(response.viewmodal).show()

                    $('#modalbarang').modal('show');
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            })
        } else {
            $.ajax({
                type: "post",
                url: "<?= site_url('penjualan/tempSimpan') ?>",
                data: {
                    barcode: barcode,
                    nama_barang: $('#nama_barang').val(),
                    jumlah: $('#jumlah').val(),
                    nofaktur: $('#nofaktur').val(),
                },
                dataType: "json",
                success: function(response) {
                    if (response.nais == 'berhasil') {
                        dataDetailPenjualan()
                        reset()
                    }
                    if (response.error) {

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: response.error,

                        })
                        dataDetailPenjualan()
                        reset()
                    }
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            })
        }
    }

    function totalBayar() {
        $.ajax({
            type: "post",
            url: "<?= site_url('penjualan/totalBayar') ?>",
            data: {
                nofaktur: $('#nofaktur').val(),
            },
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('#totalbayar').val(response.data)
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    }

    function reset() {
        $('#kodebarcode').val('')
        $('#kodebarcode').focus()
        $('#jumlah').val('1')
        $('#nama_barang').val('')
        totalBayar()
    }
</script>



<?= $this->endSection(); ?>