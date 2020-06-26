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
	<link rel="shortcut icon"  href="img/logo.png">
	<title>Skuy Apparel</title>
	<link rel="stylesheet" type="text/css" href="css/dtl_katalog.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link rel="stylesheet" type="text/css" href="css/checkout.css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,600" rel="stylesheet"><script type="text/javascript">
		function myFunction{
			var expendImg=document.getElementByID("expendImg");
			expendImg.src=img.src;
			expendImg.parentElement.style.display="block";
		}
	</script> 
	
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
		$id=$_SESSION['id_pengguna'];
		// Include our login information
		require_once('background/db_login.php');
		// Connect
		$con = mysqli_connect($db_host, $db_username, $db_password,$db_database);
		if (mysqli_connect_errno()){
		die ("Could not connect to the database: <br />". mysqli_connect_error( ));
		}
		// get data from table keranjang
		$query = mysqli_query($con,"SELECT DISTINCT pemilik_toko.id_pemiliktoko, pemilik_toko.nama_toko from pemilik_toko, barang, keranjang where keranjang.id_pengguna=$id and keranjang.id_barang=barang.id_barang and barang.id_pemiliktoko=pemilik_toko.id_pemiliktoko  ");

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

	<!-- Form -->
	<div class="formcheckout">
		<div class="Judul_Check">	
		 	<h2>Checkout</h2>
			<h3><img src="img/location.png"> Alamat Pengiriman </h3>
		</div>
		<div class="form_check">
			<form method="POST" action="checkout.php">
				<?php include('background/errors.php'); ?>
				<?php 
					$link=mysqli_connect('localhost', 'root','');
					mysqli_select_db($link,'db_toko');
			 	?>
				<div class="checkout">
					<br>	
					<label>Provinsi</label><br>	
					<div class="input">	
						<select class="#" name="province" id="provincedd" onchange="change_province()">
							<option value="">Select Province</option>
							<?php 
							$result = mysqli_query($link, "select * from provinsi ORDER BY nama");
							while ($row = mysqli_fetch_array($result)) {  ?>
								<option value="<?php echo $row['id_provinsi']; ?>"><?php echo $row['nama'];?></option>
								<?php } ?>
						</select>
					</div>
				</div>
				<div class="checkout">
					<label>Kota/Kabupaten</label><br>	
					<div class="input" id="citydd">
						<select class="#" name="city">
							<option value="">Select City</option>
						</select>
					</div>
				</div>
				<div class="checkout">
					<label>Alamat</label> <br>	
					<div class="input">	
						<textarea name="alamat" rows="4" cols="50"  placeholder="Input Your Address"></textarea>
					</div>
				</div>
				<div class="checkout">
					<label>Pos code</label><br>	
					<div class="input">	
						<input type="text" name="kodepos" pattern="[0-9]{5,}" title="Must input 5 numbers" placeholder="Input your Pos Code">
					</div>
					<br />
				</div>
		</div>
	</div>
	<div class="judul_tampilpes">
		<h3><img src="img/location.png">Pembelian Barang</h3>
	</div>

		<?php  echo "".mysqli_num_rows($query);
		$jumlah_semua=0;
		$jumlah_item=0;
		$total_harga=0;
		while ($data=mysqli_fetch_array($query)) {
			$jumlah_toko=0;
			$total_item=0;
			?>
			<div class="tampil_pesanan">
			<br />
			<?php echo '<p> Penjual : '.$data['nama_toko'].'</p>'; 
				?> 
			
				<?php $query2 = mysqli_query($con,"SELECT * from (keranjang join pemilik_toko using(id_pemiliktoko)) join barang using(id_barang) where keranjang.id_pengguna='$id' "); 
				    if (!$query2) {
       				   printf("Error: %s\n", mysqli_error($con));
       				   exit();
  				     }
					while ($data2=mysqli_fetch_array($query2)) {
					if($data['id_pemiliktoko']==$data2['id_pemiliktoko']){
					?>
					<table>	
						<tr>
							<br>
					<?php echo '<td width:"20px"><img src="'.$data2['gambar'].'"></td>'; ?> 	
					<?php echo '<td>'.$data2['nama'].''; ?> <br>	
					<?php echo 'Rp. '.$data2['harga'].''; ?> <br>	
					<?php echo 'Berat : '.$data2['berat'].''; ?>	<br>
					<?php echo 'Jumlah : '.$data2['jumlah'].'</td>'; 
					$total_harga= $total_harga + $data2['harga']*$data2['jumlah']; 
					$total_item=$total_item + $data2['jumlah'];
					$total_harga_perbarang = $data2['harga']*$data2['jumlah'];
					$total_item_perbarang = $data2['jumlah'];

					?>	

					<input type="hidden" name="id_item[<?php  $data2['id_barang'];?>]" value="<?php echo $data2['id_barang']; ?>">
					<input type="hidden" name="id_own[<?php  $data2['id_pemiliktoko']; ?>]" value="<?php echo $data2['id_pemiliktoko']; ?>">
					<input type="hidden" name="total_harga_perbarang[<?php  $total_harga_perbarang;?>]" value="<?php echo $total_harga_perbarang; ?>">
					<input type="hidden" name="total_item_perbarang[<?php  $total_item_perbarang; ?>]" value="<?php echo $total_item_perbarang; ?>">

						</tr>
					</table>	
			<hr width="80%" align="left">	
			<table width="88%">	

				<tr>	
					<td>Harga Barang :</td>
					<?php echo '<td align="right">Rp. '.$data2['harga']*$data2['jumlah'].'</td>'; ?>
				</tr>

				<?php }} ?>
				<tr>
					<td>
						Kurir Pengiriman
						<select required>
							<option value="">Pilih Kurir Pengiriman</option>
							<option value="JNE Reguler">JNE Reguler</option>
							<option value="JNE YES">JNE YES</option>
							<option value="JNT">JNT</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Ongkos Kirim  :</td>
					<td align="right">Rp.0,- (gratis ongkir)</td>
				</tr>
				<tr>
					<td><h4>Subtotal</h4></td>
					<?php echo '<td align="right">Rp. '.$total_harga.'(jumlah :'.$total_item.')</td>'; 
					$jumlah_semua=$jumlah_semua + $total_harga;
					$jumlah_item =$jumlah_item + $total_item;
					$total_harga=0;
					?>
				</tr>
			</table>
		</div>
	<?php 
		}
	 ?>
	<div class="judul_ringkasan">
		<h3><img src="img/location.png">Ringkasan Belanja</h3>
	</div>
	<div class="ringkasan_pembelian">
		<br />
		<table width="88%">
			<tr>
				<td>Total Harga</td>
				<?php echo '<td align="right">Rp.'.number_format($jumlah_semua,0,',','.').'</td>'; ?>
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
				<?php echo '<td align="right">Rp.'.number_format($jumlah_semua,0,',','.').'</td>'; ?>
			</tr>
		</table>
	</div>
	<br/>
	<div class="button_checkout">
			<button type="submit" class="btn" name="checkout_usr">Checkout</button>
	</div>
		<input type="hidden" name="id_pengguna" value="<?= "$id_pengguna"?>">
		<input type="hidden" name="biaya_kirim" value="<?= "$biaya_kirim"?>">
		<input type="hidden" name="total_harga" value="<?= "$jumlah_semua"?>">
		<input type="hidden" name="total_item" value="<?= "$jumlah_item"?>">
	</form>
		<div class="footer-katalog">© 2018 Skuy Teams. All Rights Reserved</div>
		<div class="clearfix"></div>
		<?php 
		?>

</body>
</html>

<script type="text/javascript">
	function change_province(){
		var xmlhttp= new XMLHttpRequest();
		xmlhttp.open("GET","background/ajax.php?province="+document.getElementById("provincedd").value,false);
		xmlhttp.send(null);
		document.getElementById("citydd").innerHTML=xmlhttp.responseText;
	}
</script>