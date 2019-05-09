<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700|Raleway:300,400,400i,500,500i,700,800,900" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
</head>
<body>

	<style type="text/css">

		form input[type="submit"]{
			color: #20AAE5;
			font-family: 'Open Sans', sans-serif;
			font-size: 15px;
		}

		form input[type="submit"]:hover{
			cursor: pointer;
		}

		form input[type="submit"]:focus{
			outline: 0;
		}

	</style>

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

	if(isset($_GET['post_id'])){
		$post_id = (int)$_GET['post_id'];
	}

	$query = mysqli_query($con,"SELECT likes,added_by FROM posts WHERE id='$post_id'");
	$query_array = mysqli_fetch_array($query);

	$total_likes = $query_array['likes'];
	$user_added_by = $query_array['added_by'];


	$query = mysqli_query($con,"SELECT num_likes FROM users WHERE username='$user_added_by'");
	$query_array = mysqli_fetch_array($query);

	$user_total_likes = $query_array['num_likes'];

	/*LIKE BUTTON HANDLER*/

	if(isset($_POST['like_button']))
	{
		$total_likes++;
		$like_query = mysqli_query($con,"UPDATE posts SET likes = '$total_likes' WHERE id='$post_id'");

		$user_total_likes++;
		$user_like  = mysqli_query($con,"UPDATE users SET num_likes = '$user_total_likes' WHERE username='$user_added_by'");

		$add_like = mysqli_query($con,"INSERT INTO likes(id, username, post_id) VALUES('','$userLoggedIn','$post_id')");
	}

	/*UNLIKE BUTTON HANDLER*/

	if(isset($_POST['unlike_button']))
	{
		$total_likes--;
		$unlike_query = mysqli_query($con,"UPDATE posts SET likes = '$total_likes' WHERE id='$post_id'");

		$user_total_likes--;
		$user_unlike  = mysqli_query($con,"UPDATE users SET num_likes = '$user_total_likes' WHERE username='$user_added_by'");

		$delete_like = mysqli_query($con,"DELETE FROM likes WHERE post_id = '$post_id' AND username = '$userLoggedIn'");
	}

	/*CHECKING IF POST PREVIOUSLY LIKED*/

	$check_like = mysqli_query($con,"SELECT * FROM likes WHERE post_id = '$post_id' AND username = '$userLoggedIn'");

	$check_like_row = mysqli_num_rows($check_like);

	if($check_like_row > 0)
	{
		echo '<form action="like.php?post_id=' . $post_id . '" method="POST">
		<i class="far fa-thumbs-down"></i> &nbsp;
		<input type="submit" class="comment_like" name="unlike_button" value="Unlike">
		<div class="like_value">
		('. $total_likes .')
		</div>
		</form>
		';
	}
	else {
		echo '<form action="like.php?post_id=' . $post_id . '" method="POST">
		<i class="far fa-thumbs-up"></i>&nbsp;
		<input type="submit" class="comment_like" name="like_button" value="Like">
		<div class="like_value">
		('. $total_likes .')
		</div>

		</form>
		';
	}

	?>

</body>
</html>