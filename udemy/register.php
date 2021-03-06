<?php 

require 'config/config.php';
require 'includes/form_handlers/register_handler.php';
require 'includes/form_handlers/login_handler.php';


?>


<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=0.8, user-scalable=no, minimum-scale=0.8, maximum-scale=0.9" />	
	<title>SCRI | IIT MANDI</title>
	<link href="favicon.png" rel="icon" type="image">
	<link href="favicon.png" rel="apple-touch-icon" type="image">

	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/register_style.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="assets/js/register.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700|Raleway:300,400,400i,500,500i,700,800,900" rel="stylesheet">

</head>
<body>

	<?php  

	if(isset($_POST['register_button'])) {
		echo '
		<script>

		$(document).ready(function() {
			$("#first").hide();
			$("#second").show();
			});

			</script>

			';
		}


		?>

		<div class="wrapper container">

			<div class="login_box">
				<div class="login_header">
					<img id="photo_login" src="login.jpg">
					<!-- <h1>SCRI | IIT MANDI</h1> --><br>
					Login or sign up below!<br>
					<hr>
				</div>
				<br>
				<div id="first">

					<form action="register.php" method="POST">
						<input type="email" name="log_email" placeholder="Email Address" value="<?php 
						if(isset($_SESSION['log_email'])) {
							echo $_SESSION['log_email'];
						} 
						?>" required> 
						<br>
						<input type="password" name="log_password" placeholder="Password">
						<br>
						<?php if(in_array("Email or password was incorrect<br>", $error_array)) echo  "Email or password was incorrect<br>"; ?>
						<input type="submit" name="login_button" id="login_button" value="Login">
						<br><br>
						<a href="#" id="signup" class="signup">Need and account? Register here!</a>
						<br>
						<a href="ResetPwd/requestReset.php" id="signup" class="signup">Forget password?</a>

					</form>

				</div>

				<div id="second">

					<form action="register.php" method="POST">
						<input type="text" name="reg_fname" placeholder="First Name" value="<?php 
						if(isset($_SESSION['reg_fname'])) {
							echo $_SESSION['reg_fname'];
						} 
						?>" required>
						<br>
						<?php if(in_array("Your first name must be between 2 and 25 characters<br>", $error_array)) echo "Your first name must be between 2 and 25 characters<br>"; ?>
						
						
						<input type="text" name="reg_lname" placeholder="Last Name" value="<?php 
						if(isset($_SESSION['reg_lname'])) {
							echo $_SESSION['reg_lname'];
						} 
						?>" required>
						<br>
						<?php if(in_array("Your last name must be between 2 and 25 characters<br>", $error_array)) echo "Your last name must be between 2 and 25 characters<br>"; ?>

						<input type="email" name="reg_email" placeholder="Email" value="<?php 
						if(isset($_SESSION['reg_email'])) {
							echo $_SESSION['reg_email'];
						} 
						?>" required>
						<br>

						<!-- <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php 
						//if(isset($_SESSION['reg_email2'])) {
							//echo $_SESSION['reg_email2'];
						//} 
						?>" required>
						<br>-->
						<?php if(in_array("Email already in use<br>", $error_array)) echo "Email already in use<br>"; 
						else if(in_array("Invalid email format<br>", $error_array)) echo "Invalid email format<br>";
						//else if(in_array("Emails don't match<br>", $error_array)) echo "Emails don't match<br>"; ?>


						<input type="password" name="reg_password" placeholder="Password" required>
						<br>
						<input type="password" name="reg_password2" placeholder="Confirm Password" required>
						<br>
						<?php if(in_array("Your passwords do not match<br>", $error_array)) echo "Your passwords do not match<br>"; 
						else if(in_array("Invalid characters used<br>", $error_array)) echo "Invalid characters used<br>";
						else if(in_array("Your password must be betwen 5 and 30 characters<br>", $error_array)) echo "Your password must be betwen 5 and 30 characters<br>"; ?>
						<br>
						<input type="radio" class="type" name="type" value="Student" required> &nbsp;Student&nbsp;
						<input type="radio" class="type" name="type" value="Faculty" required> &nbsp;Faculty<br><br>

						<div class="subtype">
							<input type="radio" name="sub-type"  value="B.Tech">&nbsp;B.Tech&nbsp;
							<input type="radio" name="sub-type"  value="M.Tech">&nbsp;M.Tech&nbsp;
							<input type="radio" name="sub-type"  value="M.S.">&nbsp;M.S.&nbsp;
							<input type="radio" name="sub-type"  value="M.Sc.">&nbsp;M.Sc.&nbsp;
							<input type="radio" name="sub-type"  value="PhD">&nbsp;PhD
							<br><br>
						</div>

						<input type="submit" name="register_button" id="register_button" value="Register">
						<br>

						<!-- <?php 
							//if(in_array("<span style='color: #14C800;'>You're all set! Go ahead and login!</span><br>", $error_array)) 
							{	
								//header("Location: error/sent.html"); 
								//exit();
							}
						?> -->

						<br>
						<a href="#" id="signin" class="signin">Already have an account? Sign in here!</a>
					</form>
				</div>

			</div>

		</div>

		<script type="text/javascript">

			$(".subtype").hide();

			$(document).ready(function () {

				$(".type").change(function () {

					var val = $('.type:checked').val();
					if(val == "Student"){
						$(".subtype").slideDown("fast");
						$(".subtype input[type=radio]").prop('required',true);
					}
					else{
						$(".subtype").slideUp("fast");
						$(".subtype input[type=radio]").prop('required',false);
					}
				});
			});

		</script>


	</body>
	</html>
