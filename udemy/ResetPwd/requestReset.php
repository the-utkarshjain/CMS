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

    $code = uniqid(true);
    $date_add = date("Y-m-d");

    $url = "http://". $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]). "/resetPassword.php?code=$code";

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
    $mail->setFrom('utkarsh.j.99@gmail.com', 'SCRI | IIT Mandi');
    $mail->addAddress($emailto);     // Add a recipient
    $mail->addReplyTo('no-reply@gmail.com', 'No reply');

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Password change request';
    $mail->Body    = "<h1><span style='color: #3ec1d5;'>SCRI</span> | IIT Mandi </h1><hr>
                      <h1 style='font-family: 'Raleway',Sans-serif;'>You requested a password change</h1>
                      <br>
                      <p>
                        Click on <a href='$url'>this link</a> to do so.</p> 
                        <br>
                        Regards,<br>
                        <br>SCRI | IIT Mandi<br>
                        Email: <a href='mailto: scri@students.iitmandi.ac.in'>scri@students.iitmandi.ac.in</a>
                        <br>
                        <a href='scri.iitmandi.ac.in/'>scri.iitmandi.ac.in/</a>";
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

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
