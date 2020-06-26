<?php include('background/server.php');
 if (isset($_SESSION['email'])) {
	  header('location: index.php');
  }
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon"  href="img/logo.png">
  	<title>Login User</title>
  	<link rel="stylesheet" type="text/css" href="css/style.css">
  	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,600" rel="stylesheet">
</head>
<body style="background-image: url(img/bg.jpg);">

<!-- HEADER -->
<header>
	<div >
		<a href="index.php"><img src="img/logo.png" class="logo"></a>
	</div>
	<div >
		<a href="index.php"><img src="img/avatar.png" class="avatar"></a>
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
						</div>
					</li>";
				}
			?>	
		</ul>	
	</div>
</header>

	<div class="loginbox">
		<div class="header">
			<h2>Login Here</h2>
		</div>

		<form method="POST" action="login.php">
			<?php include('background/errors.php'); ?>

			<div class="input-group">
				<label>Email</label>
				<input type="text" name="email" placeholder="Enter Your Email">
			</div>

			<div class="input-group">
				<label>Password</label>
				<input type="password" name="password" placeholder="Enter Your Password">
			</div>
			
			<div class="input-group">
				<button type="submit" class="btn" name="login_user">Login</button>
			</div>

			<div>
			<p>
				Not yet a member? <a href="register.php">Sign up</a>
			</p>
			</div>
		</form>

	</div>
</body>
</html>