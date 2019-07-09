<?php 
include("includes/header.php");
include("includes/classes/User.php");
include("includes/classes/Post.php");

$post1 = new Post($con, $userLoggedIn);
$isStudent = $post1->isStudent();

if(isset($_POST['post'])){
	$post = new Post($con, $userLoggedIn);
	$post->submitPost($_POST['title'], $_POST['phone'], $_POST['email'],$_POST['post_text'], 'none', $_POST['stipend'], $_POST['stipend_amount'], $_POST['interest']);
}


?>
<div class="container">
	<div class="row">

		<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">

			<div class="profile" style="text-align: center;">
				<a href="<?php echo $userLoggedIn; ?>" class="isDisabled">  <img src="<?php echo $user['profile_pic']; ?>"> </a><br>
				<br>

				<a href="<?php echo $userLoggedIn; ?>" class="isDisabled">
					<?php 
					echo $user['first_name'] . " " . $user['last_name'];
					?>
				</a>
				<br>
				<?php echo "Posts: " . $user['num_posts']. "<br>"; 
				echo "Likes: " . $user['num_likes'];

				?>
				<br><br>
			</div>

		</div>


		<div class= "col-xs-12 col-sm-12 col-md-10 col-lg-10">

			<div class="create_project">

				<h3> Create project </h3><br><br>
				<form class="post_form" action="index.php" method="POST">

					<p>Title: <br><input type="text" name="title" id="title" placeholder="Enter project title here"></p>
					<br>

					<div class="row">

						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<p>Email: <br><input type="email" name="email" id="email" placeholder="Contact Email" style="width:80%;"></p>
						</div>

						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<p>Phone: <br><input type="number" name="phone" id="phone" placeholder="Contact Number" style="width:80%"></p>
						</div>
						<script>
							$(function() {
								$("#phone").keypress(function(event) {
									if (event.which != 8 && event.which != 0 && (event.which < 48 || event.which > 57)) {
										return false;
									}
								});
							});
						</script>
					</div>

					<br>

					<p>Project Description: <br>
						<textarea name="post_text" id="post_text" placeholder="Project description" required></textarea><br></p>
						<br>

						<p>Research area / Interest:<br> <input type="text" name="interest" id="interest"></p><br>

						<div id="isStipend">
							<p>Is stipend available? &nbsp;&nbsp;
								<input type="radio" class="stipend" name="stipend" value="Yes" required> Yes &nbsp;
								<input type="text" class="stipend_amount" name="stipend_amount" placeholder="Enter amount">
								&nbsp;
								<input type="radio" class="stipend" name="stipend" value="No" required> No
							</p>
						</div>

						<script type="text/javascript">

							
							$(document).ready(function(){

								if("<?php echo $isStudent; ?>"){
									//$("#isStipend").hide();
									$("input[name='stipend'][value='No']").prop('checked',true);
								}

							//	else{
									$(".stipend_amount").hide();
									$(".stipend").change(function(){

									var val = $('.stipend:checked').val();
									if(val == "Yes"){
										$(".stipend_amount").show("fast");
										$(".stipend_amount").prop('required',true);
									}
									else{
										$(".stipend_amount").hide("fast");
										$(".stipend_amount").prop('required',false);
									}
								});

								//}
								
							});	

						</script>

						<button type="submit" name="post" id="post_button" value="Post">Post</button>

					</form>
				</div>

				<hr style="border-color: grey; margin-bottom: 20px;">

				<div class="posts_area"></div>
				<img id="loading" src="assets/images/icons/loading.gif">

				<script>
					var userLoggedIn = '<?php echo $userLoggedIn; ?>';

					$(document).ready(function() {

						$('#loading').show();

		//Original ajax request for loading first posts 
		$.ajax({
			url: "includes/handlers/ajax_load_posts.php",
			type: "POST",
			data: "page=1&userLoggedIn=" + userLoggedIn,
			cache:false,

			success: function(data) {
				$('#loading').hide();
				$('.posts_area').html(data);
			}
		});

		$(window).scroll(function() {
			var height = $('.posts_area').height(); //Div containing posts
			var scroll_top = $(this).scrollTop();
			var page = $('.posts_area').find('.nextPage').val();
			var noMorePosts = $('.posts_area').find('.noMorePosts').val();

			if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false') {
				$('#loading').show();

				var ajaxReq = $.ajax({
					url: "includes/handlers/ajax_load_posts.php",
					type: "POST",
					data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
					cache:false,

					success: function(response) {
						$('.posts_area').find('.nextPage').remove(); //Removes current .nextpage 
						$('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage 

						$('#loading').hide();
						$('.posts_area').append(response);
					}
				});

			} //End if 

			return false;

		}); //End (window).scroll(function())


	});

</script>

</div>
</div>
</div>

<br><br>
<footer style="bottom: 0px; width: 100%">
	<div class="footer-area">
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
