<?php 
include("includes/header.php");
include("includes/classes/User.php");
include("includes/classes/Post.php");


if(isset($_POST['post'])){
	$post = new Post($con, $userLoggedIn);
	$post->submitPost($_POST['title'], $_POST['phone'], $_POST['email'],$_POST['post_text'], 'none', $_POST['stipend'], $_POST['stipend_amount'], $_POST['interest']);
}


?>

<div class="row">

	<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">

		<div class="profile" style="text-align: center;">
			<a href="<?php echo $userLoggedIn; ?>">  <img src="<?php echo $user['profile_pic']; ?>"> </a>
			<br>

			<a href="<?php echo $userLoggedIn; ?>">
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
			<h3> Create project </h3><hr>
			<form class="post_form" action="index.php" method="POST">
				<p>Title: <br><input type="text" name="title" id="title" placeholder="Enter project title here"></p>
				<br>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<p>Email: <br><input type="email" name="email" id="email" placeholder="Contact Email" style="width:80%;"></p>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<p>Phone: <br><input type="text" name="phone" id="phone" placeholder="Contact Number" style="width:80%"></p>
					</div>
				</div>
				<br>
				<textarea name="post_text" id="post_text" placeholder="Project description" required></textarea><br><br><br>

				<p>Research area / Interest:<br> <input type="text" name="interest" id="interest"></p><br>

				<p>Is stipend available? &nbsp;&nbsp;
					<input type="radio" class="stipend" name="stipend" value="Yes" required> Yes &nbsp;
					<input type="text" class="stipend_amount" name="stipend_amount" placeholder="Enter amount">
					&nbsp;
					<input type="radio" class="stipend" name="stipend" value="No" required> No
				</p>

				<script type="text/javascript">

					$(".stipend_amount").hide();

					$(document).ready(function(){
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
					});	

				</script>

				<br>
				<input type="submit" name="post" id="post_button" value="Post">

			</form>
		</div>

		<hr style="border-color: grey">

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
</body>
</html>