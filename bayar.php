<?php 
	Include('background/server.php');

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
	<!-- Form Checkout -->
	<?php 
		// $id = $_GET['id'];

		$id=$_SESSION['id_pengguna'];
		if (isset($_POST['id_transaksi'])) {
		$id_transaksi=$_POST['id_transaksi'];
		}else{
			header("location:daftarpesanan.php");
		}
		
		// Include our login information
		require_once('background/db_login.php');
		// Connect
		$con = mysqli_connect($db_host, $db_username, $db_password,$db_database);
		if (mysqli_connect_errno()){
		die ("Could not connect to the database: <br />". mysqli_connect_error( ));
		}
				// get data from table keranjang

		$query1 = " select * from transaksi where id_transaksi='$id_transaksi' ";
		$result2= mysqli_query($con,$query1);
		
		$id_pengguna=$_SESSION['id_pengguna'];
		$biaya_kirim = 0;		
		
	// echo $_SESSION['id_pengguna'];
	
		$jumlah_semua=0;
		$jumlah_item=0;
		$total_harga=0;
		while ($data=mysqli_fetch_array($result2)) {
			$total_item=0;
			$total_harga= $data['total_harga']; 
			 } ?>
			 

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

	<!-- Form -->
  	<div class="formcheckout">
	  <br />
			<div class="Judul_Check">
				<h2>Konfirmasi Pembayaran</h2>
			</div>

			<form method="POST" action="bayar.php" enctype="multipart/form-data">
			<?php include('background/errors.php'); ?>
			<div class="form_check">
				<div class="checkout">
					<label>Nama Pengirim</label>
					<div class="input">	
						<input type="text" name="nama" placeholder="Masukkan Nama Pengirim" required="required">
					</div>
				</div>

				<div class="checkout">
					<label>Bank</label>
					<div class="input">
						<select name="bank" required="required">
							<option>Select Bank</option>
							<option value="BCA">BCA</option>
							<option value="BRI">BRI</option>
							<option value="Mandiri">Mandiri</option>
							<option value="BNI">BTN</option>
							<option value="BTN">BTN</option>
						</select>
					</div>
				</div>
				
				<div class="checkout">
					<label>Nomer Rekening</label>
					<div class="input">
						<input type="text" name="norek" placeholder="Masukkan Nomer Rekening" required="required">
					</div>
				</div>
				
				<div class="checkout">
					<label>Tanggal Transfer</label>
					<div class="input">
						<input name="tanggal_tf" type="date" required="required">
					</div>
				</div>

				<div class="checkout">
					<label>Total Pembayaran</label>
						<hr width="80%">
						<table width="88%">
						<tr>
							<?php echo '<td align="right">Rp.'.number_format($total_harga,0,',','.').'</td>'; ?>
						</tr>
					</table>

				</div>

				<div class="checkout">
					<label>Bukti Transfer</label>
					<div class="input">
							<input type="file" name="file" required="required" id="file" >
							
					</div>
				</div>

				<div>
					<input type="hidden" name="id_transaksi" value="<?= "$id_transaksi"?>">
					<input type="hidden" name="total" value="<?= "$total_harga"?>">
					<br />
				</div>
				
		</div>
		<div>
		<br /><br />
		</div>
		<div class="checkout">
					<button type="submit" class="btn" name="konfirmasi">Konfirmasi</button>
				</div>
    </div>

</body>
</html>

<script>
	var today = new Date().toISOString().split('T')[0];
	document.getElementsByName("tanggal_tf")[0].setAttribute('min', today);
</script>