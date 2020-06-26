<?php

// Inisialisasi variabel
$username = "";
$email    = "";
$errors = array();

// Connect database
require_once('db_login.php');
$db = mysqli_connect($db_host, $db_username, $db_password,$db_database);

// REGISTER PENGGUNA
if (isset($_POST['reg_user'])) {
  // Terima semua input dari form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $gender = mysqli_real_escape_string($db, $_POST['gender']);
  $address = mysqli_real_escape_string($db, $_POST['address']);
  $province = mysqli_real_escape_string($db, $_POST['province']);
  $city = mysqli_real_escape_string($db, $_POST['city']);
  $poscode = mysqli_real_escape_string($db, $_POST['poscode']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // Form validation: memastikan form terisi / tidak kosong
  // Dengan (array_push()) error dimasukkan ke $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($gender)) { array_push($errors, "Gender is required"); }
  if (empty($address)) { array_push($errors, "Address is required"); }
  if (empty($province)) { array_push($errors, "Province is required"); }
  if (empty($city)) { array_push($errors, "City is required"); }
  if (empty($poscode)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // Cek database untuk memastikan tidak ada username yang sama
  $user_check_query = "SELECT * FROM pengguna WHERE nama='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);

  if ($user) { // Jika username sudah ada
    if ($user['email'] === $email) {
      array_push($errors, "Email already exists");
    }
  }

  // Masukkan data jika sudah tidak ada error
  if (count($errors) == 0) {
    $password = md5($password_1);
  	$query = "INSERT INTO pengguna (nama, email, gender, password, alamat, id_provinsi, id_kabupaten, kodepos)
    VALUES('$username', '$email', '$gender', '$password', '$address', '$province', '$city', '$poscode')";
  	mysqli_query($db, $query);
    session_start();
  	$_SESSION['email'] = $email;
    $user_check_query2 = "SELECT * FROM pengguna WHERE nama='$username' OR email='$email' LIMIT 1";
    $result2 = mysqli_query($db, $user_check_query2);
    $user2 = mysqli_fetch_assoc($result2);
    $_SESSION['id_pengguna']= $user2['id_pengguna'];
  	$_SESSION['success'] = "You are now logged in";

  	header('location: index.php');
  }
}


// LOGIN PENGGUNA
if (isset($_POST['login_user'])) {
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {
        $password_log = md5($password);
        $query = "SELECT * FROM pengguna WHERE password='$password_log' AND email='$email' LIMIT 1";
        $results = mysqli_query($db, $query);
        if (mysqli_num_rows($results) === 1) {
          session_start();
          $_SESSION['email'] = $email;
          $_SESSION['success'] = "You are now logged in";
          // UNTUK DI KERANJANG SOALNYA MAKE ID PENGGUNA MAAPKEN
          $anu=mysqli_fetch_array($results);
          $_SESSION['id_pengguna']=$anu['id_pengguna'];
          header("location: index.php");

          // UNTUK DI KERANJANG SOALNYA MAKE ID PENGGUNA MAAPKEN
          // $anu=mysqli_fetch_array($results);
          // $_SESSION['id_pengguna']=$anu['id_pengguna'];
        }
        else {
            array_push($errors, "Wrong username/password combination");

        }
    }
  }


// Checkout
if (isset($_POST['checkout_usr'])) {
  $id_pengguna = mysqli_real_escape_string($db, $_POST['id_pengguna']);
  // $id_pemiliktoko = mysqli_real_escape_string($db, $_POST['id_pemiliktoko']);
  // $tanggal_transaksi = mysqli_real_escape_string($db, $_POST['tanggal_transaksi']);
  $id_provinsi_kirim =$_POST['province'];
  $id_kabupaten_kirim = $_POST['city'];
  $kodepos_kirim = mysqli_real_escape_string($db, $_POST['kodepos']);
  $total_item = mysqli_real_escape_string($db, $_POST['total_item']);
  $total_biaya = mysqli_real_escape_string($db, $_POST['total_harga']);
  $biaya_kirim = mysqli_real_escape_string($db, $_POST['biaya_kirim']);
  $alamat = mysqli_real_escape_string($db, $_POST['alamat']);
  // $tanggal_transaksi = $_SERVER['REQUEST_TIME'];

  if (empty($id_pengguna)) { array_push($errors, "Id Pengguna is required"); }
  // if (empty($id_pemiliktoko)) { array_push($errors, "Id Pemilik Toko is required"); }
  // if (empty($tanggal_transaksi)) { array_push($errors, "Tanggal Transaksi is required"); }
  if (empty($total_item)) { array_push($errors, "Total Item is required"); }
  if (empty($total_biaya)) { array_push($errors, "Total Biaya is required"); }
  if (empty($id_provinsi_kirim)) { array_push($errors, "Province is required"); }
  if (empty($id_kabupaten_kirim)) { array_push($errors, "City is required"); }
  if (empty($kodepos_kirim)) { array_push($errors, "Poscode is required"); }
  if (empty($alamat)) { array_push($errors, "Address is required"); }

  // Masukkan data jika sudah tidak ada error
  if (count($errors) == 0) {
    $sql2 = "INSERT INTO transaksi (id_pengguna,  id_provinsi_kirim, id_kabupaten_kirim, kodepos_kirim, total_item, total_harga, biaya_kirim, alamat) VALUES('$id_pengguna', '$id_provinsi_kirim', '$id_kabupaten_kirim', '$kodepos_kirim', '$total_item', '$total_biaya','$biaya_kirim','$alamat')";
      $result2 = mysqli_query($db, $sql2) ;
      $idtr = mysqli_insert_id($db);
        if (!$result2) {
          printf("Error sql 2: %s\n", mysqli_error($db));
          exit();
        }
        $idtr = mysqli_insert_id($db);

      foreach($_POST['id_item'] as $key => $id){
        $id_items = $id;
        $id_owner= $_POST['id_own'][$key];
        $harga_perbarang= $_POST['total_harga_perbarang'][$key];
        $item_perbarang= $_POST['total_item_perbarang'][$key];
        $sql3 = "INSERT INTO detail_transaksi (id_transaksi, id_barang, id_pemiliktoko, jumlah, harga) VALUES($idtr, '$id_items', '$id_owner', '$item_perbarang', '$harga_perbarang')";
        $result3 = mysqli_query($db, $sql3) ;
        if (!$result3) {
          printf("Error sql3: %s\n", mysqli_error($db));
          exit();
        }
      }
      $sql4 = "INSERT INTO status_transaksi (id_transaksi, status) VALUES($idtr, 'Menunggu Pembayaran')";
      $result4 = mysqli_query($db, $sql4);
      if (!$result4) {
        printf("Error sql4: %s\n", mysqli_error($db));
        exit();}


      $sql5="DELETE from keranjang where id_pengguna='$id_pengguna'";
      $result5=mysqli_query($db,$sql5);
      if (!$result5) {
        printf("Error sql4: %s\n", mysqli_error($db));
        exit();}


      header('location: form_konfirmasi.php?id_transaksi='.$idtr.'');

  }


}

// Konfirmasi Pembayaran
if (isset($_POST['konfirmasi'])) {
  // Terima semua input dari form
  $id_transaksi = mysqli_real_escape_string($db, $_POST['id_transaksi']);
  $nama_pengirim = mysqli_real_escape_string($db, $_POST['nama']);
  $bank = mysqli_real_escape_string($db, $_POST['bank']);
  $norek = mysqli_real_escape_string($db, $_POST['norek']);
  $tanggal_tf = mysqli_real_escape_string($db, $_POST['tanggal_tf']);
  $total = mysqli_real_escape_string($db, $_POST['total']);


  $ekstensi_diperbolehkan	= array('png','jpg');
  $nama = $_FILES['file']['name'];
  $x = explode('.', $nama);
  $ekstensi = strtolower(end($x));
  $ukuran	= $_FILES['file']['size'];
  $file_tmp = $_FILES['file']['tmp_name'];

  if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
    if($ukuran < 1044070){
      move_uploaded_file($file_tmp, 'bukti/'.$nama);
      // $query1 = "INSERT INTO konfirmasi (file_bukti_pembayaran) VALUES(NULL, '$nama')";
      // $result = mysqli_query ($db, $query1);
       if (count($errors) == 0) {
        $query = "INSERT INTO konfirmasi_pembayaran (id_transaksi, nama_pengirim, nama_bank, nomor_rekening, jumlah_transfer, tanggal_transfer, file_bukti_pembayaran)
        VALUES('$id_transaksi', '$nama_pengirim', '$bank', '$norek', '$total', '$tanggal_tf', 'bukti/$nama')";
        mysqli_query($db, $query);
        $query1 = "INSERT INTO status_transaksi (id_transaksi, status) VALUES('$id_transaksi','Proses Pengiriman')";
        mysqli_query($db, $query1);

      }
    }
  }

  // Masukkan data jika sudah tidak ada error

  header('location: daftarpesanan.php');
}

if (isset($_POST['terimabarang'])) {
  $id_transaksi = mysqli_real_escape_string($db, $_POST['id_transaksi']);
   $query22 = "INSERT INTO status_transaksi (id_transaksi, status) VALUES('$id_transaksi','Barang Diterima')";
    mysqli_query($db, $query22);
    header('location: daftarpesanan.php');
}

//ACT KERANJANG
if (isset($_GET["act"])) {
    if ($_GET["act"]=="tambah_keranjang") {
      $id_barang=$_POST['id_barang'];
      $id_pengguna=$_POST['id_pengguna'];
      $jumlah=$_POST['jumlah'];

      $con=mysqli_connect("localhost","root","","db_toko");
      $query=mysqli_query($con,"select * from barang where id_barang='$id_barang'");
      if (!$query) {
          printf("Error: %s\n", mysqli_error($con));
          exit();
      }
      $data=mysqli_fetch_array($query);
      $total_bayar=$data["harga"]*$jumlah;
      $nama_barang=$data["nama"];
      $harga=$data["harga"];
      $id_pemiliktoko=$data["id_pemiliktoko"];

      $cek=mysqli_query($con,"select * from keranjang where id_barang='$id_barang' and id_pengguna='$id_pengguna'");
      $cek_ada=mysqli_fetch_array($cek);

      if($cek_ada){
      $total_awal=(int) $cek_ada["total_harga"];
      $jumlah_awal= (int) $cek_ada["jumlah"];
      $total_bayar_update=$total_bayar+$total_awal;
      $jumlah_update=$jumlah_awal+$jumlah;

      $tkeranjang=mysqli_query($con, "update keranjang set jumlah='$jumlah_update',total_harga='$total_bayar_update' where id_pengguna='$id_pengguna' and id_barang='$id_barang'");
        if (!$tkeranjang) {
          printf("Error: %s\n", mysqli_error($con));
          exit();
      }
      if ($tkeranjang) {  ?>
              <script type="text/javascript">
                alert("berhasil ditambahkan ke keranjang");
              window.location.href="../keranjang.php";
              </script>
              <?php
            }else{
              echo mysqli_error($con);
            }
      }else{
      $tkeranjang=mysqli_query($con, "insert into keranjang set id_pengguna='$id_pengguna',id_barang='$id_barang', id_pemiliktoko='$id_pemiliktoko', nama='$nama_barang',harga='$harga',jumlah='$jumlah',total_harga='$total_bayar'");
      if ($tkeranjang) {  ?>
              <script type="text/javascript">
                alert("berhasil ditambahkan ke keranjang");
              window.location.href="../keranjang.php";
              </script>
              <?php
            }else{
              echo mysqli_error($con);
            }
      }

    }
    if ($_GET["act"]=="hapus_keranjang") {
      $id_keranjang=$_POST['id_keranjang'];
      $con=mysqli_connect("localhost","root","","db_toko");
      $query=mysqli_query($con,"delete from keranjang where id_keranjang='$id_keranjang'");
      header("location:../keranjang.php");

    }
    if ($_GET["act"]=="ubah_keranjang") {
      $con=mysqli_connect("localhost","root","","db_toko");
       foreach($_POST['id_kerj'] as $key => $id){
       $id_kerj = $id;
       $jumlah = $_POST['jumlah'][$key];
       $harga= $_POST['harga'][$key];
       $total= $jumlah*$harga;
       $sql2 = "UPDATE keranjang SET jumlah = '$jumlah', total_harga='$total' where id_keranjang = '$id_kerj' ";
       $result2 = mysqli_query($con, $sql2) ;
       if (!$result2) {
          printf("Error: %s\n", mysqli_error($con));
          exit();
        }
      }
      header("location:../keranjang.php");
    }
  }

?>
