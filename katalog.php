<?php 
  session_start(); 
  
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  }
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon"  href="img/logo.png">
	<title>Skuy Apparel</title>
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link rel="stylesheet" type="text/css" href="css/all_katalog.css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,600" rel="stylesheet"> 
	
</head>
<body>
	<?php
		$id = $_GET['id'];
		// Include our login information
		require_once('background/db_login.php');
		// Connect
		$con = mysqli_connect($db_host, $db_username, $db_password,$db_database);
		if (mysqli_connect_errno()){
		die ("Could not connect to the database: <br />". mysqli_connect_error( ));
		}
	//Asign a query
		$query = " SELECT * FROM barang WHERE id_kategori='$id'";
	// Execute the query
		$result = mysqli_query($con,$query);
		if (!$result){
			die ("Could not query the database: <br />". mysqli_error($con));	
		} 
	 ?>
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

	<!-- SLIDER-->
	<div class="slider">
		<img src="img/slider.jpg">
		<div class="lapisan">
			<div class="teks">Skuy Apparel</div>
		</div>
	</div>
	<div class="batas">ORDER NOW</div>

	<div class="katalog">
	<?php while ($row = mysqli_fetch_array($result)){ ?>
		<div class="product_one">
			<div class="product_img">
				<?php echo '<a href="detail_katalog.php?id='.$row['id_barang'].'"><img alt="Dress 1" src='.$row['gambar'].'> </a>';?>
			</div>

			<div class="product_text">
				<?php echo '<a href="detail_katalog.php?id='.$row['id_barang'].'"><h4>'.$row['nama'].'</h4></a>';?>
			</div>

			<div class="product_price">
				<div class="price">
					<?php echo ''.$row['harga'].''; ?>
				</div>
			</div>

			<div class="product_desc">
				<?php echo '<a href="detail_katalog.php?id='.$row['id_barang'].'"><h4>'.$row['deskripsi'].'</h4></a>';?>
			</div>
		</div>
	<?php } ?>
	</div> 
	<div class="footer-katalog" >© 2018 Skuy Teams. All Rights Reserved</div>
	<div class="clearfix"></div>


</body>
</html>