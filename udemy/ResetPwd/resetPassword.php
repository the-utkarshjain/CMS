<?php

require '../config/config.php';

if(!isset($_GET["code"])){
    header("Location: ../error/error.html");
    exit();
}

$code = $_GET["code"];

$getEmailquery = mysqli_query($con,"SELECT email FROM resetPassword where code='$code'");

if(mysqli_num_rows($getEmailquery) == 0){
	header("Location: ../error/error.html");
    exit();
}

$array_error = array();

if(isset($_POST["new_password_button"])){
	
	$array = mysqli_fetch_array($getEmailquery);
	$email = $array['email'];

    $new_password = $_POST["new_password"];
    $new_retype_password = $_POST["new_retype_password"];

    if($new_password != $new_retype_password){
        array_push($array_error,"Password don't match!<br>");
    }

    if(empty($array_error)){

        $new_password = hash('sha256',$new_password);

        $update_password_query = mysqli_query($con,"UPDATE users SET password = '$new_password' WHERE email = '$email'");

        if($update_password_query){
            $delete_query = mysqli_query($con,"DELETE FROM resetPassword where code='$code'");
            header("Location: ../error/success.html");
            exit();
        }

        else{
            header("Location: ../error/oops.html");
            exit();
        }

    }
    
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>SCRI | IIT Mandi</title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"> -->
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/register_style.css">

    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700|Raleway:300,400,400i,500,500i,700,800,900" rel="stylesheet">

</head>
<body>

	<div class="wrapper container">
        <div class="login_box">

            <div class="login_header">
                <img id="photo_login" src="../login.jpg">
                <br>
                <p>Choose your new password</p>
                <hr>
            </div>

            <div id="first">

                <form method="POST">

                    <input type="password" name="new_password" placeholder="New password" autocomplete="off" required> 
                    <br>
                    <input type="password" name="new_retype_password" placeholder="Confirm password" autocomplete="off" required> 
                    <br>

                    <?php if(in_array("Password don't match!<br>", $array_error)) echo "Password don't match!<br>"; 
                    ?>
                    <br>
                    <input type="submit" name="new_password_button" id="login_button" value="Submit">
                    <br><br>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
