<?php
// koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "projek_latihan");


function query($query)
{
  global $conn;
  $result = mysqli_query($conn, $query);

  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }
  return $rows;
}


function tambah($data)
{
  global $conn;

  // ambil data dari tiap elemen dalam form
  $nrp = htmlspecialchars($data["nrp"]);
  $nama = htmlspecialchars($data["nama"]);
  $email = htmlspecialchars($data["email"]);
  $jurusan = htmlspecialchars($data["jurusan"]);

  // upload gambar
  $gambar = upload();
  if (!$gambar) {
    return false;
  }

  // query insert data
  $query = "INSERT INTO mahasiswa
                VALUES
              ( '', '$nrp', '$nama', '$email', '$jurusan', '$gambar' )
              ";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function upload()
{
  $namaFile = $_FILES['gambar']['name'];
  $ukuranFile = $_FILES['gambar']['size'];
  $error = $_FILES['gambar']['error'];
  $tmpName = $_FILES['gambar']['tmp_name'];

  // cek apakah tidak ada gambar yg di upload
  if ($error === 4) {
    echo "<script>
              alert('pilih gambar dahulu');
          </script>";
    return false;
  }

  // cek apakah yg di upload gambar atau bukan  
  $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
  $ekstensiGambar = explode('.', $namaFile);
  $ekstensiGambarNew = strtolower(end($ekstensiGambar));
  if (!in_array($ekstensiGambarNew, $ekstensiGambarValid)) {
    echo "<script>
              alert('yg anda pilih bukan gambar!');
          </script>";
    return false;
  }

  // cek jika ukuran gambar terlalu besar
  if ($ukuranFile > 1000000) {
    echo "<script>
              alert('ukuran gambar terlalu besar!');
          </script>";
    return false;
  }

  // lolos pengecekan, gambar siap di upload
  // generate nama gambar baru

  $namaFileBaru = uniqid();
  $namaFileBaru .= '.';
  $namaFileBaru .= $ekstensiGambarNew;
  move_uploaded_file($tmpName, 'img/' . $namaFileBaru);

  return $namaFileBaru;
}


function hapus($id)
{
  global $conn;

  $h = "DELETE FROM mahasiswa WHERE id = $id";
  mysqli_query($conn, $h);

  mysqli_affected_rows($conn);
}


function ubah($data1)
{
  global $conn;

  // ambil data dari tiap elemen dalam form
  $id = $data1["id"];
  $nrp = htmlspecialchars($data1["nrp"]);
  $nama = htmlspecialchars($data1["nama"]);
  $email = htmlspecialchars($data1["email"]);
  $jurusan = htmlspecialchars($data1["jurusan"]);
  $gambarLama = $data1["gambarLama"];

  // cek apakah user pilih gambar baru atau tidak
  if ($_FILES['gambar']['error'] === 4) {
    $gambar = $gambarLama;
  } else {
    $gambar = upload();
  }

  // query insert data
  $query = "UPDATE mahasiswa SET
                nrp = '$nrp',
                nama = '$nama',
                email = '$email',
                jurusan = '$jurusan',
                gambar = '$gambar'
              WHERE id = $id
                ";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}


function cari($keyword)
{
  $query = "SELECT * FROM mahasiswa
              WHERE 
            nama LIKE '%$keyword%' OR
            nrp LIKE '%$keyword%' OR
            email LIKE '%$keyword%' OR
            jurusan LIKE '%$keyword%' 
            ";
  return query($query);
}


function registrasi($data)
{
  global $conn;

  $username = strtolower(stripslashes($data["username"]));
  $password = mysqli_real_escape_string($conn, $data["password"]);
  $password2 = mysqli_real_escape_string($conn, $data["password2"]);

  // cek username sudah ada atau belum
  $cariUsername = "SELECT username FROM users WHERE username = '$username'";
  $result = mysqli_query($conn, $cariUsername);

  if (mysqli_fetch_assoc($result)) {
    echo "<script>
            alert('User Sudah Terdaftar!')
          </script>";

    return false;
  }

  // cek konfirmasi password
  if ($password !== $password2) {
    echo "<script>
            alert('konfirmasi password tidak sesuai!')
          </script>";

    return false;
  }

  // enkripsi password
  $password = password_hash($password, PASSWORD_DEFAULT);

  // tambahkan user baru ke database
  mysqli_query($conn, "INSERT INTO users VALUES ('', '$username', '$password')");
  return mysqli_affected_rows($conn);
}
