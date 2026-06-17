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
  <?php foreach ($products as $item): ?>
  <div class="col-lg-4 col-md-6">
    <?= form_open('keranjang') ?>
    <?= form_hidden([
      'id'    => $item['id'],
      'nama'  => $item['nama'],
      'harga' => $item['harga'],
      'foto'  => $item['foto']
    ]) ?>
    <div class="card h-100 shadow-sm">
      <div class="text-center p-3">
        <img src="<?= base_url('img/' . $item['foto']) ?>" alt="<?= esc($item['nama']) ?>" style="width:100%;max-height:200px;object-fit:contain;">
      </div>
      <div class="card-body pt-0">
        <h6 class="card-title mb-1"><?= esc($item['nama']) ?></h6>
        <p class="fw-bold text-primary mb-2"><?= number_to_currency($item['harga'], 'IDR') ?></p>
        <button type="submit" class="btn btn-info w-100 rounded-pill">
          <i class="bi bi-cart-plus"></i> Beli
        </button>
      </div>
    </div>
    <?= form_close() ?>
  </div>
  <?php endforeach ?>
</div>

<?= $this->endSection() ?>
