<?php
sleep(1);
require '../functions.php';
$keyword = $_GET["keyword"];

$query = "SELECT * FROM mahasiswa
            WHERE 
          nama LIKE '%$keyword%' OR
          nrp LIKE '%$keyword%' OR
          email LIKE '%$keyword%' OR
          jurusan LIKE '%$keyword%' 
          ";
$mhs = query($query);

?>

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