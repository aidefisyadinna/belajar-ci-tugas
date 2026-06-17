<?php helper('number'); ?>
<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashData('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <?= session()->getFlashData('success') ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (session()->getFlashData('failed')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <?= session()->getFlashData('failed') ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="d-flex gap-2 mb-3">
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
    <i class="bi bi-plus-lg"></i> Tambah Data
  </button>
  <a class="btn btn-success" target="_blank" href="<?= base_url('produk/download') ?>">
    <i class="bi bi-download"></i> Download Data
  </a>
</div>

<div class="card">
  <div class="card-body p-3">
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Harga</th>
            <th class="text-center">Jumlah</th>
            <th class="text-center">Foto</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $index => $produk): ?>
          <tr>
            <td><?= $index + 1 ?></td>
            <td class="fw-semibold"><?= esc($produk['nama']) ?></td>
            <td class="text-nowrap"><?= number_to_currency($produk['harga'], 'IDR') ?></td>
            <td class="text-center"><?= $produk['jumlah'] ?></td>
            <td class="text-center">
              <?php if ($produk['foto'] != '' && file_exists("img/" . $produk['foto'])): ?>
                <img src="<?= base_url('img/' . $produk['foto']) ?>" width="60" height="60" style="object-fit:cover;border-radius:6px;" alt="<?= esc($produk['nama']) ?>">
              <?php else: ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
            <td class="text-center">
              <div class="d-flex gap-1 justify-content-center">
                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editModal-<?= $produk['id'] ?>">
                  <i class="bi bi-pencil"></i> Ubah
                </button>
                <a href="<?= base_url('produk/delete/' . $produk['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')">
                  <i class="bi bi-trash"></i> Hapus
                </a>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?= $this->include('produk/modal_add') ?>
<?= $this->include('produk/modal_edit') ?>
<?= $this->endSection() ?>
