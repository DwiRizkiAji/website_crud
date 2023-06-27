<?php
session_start();
if (!isset($_SESSION["login"])) {
  header("location: login.php");
  exit;
}

require 'functions.php';

// pagination
// konfigurasi
$jumlahDataPerHalaman = 3;
$jumlahData = count(query("SELECT * FROM mahasiswa"));
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
$halamanAktif = (isset($_GET["halaman"])) ? $_GET["halaman"] : 1;
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

$mhs = query("SELECT * FROM mahasiswa ORDER BY id DESC LIMIT $awalData, $jumlahDataPerHalaman");

// tombol cari di tekan
if (isset($_POST["cari"])) {
  $mhs = cari($_POST["keyword"]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Halaman Admin</title>
  <style>
    .loader {
      width: 100px;
      position: absolute;
      top: 118px;
      left: 155px;
      z-index: -1;
      display: none;
    }
  </style>
</head>

<body>

  <a href="logout.php" onclick="return confirm('apakah yakin?');">Logout</a> ||
  <a href="/vendor/cetak.php">Cetak</a>


  <h1>Daftar Mahasiswa</h1>

  <a href="tambah.php">Tambah Data Mahasiswa</a>
  <br><br>

  <form action="" method="post">

    <input type="text" name="keyword" autofocus placeholder="pencarian...." autocomplete="off" id="keyword">
    <button type="submit" name="cari" id="tombol-cari">Cari!</button>

    <img src="img/loader2.gif" class="loader">

  </form>
  <br>

  <!-- navigasi -->
  <div class="pagination">
    <?php if ($halamanAktif > 1) : ?>
      <a href="?halaman=<?= $halamanAktif - 1; ?>">&lt;</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
      <?php if ($i == $halamanAktif) : ?>
        <a href="?halaman=<?= $i; ?>" style="font-weight: bold; color: red;"><?= $i; ?></a>
      <?php else : ?>
        <a href="?halaman=<?= $i; ?>"><?= $i; ?></a>
      <?php endif; ?>
    <?php endfor; ?>


    <?php if ($halamanAktif < $jumlahHalaman) : ?>
      <a href="?halaman=<?= $halamanAktif + 1; ?>">&gt;</a>
    <?php endif; ?>
  </div>
  <!-- end -->

  <div id="container">
    <table border="1" cellpadding="10" cellspacing="0">
      <tr>
        <th>No</th>
        <th>Aksi</th>
        <th>Gambar</th>
        <th>NRP</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Jurusan</th>
      </tr>

      <?php $i = 1;
      foreach ($mhs as $row) : ?>
        <tr>
          <td><?= $i++; ?></td>
          <td>
            <a href="ubah.php?id=<?= $row["id"]; ?>">Ubah</a> | <a href="hapus.php?id=<?= $row["id"]; ?>" onclick="return confirm('apakah yakin?');">Hapus</a>
          </td>
          <td><img src="img/<?= $row["gambar"]; ?>" width="60"></td>
          <td><?= $row["nrp"]; ?></td>
          <td><?= $row["nama"]; ?></td>
          <td><?= $row["email"]; ?></td>
          <td><?= $row["jurusan"] ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>

  <script src="js/code.jquery.com_jquery-3.7.0.min.js"></script>
  <script src="js/script.js"></script>
</body>

</html>