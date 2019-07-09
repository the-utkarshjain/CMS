<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require '../config/config.php';
// Instantiation and passing `true` enables exceptions


if(isset($_POST['forget_button'])){

    $emailto = $_POST['forget_email'];
    $emailto = strtolower($emailto);

    $check_query = mysqli_query($con,"SELECT first_name,email from users WHERE email='$emailto'");
    $num_rows = mysqli_num_rows($check_query);

    if($num_rows == 0){
        header("Location: ../error/oops.html");
        exit();
    }

    $array = mysqli_fetch_array($check_query);
$first_name = $array['first_name'];

    $code = uniqid(true);
    $date_add = date("Y-m-d");

    $url = "https://". $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]). "/resetPassword.php?code=$code";

    $query = mysqli_query($con,"INSERT INTO resetPassword(code,email,date_add) VALUES('$code','$emailto','$date_add')");
    
    if(!$query){
        header("Location: ../error/oops.html");
        exit();
    }

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
    $mail->addAddress($emailto);     // Add a recipient
    $mail->addReplyTo('scri.noreply@gmail.com', 'No reply');

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->AddEmbeddedImage('../SCRIlogo.jpg','SCRI');
    $mail->Subject = 'Password change request';
    $mail->Body    = "<h1 style='font-family: 'Raleway',Sans-serif; font-weight: lighter'><span style='color: #3ec1d5;'>SCRI</span> | IIT Mandi </h1><hr>
                      <h2 style='font-family: 'Raleway',Sans-serif; font-weight: lighter'>Hi $first_name,</h2>
                      <br>
			<div style='font-size:15px;'>
                      <p>We received a request to reset your password for your SCRI | IIT Mandi account: $emailto. We are here to help!</p>
			<p>Simply click on the button to set a new password:</p> 
                        <button style=' background-color: #3ec1d5; border: none; color: white; padding: 10px; text-align: center; text-decoration: none; border-radius: 4px; font-size: 15px; margin: 4px 2px; cursor: pointer;'><a href='$url' style='color: white; text-decoration: none;'>Reset Password</a></button><br>
			<p><strong>Note:</strong> This link expires in 2 hours.</p>
			<p>If you didn't ask to change your password, don't worry! Your password is still safe and you can delete this email.</p>
                        <br>
                        Cheers,<br>
			<img src='cid:SCRI' width='100px'>
                        <br>SCRI | IIT Mandi<br>
                        Email: <a href='mailto: scri@students.iitmandi.ac.in'>scri@students.iitmandi.ac.in</a>
                        <br>
                        <a href='scri.iitmandi.ac.in/'>scri.iitmandi.ac.in/</a></div>";

    $mail->AltBody =  "<h1 style='font-family: 'Raleway',Sans-serif; font-weight: lighter'><span style='color: #3ec1d5;'>SCRI</span> | IIT Mandi </h1><hr>
                      <h2 style='font-family: 'Raleway',Sans-serif; font-weight: lighter'>Hi $first_name,</h2>
                      <br>
			<div style='font-size:15px;'>
                      <p>We received a request to reset your password for your SCRI | IIT Mandi account: $emailto. We are here to help!</p>
			<p>Simply click on the button to set a new password:</p> 
                        <button style=' background-color: #3ec1d5; border: none; color: white; padding: 10px; text-align: center; text-decoration: none; border-radius: 4px; font-size: 15px; margin: 4px 2px; cursor: pointer;'><a href='$url' style='color: white; text-decoration: none;'>Reset Password</a></button><br>
			<p><strong>Note:</strong> This link expires in 2 hours.</p>
			<p>If you didn't ask to change your password, don't worry! Your password is still safe and you can delete this email.</p>
                        <br>
                        Cheers,<br>
			<img src='cid:SCRI' width='100px'>
                        <br>SCRI | IIT Mandi<br>
                        Email: <a href='mailto: scri@students.iitmandi.ac.in'>scri@students.iitmandi.ac.in</a>
                        <br>
                        <a href='scri.iitmandi.ac.in/'>scri.iitmandi.ac.in/</a></div>";   
			
    $mail->send();
    header("Location: ../error/sent.html");
    // echo 'Message has been sent';
} catch (Exception $e) {
    header("Location: ../error/oops.html");
}
exit();
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
                <p>Enter your registered email address</p>
                <hr>
            </div>

            <div id="first">
                <form method="POST">
                    <input type="email" name="forget_email" placeholder="Email" autocomplete="off" required> 
                    <br><br>
                    <input type="submit" name="forget_button" id="login_button" value="Submit">
                    <br><br>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
