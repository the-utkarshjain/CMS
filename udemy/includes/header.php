<?php  
require 'config/config.php';


if (isset($_SESSION['username'])) {
	$userLoggedIn = $_SESSION['username'];
	$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
	$user = mysqli_fetch_array($user_details_query);
}
else {
	header("Location: register.php");
}

?>

<html>
<head>
	<title>Welcome to Swirlfeed</title>

	<!-- Javascript -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>

	<!-- CSS -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">

</head>
<body>

	<div class="top_bar navbar navbar-inverse"> 

		<div class="logo navbar-brand">
			<a href="index.php">SCRI | IIT Mandi</a>
		</div>

		<ul class="nav navbar-nav navbar-right">
			<li><a href="#"><?php echo $_SESSION["username"]; ?></a></li>
      <!-- <li><a href="#"><i class="far fa-envelope"></i></a></li>
      <li><a href="#"><i class="fas fa-home"></i></a></li>
      <li><a href="#"><i class="far fa-bell"></i></a></li>
      <li><a href="#"><i class="fas fa-users"></i></a></li>
      <li><a href="#"><i class="fas fa-cog"></i></a></li> -->
      <li><a href="includes/handlers/logout.php">Logout</a></li>
		</ul>

	</div>


	<div class="wrapper">