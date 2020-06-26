<?php 
  session_start(); 
  
  if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['email']);

	header("location: login.php");
  }
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon"  href="img/logo.png">
	<title>Skuy Apparel</title>
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<!-- <link rel="stylesheet" type="text/css" href="css/navbar.css"> -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,600" rel="stylesheet"> 
</head>
<body>
	<?php
		// Include our login information
		require_once('background/db_login.php');
		// Connect
		$con = mysqli_connect($db_host, $db_username, $db_password,$db_database);
		if (mysqli_connect_errno()){
		die ("Could not connect to the database: <br />". mysqli_connect_error( ));
		}
	//Asign a query
		$query1 = " SELECT * FROM barang ";
		$query2 = " SELECT * FROM kategori";
		
	// Execute the query
		$result1 = mysqli_query($con,$query1);
		$result2 = mysqli_query($con,$query2);
		if (!$result1){
			die ("Could not query2 the database: <br />". mysqli_error($con));	
		} 
		if (!$result2){
			die ("Could not query2 the database: <br />". mysqli_error($con));	
		} 
	 ?>

	<!-- HEADER -->
	<header>
		<div >
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
				<li><a href="">About Us</a></li>
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
	<!-- Footer -->

	<div class="container">
		<h3>Nama Anggota</h3>

		<ul class="gallery" type="none">
			<li >
				<img src="img/anu1.jpg" alt="anu">
				<span>Yuli Nurhayati</span>
			</li>

			<li >	
				<img src="img/anu2.jpg" alt="anu">
				<span>M Al Amin</span>
			</li>

			<li >
				<img src="img/anu3.jpg" alt="anu">
				<span>M Ivan Hanif</span>
			</li>

			<li >
				<img src="img/anu4.jpg" alt="anu">
				<span>Urratul Aqyuni</span>
			</li>

			<div class="clear"></div>
		</ul>
		
	</div>
			

	<footer class="footer-aboutus">© 2018 Skuy Teams. All Rights Reserved</footer>
	<div class="clearfix"></div>

</body>
</html>