<?php
include 'koneksi.php';

if (empty($_SESSION['keranjang'])) {
    header("Location: keranjang.php");
    exit;
}

if (isset($_POST['pesan'])) {

    $nama = trim($_POST['nama']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    $bayar = $_POST['bayar'];

    if (
        $nama == '' || $telepon == '' || $alamat == '' ||
        !in_array($bayar, ['Tunai', 'Transfer'], true)
    ) {
        die("Data pemesanan tidak lengkap.");
    }

    mysqli_begin_transaction($conn);

    try {
        $total = 0;
        $items = [];

        foreach ($_SESSION['keranjang'] as $id => $jumlah) {
            $id = (int)$id;
            $jumlah = (int)$jumlah;

            $q = mysqli_query($conn,
                "SELECT * FROM produk WHERE id = $id FOR UPDATE"
            );

            $p = mysqli_fetch_assoc($q);

            if (!$p || $jumlah < 1 || $jumlah > $p['stok']) {
                throw new Exception("Stok produk tidak mencukupi.");
            }

            $p['jumlah'] = $jumlah;
            $p['subtotal'] = $p['harga'] * $jumlah;
            $total += $p['subtotal'];
            $items[] = $p;
        }

        $stmt = mysqli_prepare($conn,
            "INSERT INTO pesanan
            (nama_pelanggan, telepon, alamat, metode_bayar, total)
            VALUES (?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt, "ssssi",
            $nama, $telepon, $alamat, $bayar, $total
        );

        mysqli_stmt_execute($stmt);
        $pesanan_id = mysqli_insert_id($conn);

        foreach ($items as $p) {

            $stmt = mysqli_prepare($conn,
                "INSERT INTO detail_pesanan
                (pesanan_id, produk_id, nama_produk, harga, jumlah, subtotal)
                VALUES (?, ?, ?, ?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt, "iisiii",
                $pesanan_id,
                $p['id'],
                $p['nama'],
                $p['harga'],
                $p['jumlah'],
                $p['subtotal']
            );

            mysqli_stmt_execute($stmt);

            $id = (int)$p['id'];
            $jumlah = (int)$p['jumlah'];

            mysqli_query($conn,
                "UPDATE produk SET stok = stok - $jumlah
                WHERE id = $id"
            );
        }

        mysqli_commit($conn);
        unset($_SESSION['keranjang']);

        echo "<!DOCTYPE html>
        <html lang='id'>
        <head>
        <meta charset='UTF-8'>
        <link rel='stylesheet' href='style.css'>
        <title>Pesanan Berhasil</title>
        </head>
        <body>
        <div class='success-box'>
            <h1>🎉 Pesanan Berhasil!</h1>
            <p>Terima kasih sudah membeli jus di SegarJuice.</p>
            <h2>Nomor Pesanan: #$pesanan_id</h2>
            <a href='index.php' class='checkout-btn'>Kembali ke Beranda</a>
        </div>
        </body>
        </html>";

        exit;

    } catch (Throwable $e) {
        mysqli_rollback($conn);
        die("Pesanan gagal: " .
            htmlspecialchars($e->getMessage()));
    }
}

$total = 0;

foreach ($_SESSION['keranjang'] as $id => $jumlah) {
    $id = (int)$id;

    $q = mysqli_query($conn,
        "SELECT harga FROM produk WHERE id = $id"
    );

    $p = mysqli_fetch_assoc($q);

    if ($p) {
        $total += $p['harga'] * (int)$jumlah;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - SegarJuice</title>
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

<section class="checkout-container">

    <div class="checkout-heading">
        <h1>🛒 Checkout Pesanan</h1>
        <p>Lengkapi data pemesanan jus buah kamu.</p>
    </div>

    <div class="checkout-content">

        <div class="checkout-form">

            <h2>📝 Data Pelanggan</h2>

            <form method="POST">

                <label>Nama Lengkap</label>
                <input
                    type="text"
                    name="nama"
                    placeholder="Masukkan nama lengkap"
                    required
                >

                <label>Nomor Telepon</label>
                <input
                    type="tel"
                    name="telepon"
                    placeholder="Contoh: 08123456789"
                    required
                >

                <label>Alamat Pengiriman</label>
                <textarea
                    name="alamat"
                    rows="4"
                    placeholder="Masukkan alamat lengkap"
                    required
                ></textarea>

                <label>Metode Pembayaran</label>

                <select name="bayar" required>
                    <option value="Tunai">Tunai / COD</option>
                    <option value="Transfer">Transfer Bank</option>
                </select>

                <button type="submit" name="pesan"
                    class="checkout-btn">
                    Buat Pesanan Sekarang →
                </button>

            </form>

        </div>

        <div class="checkout-summary">

            <h2>🍓 Ringkasan Pesanan</h2>

            <div class="summary-item">
                <span>Jumlah jenis produk</span>
                <strong><?= count($_SESSION['keranjang']) ?></strong>
            </div>

            <div class="summary-item">
                <span>Metode pengiriman</span>
                <strong>Diantar</strong>
            </div>

            <div class="summary-total">
                <p>Total Pembayaran</p>
                <h1>Rp<?= number_format($total) ?></h1>
            </div>

            <p class="summary-note">
                🍹 Jus buah segar siap menemani harimu!
            </p>

        </div>

    </div>

</section>

<footer>
    <p>© 2026 SegarJuice | Jus Buah Segar Setiap Hari 🍊</p>
</footer>

</body>
</html>