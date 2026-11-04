<?php
include '../koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['simpan'])) {

    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $harga = (int)$_POST['harga'];
    $gambar = basename($_POST['gambar']);
    $stok = (int)$_POST['stok'];

    if ($nama !== '' && $harga > 0 && $stok >= 0) {

        $stmt = mysqli_prepare($conn,
            "INSERT INTO produk
            (nama, deskripsi, harga, gambar, stok)
            VALUES (?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt, "ssisi",
            $nama, $deskripsi, $harga, $gambar, $stok
        );

        mysqli_stmt_execute($stmt);
    }

    header("Location: produk.php");
    exit;
}

if (isset($_GET['hapus'])) {

    $id = (int)$_GET['hapus'];

    $stmt = mysqli_prepare($conn,
        "DELETE FROM produk WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    header("Location: produk.php");
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM produk");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Produk</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container">

<h2>Tambah Produk Jus</h2>

<div class="form-box">
<form method="POST">

<label>Nama Jus</label>
<input type="text" name="nama" required>

<label>Deskripsi</label>
<textarea name="deskripsi"></textarea>

<label>Harga</label>
<input type="number" name="harga" required min="1">

<label>Nama File Gambar</label>
<input type="text" name="gambar" placeholder="jeruk.jpg" required>

<label>Stok</label>
<input type="number" name="stok" required min="0">

<button type="submit" name="simpan" class="btn">
    Simpan Produk
</button>

</form>
</div>

<h2>Daftar Produk</h2>

<table>
<tr>
    <th>Nama</th>
    <th>Harga</th>
    <th>Stok</th>
    <th>Aksi</th>
</tr>

<?php while ($p = mysqli_fetch_assoc($query)) { ?>

<tr>
    <td><?= htmlspecialchars($p['nama']) ?></td>
    <td>Rp<?= number_format($p['harga']) ?></td>
    <td><?= $p['stok'] ?></td>
    <td>
        <a href="?hapus=<?= $p['id'] ?>"
        onclick="return confirm('Hapus produk?')">
        Hapus
        </a>
    </td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>