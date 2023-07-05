  <!-- DataTables -->
  <link rel="stylesheet" href="<?= base_url('assets'); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url('assets'); ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url('assets'); ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

  <!-- DataTables  & Plugins -->
  <script src="<?= base_url('assets'); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="<?= base_url('assets'); ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?= base_url('assets'); ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="<?= base_url('assets'); ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>



  <!-- Modal -->
  <div class="modal fade" id="modalbarang" tabindex="-1" aria-labelledby="modalbarangLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="modalbarangLabel">List Barang</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <table id="data_barang" class="table table-bordered table-striped dataTable dtr-inline" role="grid">
                      <thead>
                          <tr>
                              <th>No</th>
                              <th>Barcode</th>
                              <th>Nama barang</th>
                              <th>Stok</th>
                              <th>Harga</th>
                              <th>#</th>
                          </tr>
                      </thead>
                  </table>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
          </div>
      </div>
  </div>

  <script type="text/javascript">
      $(document).ready(function() {
          var table = $('#data_barang').DataTable({
              "processing": true,
              "serverSide": true,
              "order": [],
              "ajax": {
                  "url": "<?php echo site_url('penjualan/getListbarang') ?>",
                  "type": "POST"
              },

              "columnDefs": [{
                  "targets": [0],
                  "orderable": false,
              }, ],
          });
      });


      function getItem(barcode, nama_barang) {
          $('#kodebarcode').val(barcode)
          $('#nama_barang').val(nama_barang)

          $('#modalbarang').on('hidden.bs.modal', function(event) {
              $('#kodebarcode').focus()
              cekBarcode()
          })

          $('#modalbarang').modal('hide')
      }
  </script>