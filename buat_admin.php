<?php
include 'koneksi.php';

$username = "admin";
$password = password_hash("admin123", PASSWORD_DEFAULT);

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO admin (username, password)
     VALUES (?, ?)"
);

mysqli_stmt_bind_param(
    $stmt, "ss", $username, $password
);

if (mysqli_stmt_execute($stmt)) {
    echo "Admin berhasil dibuat!";
} else {
    echo "Admin sudah tersedia atau terjadi kesalahan.";
}
?>