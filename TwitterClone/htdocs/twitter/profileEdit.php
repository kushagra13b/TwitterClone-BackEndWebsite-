<?php 
	include 'core/init.php';
	if($getFromU->loggedIn() === false)
	{
		header('Location: index.php');
	}
	$user_id = $_SESSION['user_id'];
	$user = $getFromU->userData($user_id);
	
	if(isset($_POST['screenName']))
	{
		if(!empty($_POST['screenName']))
		{
			$screenName = $getFromU->checkInput($_POST['screenName']);
			$profileBio = $getFromU->checkInput($_POST['bio']);
			$country = $getFromU->checkInput($_POST['country']);
			$website = $getFromU->checkInput($_POST['website']);
			
			if(strlen($screenName) > 20)
			{
				$error = "Name must be between 6-20 characters";
			}
			else if(strlen($profileBio) > 120)
			{
				$error = "Description is too long";
			}
			else if(strlen($country) > 80)
			{
				$error = "Country name is too long";
			}
			else
			{
				$getFromU->update('users', $user_id, array('screenName' => $screenName, 'bio' => $profileBio, 'country' => $country, 'website' => $website));
				header('Location: '.$user->username);
			}
		}
		else
		{
			$error = "Name field cannot be blank";
		}
	}
	
	if(isset($_FILES['profileImage']))
	{
		if(!empty($_FILES['profileImage']['name'][0]))
		{
			$fileRoot = $getFromU->uploadImage($_FILES['profileImage']);
			$getFromU->update('users', $user_id, array('profileImage' => $fileRoot));
			header('Location:'.$user->username);
		}
	}
		if(isset($_FILES['profileCover']))
	{
		if(!empty($_FILES['profileCover']['name'][0]))
		{
			$fileRoot = $getFromU->uploadImage($_FILES['profileCover']);
			$getFromU->update('users', $user_id, array('profileCover' => $fileRoot));
			header('Location:'.$user->username);
		}
	}
?>
<!doctype html>
<html>
<head>
	<title>Profile edit page</title>
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css"/>
	<link rel="stylesheet" href="assets/css/style-complete.css"/>
	<script src="https://code.jquery.com/jquery-3.1.1.js" integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA=" crossorigin="anonymous"></script>
</head>
<!--Helvetica Neue-->
<body>
<div class="wrapper">
	<!-- header wrapper -->
<div class="header-wrapper">

<div class="nav-container">
	<!-- Nav -->
	<div class="nav">
		<div class="nav-left">
			<ul>
				<li><a href="home.php"><i class="fa fa-home" aria-hidden="true"></i>Home</a></li>
				<li><a href="i/notifications"><i class="fa fa-bell" aria-hidden="true"></i>Notification</a></li>
				<li><i class="fa fa-envelope" aria-hidden="true"></i>Messages</li>
			</ul>
		</div>
		<!-- nav left ends-->
		<div class="nav-right">
			<ul>
				<li><input type="text" placeholder="Search" class="search"/><i class="fa fa-search" aria-hidden="true"></i>
				<div class="search-result">
					 			
				</div></li>
				<li class="hover"><label class="drop-label" for="drop-wrap1"><img src="<?php echo $user->profileImage;?>"/></label>
				<input type="checkbox" id="drop-wrap1">
				<div class="drop-wrap">
					<div class="drop-inner">
						<ul>
							<li><a href="<?php echo $user->username;?>"><?php echo $user->username;?></a></li>
							<li><a href="settings/account">Settings</a></li>
							<li><a href="includes/logout.php">Log out</a></li>
						</ul>
					</div>
				</div>
				</li>
				<li><label for="pop-up-tweet" class="addTweetBtn">Tweet</label></li>
			</ul>
		</div>
		<!-- nav right ends-->
	</div>
	<!-- nav ends -->
</div>
<!-- nav container ends -->
</div>
<!-- header wrapper end -->

