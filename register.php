<?php include('background/server.php');
  if (isset($_SESSION['email'])) {
    header('location: index.php');
  }
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon"  href="img/logo.png">
  <title>Registration User</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/index.css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,600" rel="stylesheet">
</head>
<body style="background-image: url(img/bg.jpg); margin-bottom: 20px;">
   	<div>
    	<a href="index.php"><img src="img/logo.png" class="logo"></a>
		</div>
		<div>
			<a href="login.php"><img src="img/avatar.png" class="avatar" alt=""></a>
	</div>
  <div id="navbar">
      <ul>
        <li class="aktif"><a href="index.php">Home</a></li>
        <li class="submenu">
          <a href="javascript:void(0)" class="subbtn">Catalogue</a>
          <div class="submenu-konten">
              <a href="katalog.php?id=801">Pakaian Anak</a>
              <a href="katalog.php?id=802">Pakaian Wanita</a>
              <a href="katalog.php?id=803">Pakaian Pria</a>
          </div>
        <li><a href="aboutus.php">About Us</a></li>
        <?php
        if (!isset($_SESSION['username'])) {
            echo "<li style='float:right'><a class='login' href='login.php'> Login </a></li>";
          }
          else{
            echo "<li style='float:right'><a class='login' href='logged/logged_status.php'> Account </a></li>";
          }
          ?>  
      </ul> 
    </div>
  <div class="header">
  	<h2>Register</h2>
  </div>
	
  <form method="post" action="register.php">
  	<?php include('background/errors.php'); ?>

  	<div class="input-group">
  	  <label>Name</label>
  	  <input type="text" name="username" placeholder="Input Your Name"  value="<?php echo $username; ?>">
  	</div>

  	<div class="input-group">
  	  <label>Email</label>
  	  <input type="email" name="email" placeholder="Input Your Email" value="<?php echo $email; ?>">
  	</div>

		<div class="input-group">
			<label>Gender</label>
			<input type="radio" name="gender" value="Male"> Male
      <input type="radio" name="gender" value="Female"> Female
  	</div>

		<div class="input-group">
			<label>Address</label>
    	<textarea name="address" placeholder="Input Your Address"></textarea>
		</div>

		<div>
			<?php
					require_once('background/db_login.php');
    // Connect
    $link = mysqli_connect($db_host, $db_username, $db_password,$db_database);
			?>

			<div class="input-group">
				<label>Province</label>
					<select class="styled-select blue semi-square" name="province" id="provincedd" onChange="change_province()">
							<option>Select Province</option>
							<?php
									$res = mysqli_query($link, "select * from provinsi ORDER BY nama");
									while($row = mysqli_fetch_array($res)) {
									?>
									<option value="<?php echo $row['id_provinsi']; ?>"><?php echo $row['nama']; ?></option>
									<?php 
									}
							?>
					</select>
			</div>

			<div class="input-group" >
				<label>City</label>
					<div id="citydd">
						<select class="styled-select blue semi-square" name="city">
								<option>Select City</option>
						</select>
					</div>
			</div>
		</div>

		<div class="input-group">
  	  <label>Pos Code</label>
  	  <input type="text" name="poscode" pattern="[0-9]{5,}" title="Must input 5 numbers" placeholder="Input Your Pos Code" >
  	</div>

  	<div class="input-group">
  	  <label>Password</label>
  	  <input type="password" name="password_1" pattern="[a-zA-Z0-9]{8,16}" title="Must contain at least one number and at least 8 or more characters" placeholder="Input Your Password">
  	</div>

  	<div class="input-group">
  	  <label>Confirm password</label>
  	  <input type="password" name="password_2" placeholder="Re-input Your Password">
  	</div>

  	<div class="input-group">
  	  <button type="submit" class="btn" name="reg_user">Register</button>
  	</div>

  	<p>
  		Already a member? <a href="login.php">Sign in</a>
  	</p>
  </form>
</body>
</html>

<script type="text/javascript">
    function change_province() {
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.open("GET","background/ajax.php?province="+document.getElementById("provincedd").value,false);
        xmlhttp.send(null);
        document.getElementById("citydd").innerHTML=xmlhttp.responseText;
    }
</script>