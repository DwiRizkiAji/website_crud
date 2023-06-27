<?php
session_start();
if (!isset($_SESSION["login"])) {
  header("location: login.php");
  exit;
}

require 'functions.php';

// ambil data di url
$id = $_GET["id"];

//query atau ambil data mahasiswa berdasarkan id
$data = query("SELECT * FROM mahasiswa WHERE id = $id")[0];

if (isset($_POST["submit"])) {

  // cek apakah data berhasil di ubah atau tidak
  if (ubah($_POST) > 0) {
    echo "
          <script>
              alert('Data Berhasil Di Ubah');
              document.location.href = 'index.php';
          </script>
    ";
  } else {
    echo "
          <script>
              alert('Data Gagal Di Ubah');
              document.location.href = 'index.php';
          </script>
    ";
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ubah Data Mahasiswa</title>
</head>

<body>
  <h1>Ubah Data Mahasiswa</h1>

  <form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $data["id"]; ?>">
    <input type="hidden" name="gambarLama" value="<?= $data["gambar"]; ?>">
    <ul>
      <li>
        <label for="nrp">Nrp : </label>
        <input type="text" name="nrp" id="nrp" required autocomplete="off" value="<?= $data["nrp"]; ?>">
      </li>
      <li>
        <label for="nama">Nama : </label>
        <input type="text" name="nama" id="nama" required autocomplete="off" value="<?= $data["nama"]; ?>">
      </li>
      <li>
        <label for="email">Email : </label>
        <input type="text" name="email" id="email" required autocomplete="off" value="<?= $data["email"]; ?>">
      </li>
      <li>
        <label for="jurusan">Jurusan : </label>
        <input type="text" name="jurusan" id="jurusan" autocomplete="off" value="<?= $data["jurusan"]; ?>">
      </li>
      <li>
        <label for="gambar">Gambar : </label><br>
        <img src="img/<?= $data["gambar"]; ?>" width="150"><br>
        <input type="file" name="gambar" id="gambar" value="<?= $data["gambar"]; ?>">
      </li>
      <br>
      <li>
        <button type="submit" name="submit">Ubah Data</button>
      </li>
    </ul>
  </form>


</body>

</html>