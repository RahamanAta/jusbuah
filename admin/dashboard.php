<?php
include '../koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

$produk = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM produk")
);

$pesanan = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM pesanan")
);

$penjualan = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT SUM(total) AS total FROM pesanan
     WHERE status != 'Dibatalkan'")
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header>
    <h2>Admin SegarJuice</h2>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="produk.php">Produk</a>
        <a href="pesanan.php">Pesanan</a>
        <a href="laporan.php">Laporan</a>
        <a href="../logout.php">Keluar</a>
    </nav>
</header>

<div class="container">

<h2>Dashboard Penjualan</h2>

<div class="produk">

<div class="card">
    <h3>Total Produk</h3>
    <h1><?= $produk['total'] ?></h1>
</div>

<div class="card">
    <h3>Total Pesanan</h3>
    <h1><?= $pesanan['total'] ?></h1>
</div>

<div class="card">
    <h3>Total Penjualan</h3>
    <h2>Rp<?= number_format($penjualan['total'] ?? 0) ?></h2>
</div>

</div>

</div>

</body>
</html>