<!--Profile cover-->
<div class="profile-cover-wrap"> 
<div class="profile-cover-inner">
	<div class="profile-cover-img">
	   <!-- PROFILE-COVER -->
		<img src="<?php echo $user->profileCover;?>"/>
 
		<div class="img-upload-button-wrap">
			<div class="img-upload-button1">
				<label for="cover-upload-btn">
					<i class="fa fa-camera" aria-hidden="true"></i>
				</label>
				<span class="span-text1">
					Change your profile photo
				</span>
				<input id="cover-upload-btn" type="checkbox"/>
				<div class="img-upload-menu1">
					<span class="img-upload-arrow"></span>
					<form method="post" enctype="multipart/form-data">
						<ul>
							<li>
								<label for="file-up">
									Upload photo
								</label>
								<input type="file" name="profileCover" onchange = "this.form.submit();" id="file-up" />
							</li>
								<li>
								<label for="cover-upload-btn">
									Cancel
								</label>
							</li>
						</ul>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="profile-nav">
	<div class="profile-navigation">
		<ul>
			<li>
				<a href="#">
					<div class="n-head">
						TWEETS
					</div>
					<div class="n-bottom">
						<?php $getFromT->countTweets($user_id);?>
					</div>
				</a>
			</li>
			<li>
				<a href="<?php echo BASE_URL.$user->username.'/following';?>">
					<div class="n-head">
						FOLLOWINGS
					</div>
					<div class="n-bottom">
						<?php echo $user->following;?>
					</div>
				</a>
			</li>
			<li>
				<a href="<?php echo BASE_URL.$user->username.'/followers';?>">
					<div class="n-head">
						FOLLOWERS
					</div>
					<div class="n-bottom">
						<?php echo $user->followers;?>
					</div>
				</a>
			</li>
			<li>
				<a href="#">
					<div class="n-head">
						LIKES
					</div>
					<div class="n-bottom">
						<?php $getFromT->countLikes($user_id);?>
					</div>
				</a>
			</li>
			
		</ul>
		<div class="edit-button">
			<span>
				<button class="f-btn" type="button" onclick = "window.location.href='<?php echo $user->username;?>'" value="Cancel">Cancel</button>
			</span>
			<span>
				<input type="submit" id="save" value="Save Changes">
			</span>
		 
		</div>
	</div>
</div>
</div><!--Profile Cover End-->

<div class="in-wrapper">
<div class="in-full-wrap">
  <div class="in-left">
	<div class="in-left-wrap">
		<!--PROFILE INFO WRAPPER END-->
<div class="profile-info-wrap">
	<div class="profile-info-inner">
		<div class="profile-img">
			<!-- PROFILE-IMAGE KUSHAGRA BAJPAI WASSSUUUUP -->
			<img src="<?php echo $user->profileImage;?>"/>
 			<div class="img-upload-button-wrap1">
			 <div class="img-upload-button">
				<label for="img-upload-btn">
					<i class="fa fa-camera" aria-hidden="true"></i>
				</label>
				<span class="span-text">
					Change your profile photo
				</span>
				<input id="img-upload-btn" type="checkbox"/>
				<div class="img-upload-menu">
				 <span class="img-upload-arrow"></span>
					<form method="post" enctype="multipart/form-data">
						<ul>
							<li>
								<label for="profileImage">
									Upload photo
								</label>
								<input id="profileImage" type="file" onchange = "this.form.submit();" name="profileImage"/>
								
							</li>
							<li><a href="#">Remove</a></li>
							<li>
								<label for="img-upload-btn">
									Cancel
								</label>
							</li>
						</ul>
					</form>
				</div>
			  </div>
			  <!-- img upload end-->
			</div>
		</div>

			    <form id="editForm" method="post" enctype="multipart/Form-data">	
				<div class="profile-name-wrap">
					<?php 
						if(isset($imageError))
						{
							echo '<ul>
									 <li class="error-li">
										 <div class="span-pe-error">'.$imageError.'</div>
									 </li>
								 </ul>';
						}
					?> 
					<div class="profile-name">
						<input type="text" name="screenName" value="<?php echo $user->screenName;?>"/>
					</div>
					<div class="profile-tname">
						@<?php echo $user->username;?>
					</div>
				</div>
				<div class="profile-bio-wrap">
					<div class="profile-bio-inner">
						<textarea class="status" name="bio"><?php echo $user->bio;?></textarea>
						<div class="hash-box">
					 		<ul>
					 		</ul>
					 	</div>
					</div>
				</div>
					<div class="profile-extra-info">
					<div class="profile-extra-inner">
						<ul>
							<li>
								<div class="profile-ex-location">
									<input id="cn" type="text" name="country" placeholder="Country" value="<?php echo $user->country;?>" />
								</div>
							</li>
							<li>
								<div class="profile-ex-location">
									<input type="text" name="website" placeholder="Website" value="<?php echo $user->website;?>"/>
								</div>
							</li>
							
						<?php 
						if(isset($error))
						{
							echo '
									 <li class="error-li">
										 <div class="span-pe-error">'.$error.'</div>
									 </li>
								 ';
						}
						?> 
							
				</form>
				<script type = "text/javascript">
				 $('#save').click(function(){
					 $('#editForm').submit();
				 })
				</script>
						</ul>						
					</div>
				</div>
				<div class="profile-extra-footer">
					<div class="profile-extra-footer-head">
						<div class="profile-extra-info">
							<ul>
								<li>
									<div class="profile-ex-location-i">
										<i class="fa fa-camera" aria-hidden="true"></i>
									</div>
									<div class="profile-ex-location">
										<a href="#">0 Photos and videos </a>
									</div>
								</li>
							</ul>
						</div>
					</div>
					<div class="profile-extra-footer-body">
						<ul>
						  <!-- <li><img src="#"></li> -->
						</ul>
					</div>
				</div>
			</div>
			<!--PROFILE INFO INNER END-->
		</div>
		<!--PROFILE INFO WRAPPER END-->
	</div>
	<!-- in left wrap-->
