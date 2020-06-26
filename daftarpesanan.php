<!DOCTYPE html>
<?php 
Include('background/server.php');

  
  // if (empty($_SESSION['email'])) {
	 //  header('location: login.php');
  // }
  // if (isset($_GET['logout'])) {
  // 	session_destroy();
  // 	unset($_SESSION['email']);
  // }
?>

<html>
<head>
	<link rel="shortcut icon"  href="img/logo.png">
	<title>Skuy Apparel</title>
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link rel="stylesheet" type="text/css" href="css/keranjang.css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,600" rel="stylesheet"> 
</head>

<body>
	<!-- HEADER -->
	<header>
		<div>
			<a href="index.php"><img src="img/logo.png" class="logo"></a>
		</div>
		<div >
			<?php
				if (isset($_SESSION['email'])) {
					echo "<a href='login.php' style='display:none;'><img src='img/avatar.png' class='avatar' alt=''></a>";
					}
				else { echo "<a href='login.php'><img src='img/avatar.png' class='avatar' alt=''></a>";
				} 
			?>
		</div>
		<div id="navbar">
			<ul>
				<li class="aktif"><a href="index.php">Home</a></li>
				<li class="submenu">
					<a href="javascript:void(0)" class="subbtn">Catalogue</a>
					<div class="submenu-konten">
				      <?php echo '<a href="katalog.php?id=801">Pakaian Anak</a>';?></a>
				      <?php echo '<a href="katalog.php?id=802">Pakaian Wanita</a>';?></a>
				      <?php echo '<a href="katalog.php?id=803">Pakaian Pria</a>';?></a>
					</div>
				<li><a href="aboutus.php">About Us</a></li>
				<?php
				if (!isset($_SESSION['email'])) {
				  	echo "<li style='float:right'><a class='login' href='login.php'> Login </a></li>";
					}
				  	else{
						echo "<li style='float:right' class='submenu'><a class='subbtn' href='javascript:void(0)'>".$_SESSION['email']." </a>
							<div class='submenu-konten'>
								<a href='index.php?logout='1''>Logout</a> 
								<a href='daftarpesanan.php'>Pesanan Saya</a>
							</div>
						</li>";
				  		echo "<li style='float:right'><a class='keranjang' href='keranjang.php'> Keranjang </a></li>";
				  	}
				?>
			</ul>	
		</div>
	</header>
	<!-- ABIS HEADER -->

	<!-- FUNGSII -->
	
	<?php 
		// Include our login information
		require_once('background/db_login.php');
		// Connect
		$id=$_SESSION['id_pengguna'];
		$con = mysqli_connect($db_host, $db_username, $db_password,$db_database);
		if (mysqli_connect_errno()){
		die ("Could not connect to the database: <br />". mysqli_connect_error( ));
		}


	
	function tampil_transaksi($id){
		$con=mysqli_connect("localhost","root","","db_toko");
		$query=mysqli_query($con, "select * from transaksi where id_pengguna='$id'");
		
		while ($data=mysqli_fetch_array($query)) {
			
			$id_transaksi=$data['id_transaksi'];

			?>
			<tr>
				<td><?php echo $data['id_transaksi']; ?>
					<form method="POST" action="daftarpesanan.php">
						<input type="hidden" name="id_detail" value="<?= "$id_transaksi"?>">
						<button type="submit">Detail Transaksi</button>
					</form>
				</td>
				<td><?php echo $data['tanggal_transaksi']; ?></td>
				<td><?php echo $data['total_item'] ;?></td>
				<td><?php echo "Rp. ".number_format($data['total_harga'],0,',','.') ?></td>
				<td>

					<?php $query2=mysqli_query($con, "SELECT * from status_transaksi where id_transaksi='$id_transaksi' ORDER BY `tgl_update` DESC LIMIT 1");
					while ($data2=mysqli_fetch_array($query2)) {
						echo "".$data2['status'];
						if ($data2['status']=="Proses Pengiriman") {
					?>
					<form action="daftarpesanan.php" method="post">
						<input type="hidden" name="id_transaksi" value="<?= "$id_transaksi"?>">
						<input type="hidden" name="status" value="Barang Diterima">
						<button type="submit" class="" name="terimabarang">Konfirmasi Penerimaan</button>
					</form>
					<?php } elseif ($data2['status']=="Menunggu Pembayaran") { ?>
					<form action="form_konfirmasi.php" method="GET">
						<input type="hidden" name="id_transaksi" value="<?= "$id_transaksi"?>">
						<button type="submit" class="">Bayar</button>
					</form>
					<?php } elseif ($data2['status']=="Barang Diterima") { ?>
						<p>Barang Telah Diterima</p>
					<?php } 
						} 
					?>
				</td>
				
			</tr>
			<?php
		}
		
	}

	function tampil_detail($id){
		$con=mysqli_connect("localhost","root","","db_toko");
		$query=mysqli_query($con, "select * from detail_transaksi where id_transaksi='$id'");
		
		while ($data=mysqli_fetch_array($query)) {
			?>
			<tr>
				<td><?php echo $data['id_transaksi'] ?></td>
				<td><?php echo $data['id_barang'] ?></td>
				<td><?php echo $data['id_pemiliktoko'] ?></td>
				<td><?php echo $data['jumlah'] ?></td>
				<td><?php echo "Rp. ".number_format($data['harga'],0,',','.') ?></td>
				
			</tr>
			<?php
		}
		
	}
	
	?>
	<!-- ABISS FUNGSII -->

	<div class="container">
		<br><br>
	<?php 
		$query=mysqli_query($con, "select * from transaksi where id_pengguna='$id'");
        if (mysqli_num_rows($query) === 0) {
	?>
		<!-- <img src="img/cart_empty.jpg"> -->
		<a href="index.php" class="a">Anda Belum Melakukan Transaksi Apapun</a>
	<?php 
		}else{
		?>
		<table class="tb_customer">
			<tr>
				<th>Kode Transaksi</th>
				<th>Tanggal Transaksi</th>
				<th>Jumlah Barang</th>
				<th>Total Harga</th>
				<th>Status</th>
			</tr>
		<?php
			tampil_transaksi($id);} 
		?>
		</table>
	</div>

	<!-- Detail Transaksi -->
	<div class="container">
		<?php
		if (isset($_POST['id_detail'])) {
			$id_detail=$_POST['id_detail']; ?>
			
			<br><br>
				<table class="tb_customer">
					<tr>
						<th>Kode Transaksi</th>
						<th>Kode Barang</th>
						<th>Kode Pemilik Toko</th>
						<th>Jumlah Barang</th>
						<th>Harga Jumlah Barang</th>
					</tr>
					<?php
			tampil_detail($id_detail);
		}?>
				</table>
				
	</div>	
	
	<!-- FOOTER -->
	<div class="footer">© 2018 Skuy Teams. All Rights Reserved</div>
	<div class="clearfix"></div>
	<!-- ABIS FOOTER -->

</body>
</html>