<?php
class Tweet extends User
{
	
	function _construct($pdo)
	{
		$this->pdo = $pdo;
	}
	
	public function tweets($user_id,$num)
	{
	    $stmt = $this->pdo->prepare("SELECT * FROM `tweets` LEFT JOIN `users` ON `tweetBy` = `user_id` WHERE `tweetBy` = :user_id AND `retweetID` = '0' OR `tweetBy` = `user_id` AND `retweetBy` != :user_id LIMIT :num");
	    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
		$stmt->bindParam(":num", $num, PDO::PARAM_INT);
		$stmt->execute();
		$tweets = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		foreach($tweets as $tweet)
		{
			$likes = $this->likes($user_id, $tweet->tweetID); 
			$retweet = $this->checkRetweet($tweet->tweetID, $user_id);
			$user = $this->userData($tweet->retweetBy);
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
						<div class="t-show-popup" data-tweet="'.$tweet->tweetID.'">
						<div class="t-show-head">
			          <div class="t-show-img">
			        		<img src="'.BASE_URL.$user->profileImage.'"/>
			        	</div>
				<div class="t-s-head-content">
					<div class="t-h-c-name">
						<span><a href="'.BASE_URL.$user->username.'">'.$user->screenName.'</a></span>
						<span>@'.$user->username.'</span>
						<span>'.$this->timeAgo( (is_array($retweet) && $retweet['postedOn']) ? $retweet['postedOn'] : "" ).'</span>
					</div>
					<div class="t-h-c-dis">
						'.$this->getTweetLinks($tweet->retweetMsg).'
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
								<span>'.$this->timeAgo($tweet->postedOn).'</span>
							</div>
							<div class="retweet-t-s-b-inner-right-text">		
								'.$this->getTweetLinks($tweet->status).'
							</div>
						</div>
					</div>
				</div>
			</div>
			</div>' : '
	<div class="t-show-popup" data-tweet="'.$tweet->tweetID.'">
		<div class="t-show-head">
			<div class="t-show-img">
				<img src="'.$tweet->profileImage.'"/>
			</div>
			<div class="t-s-head-content">
				<div class="t-h-c-name">
					<span><a href="'.$tweet->username.'">'.$tweet->screenName.'</a></span>
					<span>@'.$tweet->username.'</span>
					<span>'.$this->timeAgo($tweet->postedOn).'</span>
				</div>
				<div class="t-h-c-dis">
					'.$this->getTweetLinks($tweet->status).'
				</div>
			</div>
		</div>'.
		((!empty($tweet->tweetImage)) ? 
		 '<!--tweet show head end-->
		<div class="t-show-body">
		  <div class="t-s-b-inner">
		   <div class="t-s-b-inner-in">
		     <img src="'.$tweet->tweetImage.'" class="imagePopup" data-tweet="'.$tweet->tweetID.'"/>
		   </div>
		  </div>
		</div>
		<!--tweet show body end-->
		' : '').'
	 </div>').'
	<div class="t-show-footer">
		<div class="t-s-f-right">
			<ul> 
				<li><button><i class="fa fa-share" aria-hidden="true"></i></button></li>	
				<li>'.((is_array($retweet) && isset($retweet['retweetID']) ? $retweet['retweetID'] === $tweet->tweetID : '') ? 
				'<button class="retweeted" data-tweet="'.$tweet->tweetID.'" data-user="'.$tweet->tweetBy.'"><i class="fa fa-retweet" aria-hidden="true"></i><span class = "retweetsCount">'.$tweet->retweetCount.'</span></button>' : 
				'<button class="retweet" data-tweet="'.$tweet->tweetID.'" data-user="'.$tweet->tweetBy.'"><i class="fa fa-retweet" aria-hidden="true"></i><span class = "retweetsCount">'.(($tweet->retweetCount > 0) ? $tweet->retweetCount : '').'</span></button>').'
				</li>
				<li>'.((isset($likes['likeOn']) ? $likes['likeOn'] === $tweet->tweetID : '') ? 
			      					'<button class="unlike-btn" data-tweet="'.$tweet->tweetID.'" data-user="'.$tweet->tweetBy.'"><i class="fa fa-heart" aria-hidden="true"></i><span class="likesCounter">'.(($tweet->likesCount > 0) ? $tweet->likesCount : '' ).'</span></button>' : 
			      					'<button class="like-btn" data-tweet="'.$tweet->tweetID.'" data-user="'.$tweet->tweetBy.'"><i class="fa fa-heart-o" aria-hidden="true"></i><span class="likesCounter">'.(($tweet->likesCount > 0) ? $tweet->likesCount : '' ).'</span></button>').'
			      	</li>                   
					'.(($tweet->tweetBy === $user_id)? '
					<li>
					<a href="#" class="more"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
					<ul> 
					  <li><label class="deleteTweet" data-tweet="'.$tweet->tweetID.'">Delete Tweet</label></li>
					</ul>
				</li>' : '').'
			</ul>
		</div>
	</div>
</div>
</div>
</div>';
		}
	} 
	
	public function getUserTweets($user_id){
		$stmt = $this->pdo->prepare("SELECT * FROM `tweets` LEFT JOIN `users` ON `tweetBy` = `user_id` WHERE `tweetBy` = :user_id AND `retweetID` = '0' OR `retweetBy` = :user_id ORDER BY `tweetID` DESC");
		$stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
	
	public function addLike($user_id, $tweet_id, $get_id)
	{
		$stmt = $this->pdo->prepare("UPDATE `tweets` SET `likesCount` = `likesCount` +1 WHERE `tweetID` = :tweet_id");
		$stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
		$stmt->execute();
		
		$this->create('likes', array('likeBy' => $user_id, 'likeOn' => $tweet_id)); 
	}
	
		public function unlike($user_id, $tweet_id, $get_id)
	{
		$stmt = $this->pdo->prepare("UPDATE `tweets` SET `likesCount` = `likesCount` -1 WHERE `tweetID` = :tweet_id");
		$stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
		$stmt->execute();
		
		$this->create('likes', array('likeBy' => $user_id, 'likeOn' => $tweet_id));
	}
	
	public function likes($user_id, $tweet_id){
		$stmt = $this->pdo->prepare("SELECT * FROM `likes` WHERE `likeBy` = :user_id AND `likeOn` = :tweet_id");
		$stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
		$stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	} 
	
	public function getTrendByHash($hashtag){
		$stmt = $this->pdo->prepare("SELECT * FROM `trends` WHERE `hashtag` LIKE :hashtag LIMIT 5");
		$stmt->bindValue(":hashtag", $hashtag.'%');
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
	
	public function getMention($mention)
	{
		$stmt = $this->pdo->prepare("SELECT `user_id`,`username`,`screenName`,`profileImage` FROM `users` WHERE `username` LIKE :mention OR `screenName` LIKE :mention LIMIT 5");
		$stmt->bindValue(':mention', $mention.'%');
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
	
	public function addTrend($hashtag)
	{
		preg_match_all("/#+([a-zA-Z0-9_]+)/i", $hashtag, $matches);
		if($matches)
		{
			$result = array_values($matches[1]);
		}
		$sql = "INSERT INTO `trends` (`hashtag`, `createdOn`) VALUES (:hashtag, CURRENT_TIMESTAMP)";
		foreach ($result as $trend) {
			if($stmt = $this->pdo->prepare($sql)){
				$stmt->execute(array(':hashtag' => $trend));
			}
		}
	}
	public function getTweetLinks($tweet)
	{
		$tweet = preg_replace("/(https?:\/\/)([\w]+.)([\w\.]+)/", "<a href='$0' target='_blank'>$0</a>", $tweet);
		$tweet = preg_replace("/#([\w]+)/", "<a href='http://localhost/twitter/hashtag/$1'>$0</a>", $tweet);
		$tweet = preg_replace("/@([\w]+)/", "<a href='http://localhost/twitter/$1'>$0</a>", $tweet);
		return $tweet;
	}
	
	public function getPopupTweet($tweet_id){
		$stmt = $this->pdo->prepare("SELECT * FROM `tweets`,`users` WHERE `tweetID` = :tweet_id AND `tweetBy` = `user_id`");
		$stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_OBJ);
	}
	
	public function retweet($tweet_id, $user_id, $get_id, $comment)
	{
		$stmt = $this->pdo->prepare("UPDATE `tweets` SET `retweetCount` = `retweetCount`+1 WHERE `tweetID` = :tweet_id AND `tweetBy` = :get_id");
		$stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
		$stmt->bindParam(":get_id", $get_id, PDO::PARAM_INT);
		$stmt->execute();
		
		$stmt = $this->pdo->prepare("INSERT INTO `tweets` (`status`,`tweetBy`,`retweetID`,`retweetBy`,`tweetImage`,`postedOn`,`likesCount`,`retweetCount`,`retweetMsg`) SELECT `status`,`tweetBy`,`tweetID`,:user_id,`tweetImage`,`postedOn`,`likesCount`,`retweetCount`,:retweetMsg FROM `tweets` WHERE `tweetID` = :tweet_id");
		$stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
		$stmt->bindParam(":retweetMsg", $comment, PDO::PARAM_STR);
		$stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
		$stmt->execute();
	}
	
	public function checkRetweet($tweet_id, $user_id){
		$stmt = $this->pdo->prepare("SELECT * FROM `tweets` WHERE `retweetID` = :tweet_id AND `retweetBy` = :user_id OR `tweetID` = :tweet_id and `retweetBy` = :user_id");
		$stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
		$stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	public function comments($tweet_id){
		$stmt = $this->pdo->prepare("SELECT * FROM `comments` LEFT JOIN `users` ON `commentBy` = `user_id` WHERE `commentOn` = :tweet_id");
		$stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);  
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}
	
	public function countTweets($user_id){  
		$stmt = $this->pdo->prepare("SELECT COUNT(`tweetID`) AS `totalTweets` FROM `tweets` WHERE `tweetBy` = :user_id AND `retweetID` = '0' OR `retweetBy` = :user_id");
		$stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
		$stmt->execute();
		$count = $stmt->fetch(PDO::FETCH_OBJ);
		echo $count->totalTweets;
	}
	
	public function countLikes($user_id){
		$stmt = $this->pdo->prepare("SELECT COUNT(`likeID`) AS `totalLikes` FROM `likes` WHERE `likeBy` = :user_id");
		$stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
		$stmt->execute();
		$count = $stmt->fetch(PDO::FETCH_OBJ);
		echo $count->totalLikes;
	} 
	
}

?>





















