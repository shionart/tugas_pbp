<?php 
  include 'background/server.php';
  
  if (empty($_SESSION['email'])) {
	  header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['email']);
  	unset($_SESSION['id_pengguna']);
  }
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon"  href="img/logo.png">
	<title>Skuy Apparel</title>
	<link rel="stylesheet" type="text/css" href="css/index.css">
<!-- 	<link rel="stylesheet" type="text/css" href="css/keranjang.css"> -->
	<link rel="stylesheet" type="text/css" href="css/checkout.css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,600" rel="stylesheet"> 
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
		$con = mysqli_connect($db_host, $db_username, $db_password,$db_database);
		if (mysqli_connect_errno()){
		die ("Could not connect to the database: <br />". mysqli_connect_error( ));
		}

	?>
	<!-- ABISS FUNGSII -->


	
		<br><br>
	<?php 
		$id_cust=$_SESSION['id_pengguna'];
		$con=$db;
		$query=mysqli_query($con, "select * from keranjang where id_pengguna='$id_cust'");
        if (mysqli_num_rows($query) === 0) {
	?>
		<div style="margin-left:38%;">
		<img src="img/empty.png">
		<br/>
		<h2>Keranjang anda masih kosong</h2><br/>
		</div>
		<button class="btn" type="submit" onclick=location.href="index.php">Ayo lanjut belanja</button>
		
	<?php 
		}else{
	?>
		<?php 
		if (isset($_GET["act"])) {
			if ($_GET["act"]=="tampil_ubah_keranjang") {
			$id_cust=$_POST['id_pengguna'];
			$con=$db;
			$query = mysqli_query($con,"SELECT DISTINCT pemilik_toko.id_pemiliktoko, pemilik_toko.nama_toko from pemilik_toko, barang, keranjang where keranjang.id_pengguna=$id_cust and keranjang.id_barang=barang.id_barang and barang.id_pemiliktoko=pemilik_toko.id_pemiliktoko  ");
			?>
			<div class="judul_tampilpes">
				<h3><img src="img/location.png">Ubah Jumlah Barang</h3>
			</div>
			<?php
			while ($data=mysqli_fetch_array($query)) {
			?>
			<div class="tampil_pesanan">
			<br />
			<?php echo '<p> Penjual : '.$data['nama_toko'].'</p>'; 
				?> 
			
				<?php $query2 = mysqli_query($con,"SELECT * from (keranjang join pemilik_toko using(id_pemiliktoko)) join barang using(id_barang) where keranjang.id_pengguna='$id_cust' "); 
				while ($data2=mysqli_fetch_array($query2)) {
					if($data['id_pemiliktoko']==$data2['id_pemiliktoko']){
				 ?>
				<table>	
				<tr>
					<?php echo '<td width="30%"><img src="'.$data2['gambar'].'"></td>'; ?> 	
					<?php echo '<td width="20%" text-align="left">'.$data2['nama'].''; ?> <br>	
					<?php echo 'Rp. '.$data2['harga'].''; ?> <br>	
					<?php echo 'Berat : '.$data2['berat'].''; 
					$jml=$data2['jumlah'];
					?>
					<br>
				<form action="background/server.php?act=ubah_keranjang" method="post">
				<td width="20%" align="left">
					<p>Jumlah :</p>
					<input type="number" min="1" name="jumlah[<?php  $data2['jumlah']; ?>]" value="<?php echo $data2['jumlah']; ?>">
					<input type="hidden" name="id_kerj[<?php  $data2['id_keranjang']; ?>]" value="<?php echo $data2['id_keranjang']; ?>">
					<input type="hidden" name="harga[<?php  $data2['harga']; ?>]" value="<?php echo $data2['harga']; ?>">
				</td>
				</tr>

				<tr></tr><tr></tr>

			</table>	
			<?php }} ?>
			</div>
			<?php
			}
			?>		
					<br><br>
					<button class="btn" type="submit" class="">Simpan</button>
					</form>		
			<?php
				}
		}

		if(!isset($_GET["act"])){
			$id_cust=$_SESSION['id_pengguna'];
			$con=$db;
			$query = mysqli_query($con,"SELECT DISTINCT pemilik_toko.id_pemiliktoko, pemilik_toko.nama_toko from pemilik_toko, barang, keranjang where keranjang.id_pengguna=$id_cust and keranjang.id_barang=barang.id_barang and barang.id_pemiliktoko=pemilik_toko.id_pemiliktoko  ");
			$total_harga=0;

			?>
			<div class="judul_tampilpes">
				<h3><img src="img/location.png">Keranjang</h3>
			</div>
			<?php
			while ($data=mysqli_fetch_array($query)) {
			?>
			<div class="tampil_pesanan">
			<br />
			<?php echo '<p> Penjual : '.$data['nama_toko'].'</p>'; 
				?> 
			
				<?php $query2 = mysqli_query($con,"SELECT * from (keranjang join pemilik_toko using(id_pemiliktoko)) join barang using(id_barang) where keranjang.id_pengguna='$id_cust' "); 
				       if (!$query2) {
         		 printf("Error: %s\n", mysqli_error($con));
          		exit();
        			}
				while ($data2=mysqli_fetch_array($query2)) {
					if($data['id_pemiliktoko']==$data2['id_pemiliktoko']){
				 ?>
				<table>	
				<tr>
					<?php echo '<td width="30%"><img src="'.$data2['gambar'].'"></td>'; ?> 	
					<?php echo '<td width="20%" text-align="left">'.$data2['nama'].''; ?> <br>	
					<?php echo 'Rp. '.$data2['harga'].''; ?> <br>	
					<?php echo 'Berat : '.$data2['berat'].''; ?>	<br>
					<?php echo 'Jumlah : '.$data2['jumlah'].'</td>'; 
					$total_harga= $total_harga + $data2['harga']*$data2['jumlah'];	
					$id_kerj=$data2['id_keranjang'];
					?>
				<td width="20%" align="center">
					<form action="background/server.php?act=hapus_keranjang" method="post">
						<input type="hidden" name="id_keranjang" value="<?= "$id_kerj" ?>" />
						<button type="submit" style="font-size:24px"><i class="fa fa-trash-o"></i></button>
					</form>
				</td>
				</tr>
			</table>	

			<hr width="80%" align="left">	
			<table width="88%">	
				<tr>	
					<td>Harga Barang :</td>
					<?php echo '<td align="right">Rp. '.number_format($data2['harga']*$data2['jumlah'],0,',','.').'</td>'; ?>
				</tr>

			<?php }} ?>
				<tr>
					<td><h4>Subtotal</h4></td>
					<?php echo '<td align="right">Rp. '.number_format($total_harga,0,',','.').'</td>'; 
					$total_harga=0;?>
				</tr>
			</table>
			
		</div>
		<?php
		}
		?>
		<div class="judul_tampilpes">
			<h3><img src="img/location.png">Jumlah Pembayaran</h3>
		</div>
		<div class="ringkasan_pembelian">
			<br>
			<table width="88%">
			<tr >
			<?php 
			$id=$_SESSION['id_pengguna'];
			$sum=mysqli_query($con, "select sum( total_harga ) as total_harga FROM keranjang where id_pengguna='$id'");
			$sum_array=mysqli_fetch_array($sum);
			 ?>
			 	<td><h4>Total :<h4></td>
				<?php echo '<td align="right">Rp. '.number_format($sum_array['total_harga'],0,',','.').'</td>'; ?>
			</tr>
			</table>
			</div>
			<br>
			<form action="keranjang.php?act=tampil_ubah_keranjang" method="post">
				<input type="hidden" name="id_pengguna" value="<?= "$id" ?>" />
				<button class="btn" align="center" type="submit" class="">Ubah Jumlah</button>
			</form>
			<br>
		</div>
			<?php echo '<button class="btn" type="submit" onclick=location.href="checkout.php?id='.$_SESSION['id_pengguna'].'">Checkout</button>';?>
		<?php
			} 
		}
		?>



	<!-- FOOTER -->
	<div class="footer-katalog">© 2018 Skuy Teams. All Rights Reserved</div>
	<div class="clearfix"></div>
	<!-- ABIS FOOTER -->

</body>
</html>