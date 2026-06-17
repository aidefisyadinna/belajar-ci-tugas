<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <?= session()->getFlashdata('success') ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <?= session()->getFlashdata('error') ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card">
  <div class="card-body p-3">
    <?php if (!empty($transactions)): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>ID</th>
            <th>Username</th>
            <th>Total Harga</th>
            <th>Alamat</th>
            <th>Ongkir</th>
            <th>Status</th>
            <th class="text-center">Ubah Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($transactions as $index => $item): ?>
          <tr>
            <td><?= $index + 1 ?></td>
            <td><span class="fw-semibold">#<?= $item['id'] ?></span></td>
            <td><?= esc($item['username']) ?></td>
            <td class="fw-semibold text-nowrap"><?= number_to_currency($item['total_harga'], 'IDR') ?></td>
            <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="<?= esc($item['alamat']) ?>"><?= esc($item['alamat']) ?></td>
            <td class="text-nowrap"><?= number_to_currency($item['ongkir'], 'IDR') ?></td>
            <td>
              <?php
              $statusLabel = [
                0 => 'Menunggu Pembayaran',
                1 => 'Sudah Dibayar',
                2 => 'Sedang Dikirim',
                3 => 'Selesai',
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
              <form action="<?= base_url('penjualan/updateStatus/' . $item['id']) ?>" method="post" class="d-flex gap-1 justify-content-center">
                <?= csrf_field() ?>
                <select name="status" class="form-select form-select-sm" style="width:auto;">
                  <option value="0" <?= $item['status'] == '0' ? 'selected' : '' ?>>Pending</option>
                  <option value="1" <?= $item['status'] == '1' ? 'selected' : '' ?>>Paid</option>
                  <option value="2" <?= $item['status'] == '2' ? 'selected' : '' ?>>Shipped</option>
                  <option value="3" <?= $item['status'] == '3' ? 'selected' : '' ?>>Completed</option>
                  <option value="4" <?= $item['status'] == '4' ? 'selected' : '' ?>>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-sm btn-success">
                  <i class="bi bi-check-lg"></i>
                </button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php else: ?>
    <div class="text-center py-5">
      <i class="bi bi-inbox fs-1 text-muted"></i>
      <p class="text-muted mt-2 mb-0">Belum ada transaksi.</p>
    </div>
    <?php endif; ?>
  </div>
</div>

<?= $this->endSection() ?>
