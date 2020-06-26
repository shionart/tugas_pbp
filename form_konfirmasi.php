<?php 
	session_start();

	if (empty($_SESSION['email'])) {
		header('location: login.php');
	}

	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['username']);
	}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon"  href="img/logo.png">
    <title>Skuy Apparel</title>
    <link rel="stylesheet" type="text/css" href="css/dtl_katalog.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/checkout.css">
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

  
	<!-- Form Checkout -->
	<?php 
		// $id = $_GET['id'];
		$id_transaksi=$_GET['id_transaksi'];
		$id=$_SESSION['id_pengguna'];
		// Include our login information
		require_once('background/db_login.php');
		// Connect
		$con = mysqli_connect($db_host, $db_username, $db_password,$db_database);
		if (mysqli_connect_errno()){
		die ("Could not connect to the database: <br />". mysqli_connect_error( ));
		}
		// get data from table keranjang
		$query = mysqli_query($con,"SELECT * from transaksi where id_transaksi='$id_transaksi'");

		if (!$query) {
		    printf("Error: %s\n", mysqli_error($con));
		    exit();
		}
		
		
		// $tanggal_transaksi = date("Y-m-d");
		// get data sum harga total & jumlah total
		
		$id_pengguna=$_SESSION['id_pengguna'];
		$biaya_kirim = 0;		
		
	// echo $_SESSION['id_pengguna'];
	?>
	<?php  "".mysqli_num_rows($query);
		$jumlah_semua=0;
		$jumlah_item=0;
		$total_harga=0;
		while ($data=mysqli_fetch_array($query)) {
			$total_harga=$data['total_harga'];
				} ?>

  	<h1 style="text-align:center; color:#1c468f;">Transfer ke</h1>
  
  	<div class="ringkasan_pembelian" style="text-align:center;">
		<br /><h2>Nomer Rekening</h2>

		<p style="color:#1c468f;">32551299876622 (BCA)<br />
			32551299876624 (BRI)<br />
			32551299876625 (Mandiri)<br />
			32551299876626 (BNI)<br />
			32551299876627 (BTN)<br /></p>
			
		<h2>Atas nama</h2>
		
		<p>Skuy Apparel</p><br />
  	</div>

	<div class="ringkasan_pembelian">
		<br />
		<table width="88%">
			<tr>
				<td>Total Harga</td>
				<?php echo '<td align="right">Rp.'.number_format($total_harga,0,',','.').'</td>'; ?>
				<!-- <td align="right">Rp. Total Harga</td> -->
			</tr>
			<tr>
				<td>Total Ongkos Kirim</td>
				<td align="right">Rp. 0,-</td>
			</tr>
		</table>
		<br /> <br />
		<hr width="80%" align="center">
		<table width="88%">
			<tr>
				<td><h4>Total Pembelian</h4></td>
				<?php echo '<td align="right">Rp.'.number_format($total_harga,0,',','.').'</td>'; ?>
			</tr>
		</table>
	</div>
	<br/>
	
	<div class="button_checkout">
						<form action="bayar.php" method="POST">
				<input type="hidden" name="id_transaksi" value="<?= "$id_transaksi"?>">
				<button type="submit"  class="btn" name="Bayar">Bayar</button>
				</form>
		</div>
		<div class="footer">© 2018 Skuy Teams. All Rights Reserved</div>
		<div class="clearfix"></div>
	</body>
</html>
