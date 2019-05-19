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
	<title>SCRI | IIT Mandi</title>
	<link href="favicon.png" rel="icon" type="image">
  	<link href="favicon.png" rel="apple-touch-icon" type="image">

	<!-- Javascript -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/bootbox.min.js"></script>

	<!-- CSS -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"> -->
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">


	<!-- FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700|Raleway:300,400,400i,500,500i,700,800,900" rel="stylesheet">

</head>
<body>

	<nav class="navbar">
    <div id="sticker" class="header-area">
      <div class="container">
        <div class="row">
          <div class="col-md-12 col-sm-12">
            <nav class="navbar navbar-default">
              <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-example" aria-expanded="false">
										<span class="sr-only">Toggle navigation</span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
									</button>
                <a class="navbar-brand page-scroll sticky-logo" href="https://scri.iitmandi.ac.in/">
                  <h1><span>SCRI</span> | IIT Mandi</h1>
				</a>

              </div>
              <div class="collapse navbar-collapse main-menu" id="navbar-example">
                <ul class="nav navbar-nav navbar-right">

                  <li><a href="<?php echo $_SESSION["username"]; ?>" class="isDisabled"><?php echo $_SESSION["username"]; ?></a></li>
                  <li><a href="index.php">Home</a></li>
				  <li><a href="includes/handlers/logout.php">Logout</a></li>

                </ul>
              </div>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </nav>
