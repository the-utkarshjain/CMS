<?php 

require 'config/config.php';
include("includes/classes/User.php");
include("includes/classes/Post.php");


if (isset($_SESSION['username'])) {

	$userLoggedIn = $_SESSION['username'];
	$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
	$user = mysqli_fetch_array($user_details_query);

}
else {
	header("Location: register.php");
}

?>


<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700|Raleway:300,400,400i,500,500i,700,800,900" rel="stylesheet">

</head>
<body>


	<style type="text/css">
		* {
			font-size: 12px;
			font-family: Arial, Helvetica, Sans-serif;
		}

		.comment-section img{
			float:left; 
			margin: 0 5px 3px 3px;
			border-radius: 3px;
		}

	</style>

	<script>
		function toggle() {
			var element = document.getElementById("comment_section");

			if(element.style.display == "block") 
				element.style.display = "none";
			else 
				element.style.display = "block";
		}
	</script>

	<?php

	if(isset($_GET['post_id'])){
		$post_id = (int)$_GET['post_id'];
	}

	$user_querys = mysqli_query($con,"SELECT added_by,user_to FROM posts where id='$post_id'");

	$user_arrays = mysqli_fetch_array($user_querys);

	$added_bys = $user_arrays['added_by'];

	if(isset($_POST['postcomment'.$post_id])){

		$comment_body = $_POST['comment'];

		$comment_body = mysqli_escape_string($con,$comment_body);

		$date = date("Y-m-d H:i:s");

		$query = mysqli_query($con,"INSERT INTO 
			post_comments(id, post_body, posted_by, posted_to, date_added, removed, post_id)
			VALUES('','$comment_body','$userLoggedIn','$added_bys','$date','no','$post_id')");
	}

	?>

	<form action="comment-frame.php?post_id=<?php echo $post_id; ?>" method="POST" id="comment_form" name="postcomment<?php echo $post_id; ?>">

		<textarea placeholder="write a comment.." name="comment"></textarea>
		<input type="submit" name="postcomment<?php echo $post_id;?>" value="Post" id="comment_button">

	</form>

	<?php

	$get_comments = mysqli_query($con,"SELECT * FROM post_comments where post_id='$post_id'");

	$count = mysqli_num_rows($get_comments);

	$str = "";
	if($count != 0)
	{
		while($row = mysqli_fetch_array($get_comments)){

			$comment_body_2 = $row['post_body'];
			$posted_to = $row['posted_to'];
			$posted_by = $row['posted_by'];
			$date = $row['date_added'];
			$removed = $row['removed'];
			$posts_id = (int)$row['post_id'];


			$date_time_now = date("Y-m-d H:i:s");

					$start_date = new DateTime($date); //Time of post
					$end_date = new DateTime($date_time_now); //Current time

					$interval = $start_date->diff($end_date); //Difference between dates 

					if($interval->y >= 1) {
						if($interval == 1)
							$time_message = $interval->y . " year ago"; //1 year ago
						else 
							$time_message = $interval->y . " years ago"; //1+ year ago
					}

					else if ($interval->m >= 1) {
						if($interval->d == 0) {
							$days = " ago";
						}
						else if($interval->d == 1) {
							$days = $interval->d . " day ago";
						}
						else {
							$days = $interval->d . " days ago";
						}


						if($interval->m == 1) {
							$time_message = $interval->m . " month". $days;
						}
						else {
							$time_message = $interval->m . " months". $days;
						}
					}

					else if($interval->d >= 1) {
						if($interval->d == 1) {
							$time_message = "Yesterday";
						}
						else {
							$time_message = $interval->d . " days ago";
						}
					}

					else if($interval->h >= 1) {
						if($interval->h == 1) {
							$time_message = $interval->h . " hour ago";
						}
						else {
							$time_message = $interval->h . " hours ago";
						}
					}

					else if($interval->i >= 1) {
						if($interval->i == 1) {
							$time_message = $interval->i . " minute ago";
						}
						else {
							$time_message = $interval->i . " minutes ago";
						}
					}
					
					else {
						if($interval->s < 30) {
							$time_message = "Just now";
						}
						else {
							$time_message = $interval->s . " seconds ago";
						}
					}

					$user = new user($con,$posted_by);


					?>

					<div class="comment_section">

						<div width="20%" style="float:left">
							<a href="<?php echo $posted_by?>" target="_parent">
								<img src="<?php echo $user->getprofilepic();?>" title="<?php echo $user->getFirstAndLastName()?>" height="30"></a></div>

						<div width="80%">

							<a href="<?php echo $posted_by?>" target="_parent"><?php echo $user->getFirstAndLastName()?></a> &nbsp; &nbsp; &nbsp; &nbsp; <?php echo $time_message;?><br>
							<?php echo $comment_body_2; ?></div>

						</div>
						
						<hr>

						<?php
					}
				}

				else {
					echo "<center><br><br>No Comments to Show!</center>";
				}

				?>

			</body>
			</html>