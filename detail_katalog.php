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
	<link rel="stylesheet" type="text/css" href="css/dtl_katalog.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,600" rel="stylesheet"><script type="text/javascript">
		function myFunction{
			var expendImg=document.getElementByID("expendImg");
			expendImg.src=img.src;
			expendImg.parentElement.style.display="block";
		}
	</script> 
	
</head>
<body>
<?php 
	$id = $_GET['id'];
	require_once('background/db_login.php');
	$db= new mysqli($db_host, $db_username, $db_password, $db_database);
	if($db->connect_errno){
		die("Could not connect to the database : <br />". $db->connect_error);
	}
	$query  =  "SELECT * FROM barang WHERE id_barang='$id' ";
	$result = $db->query($query);
	if(!$result){
		die("Could not query the database: <br />". $db->error);
	}else{ 
		$row = $result->fetch_object();
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

	<div class="detail">
	<table>
		<tr>
			<td>
			<div class="container_image">
				<div class="image">
					<?php echo '<img alt="dress" src="'.$row->gambar.'">'; ?>
					<!-- <img src="img/dress2.jpg" alt="gambar1" style="width: 100%" onclick="myFunction(this);"> -->
				</div>
			</div>
			<div class="border" ></div>
			</td>
			<td>
			<div class="container_desc">
				<div class="judul_produk">
					<?php echo '<h2>'.$row->nama.'</h2>'; ?>
					<!-- <h2>Judul Produk</h2> -->
				</div>
				<div class="harga">
					<?php echo '<h2>Rp.'.$row->harga.'</h2>'; ?>
					<!-- <h2>Rp. Harga</h2> -->
				</div>
				<div class="deksripsi">
					<?php echo '<h5>'.$row->deskripsi.'</h5>'; ?>
				</div >
				<?php 
						if (isset($_SESSION['email'])) {
							$id_pengguna=$_SESSION['id_pengguna'];
							?>
								<form class="jml_barang" action="background/server.php?act=tambah_keranjang" method="post">
									<input type="hidden" name="id_barang" value="<?= "$id" ?>" />
									<input type="hidden" name="id_pengguna" value="<?= "$id_pengguna" ?>" />
									<input type="number" min="1" name="jumlah" placeholder="jumlah">
									<button type="submit">Tambah ke keranjang</button>
								</form>
							<?php		
						}else{
							?>
								<a href="login.php">Login</a>
							<?php
						} 
				?>
			</div>
			</td>
		</tr>
	</table>

	</div>

		<div class="footer">© 2018 Skuy Teams. All Rights Reserved</div>
		<div class="clearfix"></div>


</body>
</html>