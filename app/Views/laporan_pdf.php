<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Laporan Pendapatan</title>
  <style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    th { background-color: #eee; }
    h3 { text-align: center; }
  </style>
</head>
<body>
<h3>Laporan Pendapatan</h3>
<p>Periode: <?= esc($tanggal_awal) ?> s.d <?= esc($tanggal_akhir) ?></p>
<table>
  <thead>
    <tr><th>No</th><th>ID Transaksi</th><th>Tanggal</th><th>Username</th><th>Total Harga</th><th>Status</th></tr>
  </thead>
  <tbody>
    <?php $total = 0; foreach ($laporan as $i => $row) : $total += $row['total_harga']; ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <td><?= esc($row['id']) ?></td>
      <td><?= esc($row['created_at']) ?></td>
      <td><?= esc($row['username']) ?></td>
      <td>Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
      <td><?= ['Pending','Paid','Shipped','Completed','Cancelled'][$row['status']] ?? 'Tidak Diketahui' ?></td>
    </tr>
    <?php endforeach; ?>
    <tr><td colspan="4"><strong>Total Pendapatan</strong></td><td colspan="2"><strong>Rp<?= number_format($total, 0, ',', '.') ?></strong></td></tr>
  </tbody>
</table>
</body>
</html>