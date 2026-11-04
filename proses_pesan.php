<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = (int) $_POST['id'];

    $query = mysqli_query(
        $conn,
        "SELECT * FROM produk WHERE id = $id"
    );

    $produk = mysqli_fetch_assoc($query);

    if ($produk && $produk['stok'] > 0) {

        if (!isset($_SESSION['keranjang'])) {
            $_SESSION['keranjang'] = [];
        }

        $jumlah = $_SESSION['keranjang'][$id] ?? 0;

        if ($jumlah < $produk['stok']) {
            $_SESSION['keranjang'][$id] = $jumlah + 1;
        }
    }
}

header("Location: keranjang.php");
exit;
?>