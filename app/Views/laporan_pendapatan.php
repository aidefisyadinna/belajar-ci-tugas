<?php helper('number'); ?>
<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-body p-4">
    <form action="<?= base_url('laporan/pendapatan') ?>" method="get" class="row g-3 mb-4">
      <div class="col-md-4">
        <label for="tanggal_awal" class="form-label fw-semibold">Tanggal Awal</label>
        <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" value="<?= esc($tanggal_awal ?? '') ?>">
      </div>
      <div class="col-md-4">
        <label for="tanggal_akhir" class="form-label fw-semibold">Tanggal Akhir</label>
        <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="<?= esc($tanggal_akhir ?? '') ?>">
      </div>
      <div class="col-md-4 d-flex align-items-end gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-search"></i> Tampilkan
        </button>
        <?php if (!empty($laporan)): ?>
          <a href="<?= base_url("laporan/exportPdf?tanggal_awal=$tanggal_awal&tanggal_akhir=$tanggal_akhir") ?>" class="btn btn-danger">
            <i class="bi bi-filetype-pdf"></i> Cetak PDF
          </a>
          <a href="<?= base_url("laporan/exportExcel?tanggal_awal=$tanggal_awal&tanggal_akhir=$tanggal_akhir") ?>" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
          </a>
        <?php endif; ?>
      </div>
    </form>

    <?php if (!empty($laporan)): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>ID Transaksi</th>
            <th>Tanggal</th>
            <th>User</th>
            <th>Total Harga</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $total = 0;
          foreach ($laporan as $i => $row):
            $total += $row['total_harga'];
          ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><span class="fw-semibold">#<?= esc($row['id']) ?></span></td>
            <td class="text-nowrap"><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
            <td><?= esc($row['username']) ?></td>
            <td class="fw-semibold text-nowrap"><?= number_to_currency($row['total_harga'], 'IDR') ?></td>
            <td>
              <?php
              $badgeClass = [
                0 => 'bg-warning text-dark', 1 => 'bg-info', 2 => 'bg-primary',
                3 => 'bg-success', 4 => 'bg-danger'
              ][$row['status']] ?? 'bg-secondary';
              $statusLabel = ['Pending','Paid','Shipped','Completed','Cancelled'][$row['status']] ?? 'Tidak Diketahui';
              ?>
              <span class="badge <?= $badgeClass ?>"><?= $statusLabel ?></span>
            </td>
          </tr>
          <?php endforeach; ?>
          <tr class="table-warning fw-bold">
            <td colspan="4" class="text-end">Total Pendapatan</td>
            <td colspan="2"><?= number_to_currency($total, 'IDR') ?></td>
          </tr>
        </tbody>
      </table>
    </div>
    <?php elseif ($tanggal_awal && $tanggal_akhir): ?>
    <div class="text-center py-5">
      <i class="bi bi-inbox fs-1 text-muted"></i>
      <p class="text-muted mt-2 mb-0">Tidak ada data untuk periode tersebut.</p>
    </div>
    <?php endif; ?>
  </div>
</div>

<?= $this->endSection() ?>
