<?php 
include("includes/header.php");
include("includes/classes/User.php");
include("includes/classes/Post.php");


if(isset($_GET['profile_username'])) {
	$username = $_GET['profile_username'];
	
	$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");

	$user_array = mysqli_fetch_array($user_details_query);
}
	
?>
<div class="container">

	<div class ="row"> 

		<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">

			<div class="profile" style="text-align: center;">
				<a href="<?php echo $userLoggedIn; ?>">  <img src="<?php echo $user_array['profile_pic']; ?>"> </a><br>
				<br>

				<a href="<?php echo $username; ?>">
					<?php 
					echo $user_array['first_name'] . " " . $user_array['last_name'];
					?>
				</a>
				<br>
				<?php echo "Posts: " . $user_array['num_posts']. "<br>"; 

				if($user_array['type'] == "Student"){
					echo $user_array['subtype']."<br>";
				}
				
				else{
					echo $user_array['type'] ."<br>";
				}

				?>
				<br><br>
			</div>

		</div>

		<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">

		</div>

	</div>

</div>

<footer style="bottom: 0; width: 100%; position: absolute; ">
	<div class="footer-area" >
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="footer-content">
						<div class="footer-head">
							<div class="footer-logo">
								<h2><span>SCRI</span> | IIT Mandi</h2>
							</div>

							<p>Follow us</p>
							<div class="footer-icons">
								<ul> 
									<li>
										<a href="https://www.facebook.com/scriiitmandi/"><i class="fab fa-facebook-f"></i></a>
									</li>
									<li>
										<a href="https://www.linkedin.com/in/scri-iit-mandi-406732172/"><i class="fab fa-linkedin-in"></i></a>
									</li>
								</ul>
								<br>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="footer-content">
						<div class="footer-head">
							<h4>information</h4>
							<div class="footer-contacts">
								<p><span>Tel:</span> +91 90242 62802</p>
								<p><span>Email:</span><a href="mailto:scri@students.iitmandi.ac.in">&nbsp;&nbsp;scri@students.iitmandi.ac.in</a></p>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="footer-content">
						<div class="footer-head">
							<h4 style="line-height: 37px;"><b><span style="color:#3ec1d5;">Collaborative</span> Research:</b> <br>"Alone we can do so little, <br>together we can do so much."</h4>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="footer-area-bottom">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="copyright text-center">
						<p>
							&copy; Copyright <strong>SCRI | IIT Mandi</strong>. All Rights Reserved
						</p>
					</div>
					<div class="credits">
						Designed and Developed by <a href="https://www.facebook.com/utkarsh.jain.376" target="_blank">Utkarsh Jain</a><br>
						Hosted by <a href="https://www.iitmandi.ac.in" target="_blank">Wing IIT Mandi</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>

</body>
</html>
