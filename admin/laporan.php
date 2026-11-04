<?php
include '../koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

$query = mysqli_query($conn,
    "SELECT DATE(dibuat) AS tanggal,
    COUNT(*) AS jumlah,
    SUM(total) AS omzet
    FROM pesanan
    WHERE status != 'Dibatalkan'
    GROUP BY DATE(dibuat)
    ORDER BY tanggal DESC"
);

$total = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT SUM(total) AS omzet FROM pesanan
    WHERE status != 'Dibatalkan'"
));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container">

<h2>Laporan Penjualan Jus Buah</h2>

<h3>Total Omzet:
Rp<?= number_format($total['omzet'] ?? 0) ?>
</h3>

<br>

<table>
<tr>
    <th>Tanggal</th>
    <th>Jumlah Pesanan</th>
    <th>Omzet</th>
</tr>

<?php while ($p = mysqli_fetch_assoc($query)) { ?>

<tr>
    <td><?= htmlspecialchars($p['tanggal']) ?></td>
    <td><?= $p['jumlah'] ?></td>
    <td>Rp<?= number_format($p['omzet']) ?></td>
</tr>

<?php } ?>

</table>

<br>

<button onclick="window.print()" class="btn">
    Cetak Laporan
</button>

</div>

</body>
</html>