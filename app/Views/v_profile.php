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

<div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded border">
  <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:56px;height:56px;">
    <i class="bi bi-person-fill text-white fs-3"></i>
  </div>
  <div>
    <h5 class="mb-0"><?= esc($username) ?></h5>
    <small class="text-muted">Riwayat Transaksi Pembelian</small>
  </div>
</div>

<?php if (empty($buy)): ?>
<div class="text-center py-5">
  <i class="bi bi-inbox fs-1 text-muted"></i>
  <p class="text-muted mt-2 mb-0">Belum ada transaksi.</p>
  <small class="text-muted">Silakan belanja terlebih dahulu melalui halaman Home.</small>
</div>
<?php else: ?>
<div class="card">
  <div class="card-body p-3">
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>ID</th>
            <th>Waktu</th>
            <th>Total Bayar</th>
            <th>Alamat</th>
            <th class="text-center">Bukti</th>
            <th>Status</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($buy as $index => $item) : ?>
          <tr>
            <td><?= $index + 1 ?></td>
            <td><span class="fw-semibold">#<?= $item['id'] ?></span></td>
            <td class="text-nowrap"><?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></td>
            <td class="fw-semibold text-nowrap"><?= number_to_currency($item['total_harga'], 'IDR') ?></td>
            <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="<?= esc($item['alamat']) ?>"><?= esc($item['alamat']) ?></td>
            <td class="text-center">
              <?php if (!empty($item['bukti_pembayaran'])): ?>
                <a href="<?= base_url('uploads/bukti/' . $item['bukti_pembayaran']) ?>" target="_blank">
                  <img src="<?= base_url('uploads/bukti/' . $item['bukti_pembayaran']) ?>" width="60" height="60" style="object-fit:cover;border-radius:6px;" alt="Bukti">
                </a>
              <?php else: ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
            <td>
              <?php
              $statusLabel = [
                0 => 'Menunggu Pembayaran',
                1 => 'Sudah Dibayar',
                2 => 'Sedang Dikirim',
                3 => 'Sudah Selesai',
                4 => 'Dibatalkan'
              ][$item['status']] ?? 'Tidak Diketahui';
              $badgeClass = [
                0 => 'bg-warning text-dark',
                1 => 'bg-info',
                2 => 'bg-primary',
                3 => 'bg-success',
                4 => 'bg-danger'
              ][$item['status']] ?? 'bg-secondary';
              ?>
              <span class="badge fs-6 <?= $badgeClass ?>"><?= $statusLabel ?></span>
            </td>
            <td class="text-center">
              <div class="d-flex gap-1 justify-content-center">
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailModal-<?= $item['id'] ?>">
                  <i class="bi bi-eye"></i> Detail
                </button>
                <?php if ($item['status'] == 0): ?>
                <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#uploadModal-<?= $item['id'] ?>">
                  <i class="bi bi-upload"></i> Upload
                </button>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php foreach ($buy as $index => $item) : ?>
<!-- Modal Detail -->
<div class="modal fade" id="detailModal-<?= $item['id'] ?>" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title">Detail Transaksi #<?= $item['id'] ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php if (!empty($product) && isset($product[$item['id']])): ?>
          <?php foreach ($product[$item['id']] as $index2 => $item2) : ?>
          <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
            <?php if ($item2['foto'] != '' && file_exists(FCPATH . 'img/' . $item2['foto'])): ?>
              <img src="<?= base_url('img/' . $item2['foto']) ?>" width="70" height="70" style="object-fit:cover;border-radius:8px;" alt="<?= esc($item2['nama']) ?>">
            <?php endif; ?>
            <div class="flex-grow-1">
              <h6 class="mb-1"><?= esc($item2['nama']) ?></h6>
              <small class="text-muted"><?= number_to_currency($item2['harga'], 'IDR') ?> × <?= $item2['jumlah'] ?> pcs</small>
              <div class="fw-semibold mt-1"><?= number_to_currency($item2['subtotal_harga'], 'IDR') ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
        <div class="d-flex justify-content-between pt-1">
          <span>Ongkos Kirim</span>
          <span class="fw-semibold"><?= number_to_currency($item['ongkir'], 'IDR') ?></span>
        </div>
        <hr>
        <div class="d-flex justify-content-between">
          <span class="fw-bold">Total</span>
          <span class="fw-bold fs-5"><?= number_to_currency($item['total_harga'], 'IDR') ?></span>
        </div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Upload Bukti -->
<?php if ($item['status'] == 0): ?>
<div class="modal fade" id="uploadModal-<?= $item['id'] ?>" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="<?= base_url('upload-bukti') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="id_pembelian" value="<?= $item['id'] ?>">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title">Upload Bukti Pembayaran</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p class="text-muted small">Transaksi <strong>#<?= $item['id'] ?></strong></p>
          <div class="mb-3">
            <label for="bukti-<?= $item['id'] ?>" class="form-label">Pilih file gambar bukti pembayaran</label>
            <input class="form-control" type="file" id="bukti-<?= $item['id'] ?>" name="bukti" accept="image/*" required>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Kirim</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>
