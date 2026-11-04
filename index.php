<?php
include 'koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM produk");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SegarJuice</title>
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

<section class="hero">
    <h1>Selamat Datang di SegarJuice</h1>
    <p>Kesegaran buah dalam setiap tegukan!</p>
    <a href="#menu" class="btn">Lihat Menu</a>
</section>

<section class="container" id="menu">
    <h2>Menu Jus Buah</h2>

    <div class="produk">

    <?php while ($p = mysqli_fetch_assoc($query)) { ?>

        <div class="card">

            <img src="images/<?php
                echo htmlspecialchars($p['gambar']);
            ?>" alt="jus buah"
            width="200">

        
            <h3>
                <?php echo htmlspecialchars($p['nama']); ?>
            </h3>

            <p>
                <?php echo htmlspecialchars($p['deskripsi']); ?>
            </p>

            <h3>
                Rp<?php echo number_format($p['harga']); ?>
            </h3>

            <form action="proses_pesan.php" method="POST">
                <input type="hidden" name="id"
                    value="<?php echo $p['id']; ?>">
                <button type="submit">+ Keranjang</button>
            </form>

        </div>

    <?php } ?>

    </div>
</section>

<footer>
    <p>© 2026 SegarJuice</p>
</footer>

</body>
</html>