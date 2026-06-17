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

<?= form_open('keranjang/edit') ?>
<div class="card">
  <div class="card-body p-3">
    <?php if (!empty($items)): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Nama</th>
            <th class="text-center">Foto</th>
            <th>Harga</th>
            <th class="text-center">Jumlah</th>
            <th>Subtotal</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $i = 1;
          foreach ($items as $index => $item):
          ?>
          <tr>
            <td class="fw-semibold"><?= esc($item['name']) ?></td>
            <td class="text-center">
              <img src="<?= base_url('img/' . $item['options']['foto']) ?>" width="70" height="70" style="object-fit:cover;border-radius:6px;" alt="<?= esc($item['name']) ?>">
            </td>
            <td class="text-nowrap"><?= number_to_currency($item['price'], 'IDR') ?></td>
            <td class="text-center" style="width:100px;">
              <input type="number" min="1" name="qty<?= $i++ ?>" class="form-control form-control-sm text-center" value="<?= $item['qty'] ?>">
            </td>
            <td class="fw-semibold text-nowrap"><?= number_to_currency($item['subtotal'], 'IDR') ?></td>
            <td class="text-center">
              <a href="<?= base_url('keranjang/delete/' . $item['rowid']) ?>" class="btn btn-sm btn-danger">
                <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php else: ?>
    <div class="text-center py-5">
      <i class="bi bi-cart-x fs-1 text-muted"></i>
      <p class="text-muted mt-2 mb-0">Keranjang belanja kosong.</p>
      <a href="<?= base_url('/') ?>" class="btn btn-primary mt-2">Belanja Sekarang</a>
    </div>
    <?php endif; ?>
  </div>
</div>

<?php if (!empty($items)): ?>
<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3">
  <div class="alert alert-info mb-0 py-2 px-3">
    <strong>Total: <?= number_to_currency($total, 'IDR') ?></strong>
  </div>
  <div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">
      <i class="bi bi-arrow-repeat"></i> Perbarui Keranjang
    </button>
    <a class="btn btn-warning" href="<?= base_url('keranjang/clear') ?>">
      <i class="bi bi-cart-x"></i> Kosongkan
    </a>
    <a class="btn btn-success" href="<?= base_url('checkout') ?>">
      <i class="bi bi-bag-check"></i> Selesai Belanja
    </a>
  </div>
</div>
<?php endif; ?>

<?= form_close() ?>
<?= $this->endSection() ?>
