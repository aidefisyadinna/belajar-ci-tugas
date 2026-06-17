<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashData('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <?= session()->getFlashData('success') ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (session()->getFlashData('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <?= session()->getFlashData('error') ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row g-4">
  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-body p-4">
        <h5 class="card-title mb-3">Data Pengiriman</h5>
        <?= form_open('buy', 'class="row g-3"') ?>
        <?= form_hidden('username', session()->get('username')) ?>
        <?= form_input(['type' => 'hidden', 'name' => 'total_harga', 'id' => 'total_harga', 'value' => '']) ?>

        <div class="col-12">
          <label for="nama" class="form-label fw-semibold">Nama</label>
          <input type="text" class="form-control" id="nama" value="<?= esc(session()->get('username')) ?>" readonly>
        </div>

        <div class="col-12">
          <label for="alamat" class="form-label fw-semibold">Alamat</label>
          <textarea class="form-control" id="alamat" name="alamat" rows="2" placeholder="Masukkan alamat lengkap"></textarea>
        </div>

        <div class="col-12">
          <label for="kelurahan" class="form-label fw-semibold">Kelurahan</label>
          <select class="form-control" id="kelurahan" name="kelurahan" required></select>
        </div>

        <div class="col-12">
          <label for="layanan" class="form-label fw-semibold">Layanan</label>
          <select class="form-control" id="layanan" name="layanan" required></select>
        </div>

        <div class="col-12">
          <label for="ongkir" class="form-label fw-semibold">Ongkos Kirim</label>
          <input type="text" class="form-control" id="ongkir" name="ongkir" readonly>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-body p-4">
        <h5 class="card-title mb-3">Ringkasan Pesanan</h5>
        <div class="table-responsive">
          <table class="table table-bordered mb-0">
            <thead class="table-light">
              <tr>
                <th>Nama</th>
                <th>Harga</th>
                <th class="text-center">Jml</th>
                <th class="text-end">Sub Total</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              if (!empty($items)):
                foreach ($items as $index => $item):
              ?>
              <tr>
                <td><?= esc($item['name']) ?></td>
                <td class="text-nowrap"><?= number_to_currency($item['price'], 'IDR') ?></td>
                <td class="text-center"><?= $item['qty'] ?></td>
                <td class="text-end text-nowrap"><?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
              </tr>
              <?php
                endforeach;
              endif;
              ?>
              <tr>
                <td colspan="3" class="text-end fw-semibold">Subtotal</td>
                <td class="text-end text-nowrap"><?= number_to_currency($total, 'IDR') ?></td>
              </tr>
              <tr>
                <td colspan="3" class="text-end fw-semibold">Ongkir</td>
                <td class="text-end text-nowrap" id="ongkirDisplay">Rp0</td>
              </tr>
              <tr class="table-active">
                <td colspan="3" class="text-end fw-bold">Total</td>
                <td class="text-end fw-bold text-nowrap fs-5" id="total"><?= number_to_currency($total, 'IDR') ?></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="text-center mt-4">
          <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-check-circle"></i> Buat Pesanan
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<?= form_close() ?>

<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
$(document).ready(function() {
  var ongkir = 0;
  var total = 0;
  hitungTotal();

  $('#kelurahan').select2({
    placeholder: 'Ketik nama kelurahan...',
    ajax: {
      url: '<?= base_url('get-location') ?>',
      dataType: 'json',
      delay: 1500,
      data: function(params) {
        return { search: params.term };
      },
      processResults: function(data) {
        return {
          results: data.map(function(item) {
            return {
              id: item.id,
              text: item.subdistrict_name + ", " + item.district_name + ", " + item.city_name + ", " + item.province_name + ", " + item.zip_code
            };
          })
        };
      },
      cache: true
    },
    minimumInputLength: 3
  });

  $("#kelurahan").on('change', function() {
    var id_kelurahan = $(this).val();
    $("#layanan").empty();
    ongkir = 0;

    $.ajax({
      url: "<?= site_url('get-cost') ?>",
      type: 'GET',
      data: { 'destination': id_kelurahan },
      dataType: 'json',
      success: function(data) {
        data.forEach(function(item) {
          var text = item["description"] + " (" + item["service"] + ") : estimasi " + item["etd"] + "";
          $("#layanan").append($('<option>', {
            value: item["cost"],
            text: text
          }));
        });
        hitungTotal();
      },
    });
  });

  $("#layanan").on('change', function() {
    ongkir = parseInt($(this).val());
    hitungTotal();
  });

  function hitungTotal() {
    total = ongkir + <?= $total ?>;
    $("#ongkir").val(ongkir);
    $("#ongkirDisplay").html("Rp" + ongkir.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
    $("#total").html("Rp" + total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
    $("#total_harga").val(total);
  }
});
</script>
<?= $this->endSection() ?>
