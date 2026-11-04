<?php
include '../koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['ubah'])) {

    $id = (int)$_POST['id'];
    $status = $_POST['status'];

    $daftar = ['Menunggu','Diproses','Selesai','Dibatalkan'];

    if (in_array($status, $daftar, true)) {

        $stmt = mysqli_prepare($conn,
            "UPDATE pesanan SET status = ? WHERE id = ?"
        );

        mysqli_stmt_bind_param($stmt, "si", $status, $id);
        mysqli_stmt_execute($stmt);
    }

    header("Location: pesanan.php");
    exit;
}

$query = mysqli_query($conn,
    "SELECT * FROM pesanan ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pesanan</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container">

<h2>Data Pesanan Pelanggan</h2>

<table>
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Telepon</th>
    <th>Total</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>

<?php while ($p = mysqli_fetch_assoc($query)) { ?>

<tr>
    <td><?= $p['id'] ?></td>
    <td><?= htmlspecialchars($p['nama_pelanggan']) ?></td>
    <td><?= htmlspecialchars($p['telepon']) ?></td>
    <td>Rp<?= number_format($p['total']) ?></td>
    <td><?= htmlspecialchars($p['status']) ?></td>

    <td>
        <form method="POST">
            <input type="hidden" name="id"
                value="<?= $p['id'] ?>">

            <select name="status">
                <option>Menunggu</option>
                <option>Diproses</option>
                <option>Selesai</option>
                <option>Dibatalkan</option>
            </select>

            <button name="ubah" class="btn">
                Simpan
            </button>
        </form>
    </td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>