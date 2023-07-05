<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" width='100'>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Kode Barang</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>action</th>
        </tr>
    </thead>
    <tbody>

        <?php
        $no = 1;
        foreach ($datadetail->getResultArray() as $row) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['nama_barang']; ?></td>
                <td><?= $row['code_barcode']; ?></td>
                <td><?= number_format($row['jumlah']); ?></td>
                <td>Rp.<?= number_format($row['total'], 0, ',', '.'); ?></td>
                <td>
                    <button type="button" class="btn btn-danger" onclick="hapus(<?= $row['temp_id']; ?>)">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>


    </tbody>
</table>

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
                    url: "<?= site_url('penjualan/delete') ?>",
                    data: {
                        id: id,
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.aa == 'bb') {
                            dataDetailPenjualan()
                            reset()
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