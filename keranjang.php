<?php
include 'koneksi.php';

$total = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - SegarJuice</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h2>🍹 SegarJuice</h2>
    <nav>
        <a href="index.php">Beranda</a>
        <a href="keranjang.php">Keranjang</a>
        <a href="login.php">Admin</a>
    </nav>
</header>

<section class="cart-container">

    <div class="cart-title">
        <h1>🛒 Keranjang Belanja</h1>
        <p>Periksa pesanan jus buah kamu sebelum checkout.</p>
    </div>

    <div class="cart-box">

        <table class="cart-table">
            <tr>
                <th>Nama Jus</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>

            <?php
            if (!empty($_SESSION['keranjang'])) {

                foreach ($_SESSION['keranjang'] as $id => $jumlah) {

                    $id = (int)$id;

                    $query = mysqli_query(
                        $conn,
                        "SELECT * FROM produk WHERE id = $id"
                    );

                    $p = mysqli_fetch_assoc($query);

                    if (!$p) continue;

                    $jumlah = min((int)$jumlah, (int)$p['stok']);
                    $subtotal = $p['harga'] * $jumlah;
                    $total += $subtotal;
            ?>

            <tr>
                <td>🍹 <?= htmlspecialchars($p['nama']) ?></td>
                <td>Rp<?= number_format($p['harga']) ?></td>
                <td><?= $jumlah ?></td>
                <td>Rp<?= number_format($subtotal) ?></td>
            </tr>

            <?php
                }
            } else {
            ?>

            <tr>
                <td colspan="4">Keranjang kamu masih kosong.</td>
            </tr>

            <?php } ?>

        </table>

        <div class="cart-total">
            <h2>Total Pembayaran</h2>
            <h1>Rp<?= number_format($total) ?></h1>
        </div>

        <div class="cart-button">
            <a href="index.php" class="btn-back">
                + Tambah Jus
            </a>

            <?php if (!empty($_SESSION['keranjang'])) { ?>
                <a href="checkout.php" class="btn-checkout">
                    Checkout Sekarang →
                </a>
            <?php } ?>
        </div>

    </div>

</section>

<footer>
    <p>© 2026 SegarJuice | Jus Buah Segar Setiap Hari 🍊</p>
</footer>

</body>
</html>