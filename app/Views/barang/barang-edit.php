<?= $this->extend('layout/menu') ?>

<?= $this->section('judul') ?>
<h3><i class="fa fa-fw fa-table"></i> Manajemen Data Produk</h3>
<?= $this->endSection() ?>

<?= $this->section('isi') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="alert alert-success" role="alert">
                <i class="fas fa-user"></i> Form Edit Data
            </div>
            <form action="/barang/update/<?= $barang['id']; ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" class="form-control" name="nama_barang" value="<?= $barang['nama_barang']; ?>" required>
                </div>


                <!-- <div class="form-group">
                    <label>Stock</label> -->
                <input type="hidden" class="form-control" name="stok" value="<?= $barang['stok']; ?>" required>
                <!-- </div> -->
                <!-- <div class="form-group">
                    <label>Harga Beli</label> -->
                <input type="hidden" class="form-control" name="harga_beli" value="<?= $barang['harga_beli']; ?>">
                <!-- </div> -->

                <div class="form-group">
                    <label>Harga Jual</label>
                    <input type="text" class="form-control" name="harga_jual" value="<?= $barang['harga_jual']; ?>">
                </div>

                <div class="form-group">
                    <label>Kode Barcode</label>
                    <input type="text" class="form-control" name="barcode" value="<?= $barang['barcode']; ?>" required>
                </div>

                <div class="form-group row">
                    <div class="col-sm">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>


        </div>
    </div>
</div>

<?= $this->endSection(); ?>