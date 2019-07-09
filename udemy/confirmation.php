<?php

require 'config/config.php';

if(!isset($_GET["code"])){
	header("Location: error/error.html");
	exit();
}

$str = "";

$code = $_GET["code"];
$getactivated = mysqli_query($con,"UPDATE users SET activated = '1' WHERE token='$code'");

if(!$getactivated){
	header("Location: error/oops.html");
	exit();
}
	
$str = "<h1>Confirmed.</h1> 
			<h4><a href='register.php'>Click here to go to login page. </a></h4>" ;


?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=0.8, user-scalable=no, minimum-scale=0.8, maximum-scale=0.9" />	
	<title>SCRI | IIT MANDI</title>
	<link href="favicon.png" rel="icon" type="image">
        <link href="favicon.png" rel="apple-touch-icon" type="image">

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700|Raleway:300,400,400i,500,500i,700,800,900" rel="stylesheet">

	<style type="text/css">
		
		body{
			height: 1vh;
			background-image: linear-gradient(120deg, #75afff 0%, #3ec1d5 100%);
			text-align: left;
		}

		h1{
			margin-bottom: 20px;
			font-size: 60px;
			font-family: "Raleway",Sans-serif;
			margin-left: 20px;
			margin-top: 20%;
			line-height: 75px;
		}

		h4{
			font-family: "Raleway",Sans-serif;
			margin-left: 20px;
			font-size: 20px;
			font-weight: 500;
		}

		h4 a{
			text-decoration: none;
			color: #fdee07;
		}

	</style>

</head>
<body>

<?php
	echo $str;
?>

</body>
</html>
