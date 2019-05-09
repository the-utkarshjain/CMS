<?php
class Post {

	private $user_obj;
	private $con;

	public function __construct($con, $user){
		$this->con = $con;
		$this->user_obj = new User($con, $user);
	}

	public function submitPost($title, $phone, $email, $body, $user_to, $stipend, $stipend_amount, $interest) {

		/*CLEANING THE INPUTS*/

		$title = strip_tags($title); //removes html tags 
		$title = mysqli_real_escape_string($this->con, $title);

		$phone = strip_tags($phone); //removes html tags 
		$phone = mysqli_real_escape_string($this->con, $phone);

		$email = strip_tags($email); //removes html tags 
		$email = mysqli_real_escape_string($this->con, $email);

		$stipend_amount = strip_tags($stipend_amount); //removes html tags 
		$stipend_amount = mysqli_real_escape_string($this->con, $stipend_amount);

		$interest = strip_tags($interest); //removes html tags 
		$interest = mysqli_real_escape_string($this->con, $interest);

		$body = strip_tags($body); //removes html tags 
		$body = mysqli_real_escape_string($this->con, $body);
		$check_empty = preg_replace('/\s+/', '', $body); //Deltes all spaces 



		/*INSERTING POSTS ONLY IF BODY IS NOT EMPTY*/

		if($check_empty != "") {

			$date_added = date("Y-m-d H:i:s");

			$added_by = $this->user_obj->getUsername();


			if($user_to == $added_by) {
				$user_to = "none";
			}

			//Query to insert post

			$query = mysqli_query($this->con, "INSERT INTO 
				posts(id, body, added_by, user_to, date_added, user_closed, likes, deleted, title, phone, email, stipend, stipend_amount, interest) 
				VALUES('', '$body', '$added_by', '$user_to', '$date_added', 'no', 0, 'no', '$title', '$phone', '$email', '$stipend', '$stipend_amount', '$interest')");

			$returned_id = mysqli_insert_id($this->con);

			//Update post count for user 
			$num_posts = $this->user_obj->getNumPosts();
			$num_posts++;
			$update_query = mysqli_query($this->con, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");

		}

		header("Location: index.php");
	}

	/*FUNCTION TO SHOW POSTS BY ALL*/

	public function loadPostsFriends($data, $limit) {

		$page = $data['page']; 
		$userLoggedIn = $this->user_obj->getUsername();

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;


		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

		if(mysqli_num_rows($data_query) > 0) {


			$num_iterations = 0; //Number of results checked (not necasserily posted)
			$count = 1;

			while($row = mysqli_fetch_array($data_query)) {
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];
				$title = $row['title'];
				$phone = $row['phone'];
				$email = $row['email'];
				$stipend = $row['stipend'];

				$stipend_amount = $row['stipend_amount'];
				if($stipend == "No")
				{
					$stipend_amount = "0";
				}

				$interest = $row['interest'];

				//Prepare user_to string so it can be included even if not posted to a user
				if($row['user_to'] == "none") {
					$user_to = "";
				}
				else {
					$user_to_obj = new User($con, $row['user_to']);
					$user_to_name = $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href='" . $row['user_to'] ."'>" . $user_to_name . "</a>";
				}

				//Check if user who posted, has their account closed
				$added_by_obj = new User($this->con, $added_by);
				if($added_by_obj->isClosed()) {
					continue;
				}
				?>
				
				<script>

					function toggle<?php echo $id;?>(){
						var element = document.getElementById("toggleComment<?php echo $id; ?>");
						
						console.log("hi <?php echo $id; ?>");
						console.log(element.style.display);

						if(element.style.display == 'block')
							element.style.display = 'none';

						else 
							element.style.display = 'block';
					}

				</script>

				<?php
				if($num_iterations++ < $start)
					continue; 

					//Once 10 posts have been loaded, break
				if($count > $limit) {
					break;
				}
				else {
					$count++;
				}

				$user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");

				$user_row = mysqli_fetch_array($user_details_query);

				$first_name = $user_row['first_name'];
				$last_name = $user_row['last_name'];
				$profile_pic = $user_row['profile_pic'];


				$comments_check = mysqli_query($this->con, "SELECT * FROM post_comments WHERE post_id='$id'");

				$comments_check_num = mysqli_num_rows($comments_check);

					//Timeframe
				$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time); //Time of post
					$end_date = new DateTime($date_time_now); //Current time
					$interval = $start_date->diff($end_date); //Difference between dates 
					if($interval->y >= 1) {
						if($interval == 1)
							$time_message = $interval->y . " year ago"; //1 year ago
						else 
							$time_message = $interval->y . " years ago"; //1+ year ago
					}
					else if ($interval-> m >= 1) {
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

					$str .= "<div class='all_posts'>
		<div class='container-fluid' >
			<div class='row'>

				<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
					<div class='post_profile_pic'>
						<img src='$profile_pic' width='50'>
						<a href='$added_by'> $first_name $last_name </a> $user_to &nbsp; &nbsp; &nbsp; &nbsp; $time_message
					</div>
				</div>

				<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>

					<div id='post_body'>
						<p><br>
							<strong>Title: </strong>$title<br>
							<strong>Contact: </strong>$phone <br>
							<strong>Email: </strong><a href='mailto:$email'>$email</a> <br>
							<strong>Interest:</strong> $interest<br>
							<strong>Stipend:</strong> $stipend.&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>Amount: </strong>&nbsp;<i class='fas fa-rupee-sign rupees'></i> $stipend_amount<br><br>
							<strong>Description:</strong><br>$body<br>


						</p>
					</div>

					<hr>
					<div class='comments-sec'>

						<div class='col-xs-4 col-sm-4 col-md-4 col-lg-4 like'>
							<div class='newsfeedPostOptions'>
								<iframe src='like.php?post_id=$id' scrolling='no'></iframe>
							</div>
						</div>
						<div class='col-xs-4 col-sm-4 col-md-4 col-lg-4 comment' onClick='javascript:toggle$id()'>
							<i class='far fa-comment-alt'>&nbsp;&nbsp;</i>Comment&nbsp;&nbsp;<span style='color:black;'>($comments_check_num)</span>
						</div>
						<div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>
						</div>
						
					</div>
				</div>
			</div>
		</div>
		
		<div class='post_comment' id='toggleComment$id' style='display:none;' >
			<iframe src='comment-frame.php?post_id=$id' id='comment_iframe' frameborder='0'> </iframe>
		</div>
	</div>
	<br>
					";


			} //WHILE LOOP ENDS HERE

			if($count > $limit) 
				$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
			<input type='hidden' class='noMorePosts' value='false'>";
			else 
				$str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre;'> No more posts to show! </p>";
		}

		echo $str;


	}


}



?>

