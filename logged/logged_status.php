<?php 
  session_start(); 

  if (!isset($_SESSION['email'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: ../login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['email']);
  	header("location: ../login.php");
  }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
  <link rel="stylesheet" type="text/css" href="../css/navbar.css">
  <link rel="stylesheet" type="text/css" href="../css/logo.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600" rel="stylesheet">
</head>
<body>
      <div id="logo">
    <a href="index.php"><img src="../img/logo.png"></a>
    </div>
<div id="navbar">
      <ul>
        <li class="aktif"><a href="../index.php">Home</a></li>
        <li class="submenu">
          <a href="javascript:void(0)" class="subbtn">Catalogue</a>
          <div class="submenu-konten">
              <?php echo '<a href="../katalog.php?id=801">Pakaian</a>';?></a>
              <?php echo '<a href="../katalog.php?id=802">Alat Elektronik</a>';?></a>
              <?php echo '<a href="../katalog.php?id=803">Makanan</a>';?></a>
          </div>
        <li><a href="">About Us</a></li>
        <?php
        if (!isset($_SESSION['email'])) {
            echo "<li style='float:right'><a class='login' href='../login.php'> Login </a></li>";
          }
          else{
            echo "<li style='float:right'><a class='login' href='logged_status.php'> Account </a></li>";
            echo "<li style='float:right'><a class='keranjang' href='../keranjang.php'> Keranjang </a></li>";
          }
          ?>  
      </ul> 
    </div>
<div class="header">
	<h2>Home Page</h2>
</div>
<div class="content">
  	<!-- notification message -->
  	<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>

    <!-- logged in user information -->
    <?php  if (isset($_SESSION['email'])) : ?>
    	<p>Welcome <strong><?php echo $_SESSION['email']; ?></strong></p>
    	<p> <a href="../index.php?logout='1'" style="color: red;">logout</a> </p>
    <?php endif ?>
</div>
		
</body>
</html>