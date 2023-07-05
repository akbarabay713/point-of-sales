<!-- Modal -->
<div class="modal fade" id="modalbayar" tabindex="-1" aria-labelledby="modalbayarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalbayarLabel">Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?= form_open("penjualan/saveBayar", ['class' => 'form-bayar']) ?>
            <div class="modal-body">

                <input type="hidden" name="nofaktur" value="<?= $nofaktur ?>">
                <input type="hidden" name="total_kotor" value="<?= $totalbayar ?>">
                <div class="form-group">
                    <label for="totalbersih">Total Bayar</label>
                    <input type="text" name="totalbersih" id="totalbersih" class="form-control from-control-lg" value="<?= $totalbayar ?>" readonly style="text-align: right; color:red; font-weight : bold; font-size:30pt;">
                </div>
                <div class="form-group">
                    <label for="totalbersih">Jumlah Uang</label>
                    <input type="text" name="jumlah_uang" id="jumlah_uang" class="form-control from-control-lg" style="text-align: right; color:black; font-weight : bold; font-size:30pt;" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="totalbersih">Sisa Uang</label>
                    <input type="text" name="sisa_uang" id="sisa_uang" class="form-control from-control-lg" style="text-align: right; color:black; font-weight : bold; font-size:30pt;" readonly>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-simpan">Bayar</button>
            </div>
            </form>
            <?= form_close() ?>

            <div id="printArea"></div>


        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/recta/dist/recta.js"></script>
<script type="text/javascript">
    var printer = new Recta('1739845895', '1811')

    function onClick(faktur, jumlahuang, sisaUang) {
        printer.open().then(function() {
            printer.align('left')
                .text(faktur)
                .bold(true)
                .text('This is bold text')
                .bold(false)
                .underline(true)
                .text('This is underline text')
                .underline(false)
                .barcode('UPC-A', '123456789012')
                .cut()
                .print()
        })
    }
</script>
<script>
    $(document).ready(function() {
        $('#totalbersih').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '2'
        });
        $('#jumlah_uang').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '2'
        });
        $('#sisa_uang').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '2'
        });

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        $('#jumlah_uang').keyup(function(e) {
            sisaUang()
        })

        $('.form-bayar').submit(function(e) {

            e.preventDefault()

            let jumlahuang = ($("#jumlah_uang").val() == "") ? 0 : $("#jumlah_uang").autoNumeric('get')
            let sisa_uang = ($('#sisa_uang').val() == "") ? 0 : $("#sisa_uang").autoNumeric('get')

            if (parseFloat(jumlahuang) == '' || parseFloat(jumlahuang) == 0) {
                Toast.fire({
                    icon: 'warning',
                    title: 'jumlah uang belum di input'
                })
            } else if (parseFloat(sisa_uang) < 0) {
                Toast.fire({
                    icon: 'warning',
                    title: 'uang belum mencukupi'
                })
            } else {
                $.ajax({
                    type: "post",
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: "json",
                    beforeSend: function() {
                        $('.btn-simpan').prop('disabled', true)
                        $('.btn-simpan').html('<i class="fa fa-spin fa-spinner" ></i>')
                    },
                    complete: function() {
                        $('.btn-simpan').prop('disabled', false)
                        $('.btn-simpan').html('berhasil')
                    },
                    success: function(response) {
                        if (response.data == 'berhasil') {
                            // alert(response.nofaktur)
                            var faktur = response.nofaktur
                            var det = (response.detail)
                            let total = 0;

                            // onClick(faktur, jumlahuang, sisaUang);
                            printer.open().then(function() {
                                printer.align('left')
                                    .text('Budi Luhur PetShop')
                                    .text(`Faktur : ${faktur}`)
                                    .text(`Tanggal : ${response.tgl}`)
                                    .text('-------------------------------')
                                // .cut()
                                // .print()
                                //   printer.align('left')

                                det.map(d => {
                                    total += parseInt(d.detail_total);
                                    return (

                                        printer.align('left')
                                        .text(d.detail_nama_barang)
                                        .text(`${d.detail_jumlah} pcs Rp.${d.detail_harga_jual} Rp.${d.detail_total}`)


                                    )
                                })

                                printer.align('left')
                                    .text('-------------------------------')
                                    .text(`Total   : Rp.${total}`)
                                    .text(`Tunai   : Rp.${jumlahuang}`)
                                    .text(`Kembali : Rp.${sisa_uang}`)
                                    .text('-------------------------------')
                                    .text('Terima kasih telah berbelanja')
                                    .cut()
                                    .print()
                            })
                            window.location.reload()

                            // $.ajax({
                            //     type: "post",
                            //     url: "<?= site_url('penjualan/cetakStruk') ?>",
                            //     data: {
                            //         nofaktur: faktur,
                            //         jumlahuang: jumlahuang,
                            //         sisa_uang: sisa_uang
                            //     },
                            //     // dataType: "json",
                            //     success: function(response) {
                            //         // if (response.data) {
                            //         // $('#printArea').html(response.data);
                            //         // printDiv()
                            //         window.location.reload()
                            //         // }
                            //         // alert(faktur)

                            //         // $('.printArea').html(response.data);


                            //         // var printWindow = window.open("http://localhost:8080/Penjualan/print");
                            //         // printWindow.print();
                            //         // window.open("http://localhost:8080/Penjualan/print")


                            //     },
                            //     error: function(xhr, thrownError) {
                            //         console.log(thrownError)
                            //         console.log(xhr)
                            //         alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                            //     }
                            // })
                        } else {
                            window.location.reload()
                        }
                    },
                    error: function(xhr, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                })
            }

            return false
        })


    })

    function printDiv() {
        var printContents = document.getElementById('printArea').innerHTML
        var originalContents = document.body.innerHTML
        document.body.innerHTML = printContents
        window.print()
        document.body.innerHTML = originalContents
    }


    function sisaUang() {
        let totalbayar = $("#totalbersih").autoNumeric('get')
        let jumlahuang = ($("#jumlah_uang").val() == '') ? 0 : $("#jumlah_uang").autoNumeric('get')

        sisa_uang = parseFloat(jumlahuang) - parseFloat(totalbayar)
        $("#sisa_uang").val(sisa_uang)

        let sisa = $("#sisa_uang").val()

        $('#sisa_uang').autoNumeric('set', sisa)
    }
</script>