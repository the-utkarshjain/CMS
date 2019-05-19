<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'ResetPwd/PHPMailer/src/Exception.php';
require 'ResetPwd/PHPMailer/src/PHPMailer.php';
require 'ResetPwd/PHPMailer/src/SMTP.php'; 
//Declaring variables to prevent errors

$fname = ""; //First name
$lname = ""; //Last name
$em = ""; //email
$em2 = ""; //email 2
$password = ""; //password
$password2 = ""; //password 2
$date = ""; //Sign up date 
$error_array = array(); //Holds error messages

if(isset($_POST['register_button']))
{

	//Registration form values
	$type = $_POST['type'];

	if($type=="Student"){
		$subtype = $_POST['sub-type'];
	}

	else{
		$subtype = "Faculty";
	}
	//First name
	$fname = strip_tags($_POST['reg_fname']); //Remove html tags
	$fname = str_replace(' ', '', $fname); //remove spaces
	$fname = ucfirst(strtolower($fname)); //Uppercase first letter
	$_SESSION['reg_fname'] = $fname; //Stores first name into session variable

	//Last name
	$lname = strip_tags($_POST['reg_lname']); //Remove html tags
	$lname = str_replace(' ', '', $lname); //remove spaces
	$lname = ucfirst(strtolower($lname)); //Uppercase first letter
	$_SESSION['reg_lname'] = $lname; //Stores last name into session variable

	//email
	$em = strip_tags($_POST['reg_email']); //Remove html tags
	$em = str_replace(' ', '', $em); //remove spaces
	$em = strtolower($em);
	$_SESSION['reg_email'] = $em; //Stores email into session variable

	//email 2
	// $em2 = strip_tags($_POST['reg_email2']); //Remove html tags
	// $em2 = str_replace(' ', '', $em2); //remove spaces
	// $_SESSION['reg_email2'] = $em2; //Stores email2 into session variable

	//Password
	$password = strip_tags($_POST['reg_password']); //Remove html tags
	$password2 = strip_tags($_POST['reg_password2']); //Remove html tags

	$date = date("Y-m-d"); //Current date

	if($em == $em) {
		//Check if email is in valid format 
		if(filter_var($em, FILTER_VALIDATE_EMAIL)) {

			$em = filter_var($em, FILTER_VALIDATE_EMAIL);

			//Check if email already exists 
			$e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");

			//Count the number of rows returned
			$num_rows = mysqli_num_rows($e_check);

			if($num_rows > 0) {
				array_push($error_array, "Email already in use<br>");
			}

		}
		else {
			array_push($error_array, "Invalid email format<br>");
		}


	}
	else {
		array_push($error_array, "Emails don't match<br>");
	}


	if(strlen($fname) > 25 || strlen($fname) < 2) {
		array_push($error_array, "Your first name must be between 2 and 25 characters<br>");
	}

	if(strlen($lname) > 25 || strlen($lname) < 2) {
		array_push($error_array,  "Your last name must be between 2 and 25 characters<br>");
	}

	if($password != $password2) {
		array_push($error_array,  "Your passwords do not match<br>");
	}
	else {
		if(preg_match('/[^A-Za-z0-9\W\.\-\_\+\=\\\|]/', $password)) {
			array_push($error_array, "Invalid characters used<br>");
		}
	}

	if(strlen($password > 30 || strlen($password) < 5)) {
		array_push($error_array, "Your password must be betwen 5 and 30 characters<br>");
	}


	if(empty($error_array)) {
		
		$password = hash('sha256',$password); //Encrypt password before sending to database

		//Generate username by concatenating first name and last name
		$username = strtolower($fname . "_" . $lname);
		$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");


		$i = 0; 
		//if username exists add number to username
		while(mysqli_num_rows($check_username_query) != 0) {
			$i++; //Add 1 to i
			$username = $username . "_" . $i;
			$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
		}

		//Profile picture assignment
		$rand = rand(1, 7); //Random number between 1 and 2

		if($rand == 1)
			$profile_pic = "assets/images/profile_pics/defaults/icons8-boy.png";

		else if($rand == 2)
			$profile_pic = "assets/images/profile_pics/defaults/icons8-businesswoman.png";

		else if($rand == 3)
			$profile_pic = "assets/images/profile_pics/defaults/icons8-manager.png";

		else if($rand == 4)
			$profile_pic = "assets/images/profile_pics/defaults/icons8-user_female_red_hair.png";

		else if($rand == 5)
			$profile_pic = "assets/images/profile_pics/defaults/icons8-user_female_skin_type_5.png";

		else if($rand == 6)
			$profile_pic = "assets/images/profile_pics/defaults/icons8-user_female.png";

		else if($rand == 7)
			$profile_pic = "assets/images/profile_pics/defaults/icons8-user_male.png";

		$token = uniqid(true);
		$activated = 0;

		$query = mysqli_query($con, "INSERT INTO
			users(first_name, last_name, username, email, password, signup_date, profile_pic, num_posts, num_likes, user_closed, friend_array, type, subtype, activated, token)
			VALUES ('$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',', '$type', '$subtype','$activated','$token')");

		// array_push($error_array, "<span style='color: #14C800;'>Email with activation link has been sent to the provided email adress</span><br>");
		// array_push($error_array, "<span style='color: #14C800;'>You're all set! Go ahead and login!</span><br>");

		if(!$query){
			header("Location: ../error/oops.html");
			exit();
		}
		
		$url = "http://". $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]). "/confirmation.php?code=$token";

		$mail = new PHPMailer(true);

		try {
    //Server settings                            
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'scri.noreply@gmail.com';                     // SMTP username
    $mail->Password   = 'Quantum124';                               // SMTP password
    $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 465;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('scri.noreply@gmail.com', 'SCRI | IIT Mandi');
    $mail->addAddress($em);     // Add a recipient
    $mail->addReplyTo('no-reply@gmail.com', 'No reply');

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Please confirm your email';
    $mail->Body    = "<h1><span style='color: #3ec1d5;'>SCRI</span> | IIT Mandi </h1><hr>
    <h1 style='font-family: 'Raleway',Sans-serif;'>Before we get started...</h1>
    <br>
    <h2> Please take a second to make sure we've got your email right. </h2>
    <p>
    Click on <a href='$url'>this link</a> to do so.</p> 
    <br><br>
    Regards,
    <br>SCRI | IIT Mandi<br>
    Email: <a href='mailto: scri@students.iitmandi.ac.in'>scri@students.iitmandi.ac.in</a>
    <br>
    <a href='scri.iitmandi.ac.in/'>scri.iitmandi.ac.in/</a>";
    $mail->AltBody = 'Body in plain text for non-HTML mail clients';

    $mail->send();
    header("Location: error/sent.html");
} catch (Exception $e) {
	header("Location: error/oops.html");
}

		//Clear session variables 
$_SESSION['reg_fname'] = "";
$_SESSION['reg_lname'] = "";
$_SESSION['reg_email'] = "";
$_SESSION['reg_email2'] = "";
}

}

?>
