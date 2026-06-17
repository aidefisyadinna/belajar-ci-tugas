<table border="1" cellpadding="5" cellspacing="0">
  <thead>
    <tr><th>No</th><th>ID Transaksi</th><th>Tanggal</th><th>Username</th><th>Total Harga</th><th>Status</th></tr>
  </thead>
  <tbody>
    <?php $total = 0; foreach ($laporan as $i => $row) : $total += $row['total_harga']; ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <td><?= $row['id'] ?></td>
      <td><?= $row['created_at'] ?></td>
      <td><?= $row['username'] ?></td>
      <td><?= $row['total_harga'] ?></td>
      <td><?= ['Pending','Paid','Shipped','Completed','Cancelled'][$row['status']] ?? 'Tidak Diketahui' ?></td>
    </tr>
    <?php endforeach; ?>
    <tr><td colspan="4"><strong>Total Pendapatan</strong></td><td colspan="2"><strong><?= $total ?></strong></td></tr>
  </tbody>
</table>