</div>
<!-- in left end-->

<div class="in-center">
	<div class="in-center-wrap">	
		<?php 
		$tweets = $getFromT->getUserTweets($user_id);
		  foreach ($tweets as $tweet) {
    $likes   = $getFromT->likes($user_id, $tweet->tweetID);
    $retweet = $getFromT->checkRetweet($tweet->tweetID, $user_id);
    $user    = $getFromU->userData($tweet->retweetBy);
    
    echo '<div class="all-tweet">
		    <div class="t-show-wrap">
		     <div class="t-show-inner">
		     '.((is_array($retweet) && isset($retweet['retweetID']) ? $retweet['retweetID'] === $tweet->retweetID OR $tweet->retweetID > 0 : '') ? '
		      <div class="t-show-banner">
		        <div class="t-show-banner-inner">
		          <span><i class="fa fa-retweet" aria-hidden="true"></i></span><span>'.$user->screenName.' Retweeted</span>
		        </div>
		      </div>'
		      : '').'

		     '.((!empty($tweet->retweetMsg) && (is_array($retweet) && isset($retweet['tweetID']) && $tweet->tweetID === $retweet['tweetID']) OR $tweet->retweetID > 0) ? '
			  <div class="t-show-head">
		      <div class="t-show-popup" data-tweet="'.$tweet->tweetID.'">
		        <div class="t-show-img">
		          <img src="'.BASE_URL.$user->profileImage.'"/>
		        </div>
		        <div class="t-s-head-content">
		          <div class="t-h-c-name">
		            <span><a href="'.BASE_URL.$user->username.'">'.$user->screenName.'</a></span>
		            <span>@'.$user->username.'</span>
		            <span>'.$getFromU->timeAgo($tweet->postedOn).'</span>
		          </div>
		          <div class="t-h-c-dis">
		            '.$getFromT->getTweetLinks($tweet->retweetMsg).'
		          </div>
		        </div>
		      </div>
		      <div class="t-s-b-inner">
		        <div class="t-s-b-inner-in">
		          <div class="retweet-t-s-b-inner">
		          '.((!empty($tweet->tweetImage)) ? '
		            <div class="retweet-t-s-b-inner-left">
		              <img src="'.BASE_URL.$tweet->tweetImage.'" class="imagePopup" data-tweet="'.$tweet->tweetID.'"/>
		            </div>' : '').'
		            <div>
		              <div class="t-h-c-name">
		                <span><a href="'.BASE_URL.$tweet->username.'">'.$tweet->screenName.'</a></span>
		                <span>@'.$tweet->username.'</span>
		                <span>'.$getFromU->timeAgo($tweet->postedOn).'</span>
		              </div>
		              <div class="retweet-t-s-b-inner-right-text">
		                '.$getFromT->getTweetLinks($tweet->status).'
		              </div>
		            </div>
		          </div>
		        </div>
		      </div>
		      </div>' : '

		      <div class="t-show-popup" data-tweet="'.$tweet->tweetID.'">
		        <div class="t-show-head">
		          <div class="t-show-img">
		            <img src="'.BASE_URL.$tweet->profileImage.'"/>
		          </div>
		          <div class="t-s-head-content">
		            <div class="t-h-c-name">
		              <span><a href="'.$tweet->username.'">'.$tweet->screenName.'</a></span>
		              <span>@'.$tweet->username.'</span>
		              <span>'.$getFromU->timeAgo($tweet->postedOn).'</span>
		            </div>
		            <div class="t-h-c-dis">
		              '.$getFromT->getTweetLinks($tweet->status).'
		            </div>
		          </div>
		        </div>'.
		        ((!empty($tweet->tweetImage)) ?
		         '<!--tweet show head end-->
		              <div class="t-show-body">
		                <div class="t-s-b-inner">
		                 <div class="t-s-b-inner-in">
		                   <img src="'.BASE_URL.$tweet->tweetImage.'" class="imagePopup" data-tweet="'.$tweet->tweetID.'"/>
		                 </div>
		                </div>
		              </div>
		              <!--tweet show body end-->
		        ' : '').'

		      </div>').'
		      <div class="t-show-footer">
		        <div class="t-s-f-right">
		          <ul>
		          '.(($getFromU->loggedIn() === true) ? '
		            <li><button><i class="fa fa-share" aria-hidden="true"></i></button></li>
		            <li>'.((is_array($retweet) && isset($retweet['retweetID']) ? $retweet['retweetID'] === $tweet->tweetID : '') ? 
					'<button class="retweeted" data-tweet="'.$tweet->tweetID.'" data-user="'.$tweet->tweetBy.'"><i class="fa fa-retweet" aria-hidden="true"></i><span class = "retweetsCount">'.$tweet->retweetCount.'</span></button>' : 
					'<button class="retweet" data-tweet="'.$tweet->tweetID.'" data-user="'.$tweet->tweetBy.'"><i class="fa fa-retweet" aria-hidden="true"></i><span class = "retweetsCount">'.(($tweet->retweetCount > 0) ? $tweet->retweetCount : '').'</span></button>').'
					</li>
		            <li>'.((isset($likes['likeOn']) ? $likes['likeOn'] === $tweet->tweetID : '') ? 
			      					'<button class="unlike-btn" data-tweet="'.$tweet->tweetID.'" data-user="'.$tweet->tweetBy.'"><i class="fa fa-heart" aria-hidden="true"></i><span class="likesCounter">'.(($tweet->likesCount > 0) ? $tweet->likesCount : '' ).'</span></button>' : 
			      					'<button class="like-btn" data-tweet="'.$tweet->tweetID.'" data-user="'.$tweet->tweetBy.'"><i class="fa fa-heart-o" aria-hidden="true"></i><span class="likesCounter">'.(($tweet->likesCount > 0) ? $tweet->likesCount : '' ).'</span></button>').'
			      	</li>    
		            '.(($tweet->tweetBy === $user_id) ? '
		              <li>
			              <a href="#" class="more"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
			              <ul>
			                <li><label class="deleteTweet" data-tweet="'.$tweet->tweetID.'">Delete Tweet</label></li>
			              </ul>
		              </li>' : '').'

		           ' : '<li><button><i class="fa fa-share" aria-hidden="true"></i></button></li>
		                <li><button><i class="fa fa-retweet" aria-hidden="true"></i></button></li>
		                <li><button><i class="fa fa-heart" aria-hidden="true"></i></button></li>
		            ').'

		          </ul>
		        </div>
		      </div>
		    </div>
		    </div>
		    </div>';
  }
		?>
	</div>
	<!-- in left wrap-->
   <div class="popupTweet"></div>
<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/like.js"></script>
  <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/retweet.js"></script>
  <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/popuptweets.js"></script>
  <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/delete.js"></script>
  <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/comment.js"></script>
  <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/popupForm.js"></script>
  <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/search.js"></script>
  <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/hashtag.js"></script>
 
</div>
<!-- in center end -->

<div class="in-right">
	<div class="in-right-wrap">
		<!--==WHO TO FOLLOW==-->
           <!-- HERE -->
		<!--==WHO TO FOLLOW==-->
			
		<!--==TRENDS==-->
 	 	   <!-- HERE -->
	 	<!--==TRENDS==-->
	</div>
	<!-- in left wrap-->
</div>
<!-- in right end -->

   </div>
   <!--in full wrap end-->
 
  </div>
  <!-- in wrappper ends-->

</div>
<!-- ends wrapper -->
</body>
</html>