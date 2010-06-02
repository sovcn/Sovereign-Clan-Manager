<?php
class Profile
{
	var $username; 
	var $email;
	var $rank;
	var $rank_num;
	var $aim;
	var $title;
	var $recruiter;
	var $recruits;
	var $ip;
	var $joined;
	var $last_login;
	var $dsl;
	var $status;
	var $image;
	
	var $maingame;
	var $image_path;
	
	function Profile($username)
	{
		global $db, $config;
		$this->username = $username;
		$array = $db->getInfo($this->username);  // Gets the information on the logged in username
		//>>>   Assigns the information of the logged in user to the member data  <<<<<
		$this->rank = $db->getRankName($this->username);
		$this->rank_num = $array['rank'];
		$this->email = $array['email'];
		$this->aim = $array['aim'];
		$this->title = $db->getRankName($this->username) . ' ' . $array['displayname'];
		$this->recruiter = $array['recruiter'];
		$this->recruits = mysql_num_rows( $db->getRecruits($this->username) );
		$this->ip = $array['ip'];
		$this->joined = $array['joined'];
		$this->last_login = $array['last_login'];
		$this->status = $db->isActive($this->username, $config->getSetting('active_timeout'));
		$this->dsl = daysSinceTimestamp($this->last_login);
		$this->image = '<img src="images/' . $this->rank_num . '.jpg" alt="' . $this->rank . '" />';
		$this->location = $array['location'];
		$this->quote = $array['quote'];
		
		$this->maingame = $array['maingame'];
		$this->image_path = $array['image_path'];

	}

	function display_image( $width_max )
	{
		$imagesize = @getimagesize($this->image_path);
		$width = $imagesize[0];
		$height = $imagesize[1];
		if( $width > $width_max )
		{
			$ratio = $height / $width;
			
			$width = $width_max;
			$height = $width * $ratio;
		}
		echo('<img src="' . $this->image_path . '" width="' . $width . '" height="' . $height . '" />');
	}
};

class Music
{
	var $id;
	var $shuffle;
	var $autostart;
	var $color;
	var $hasMusic;
	
	function Music( $username )
	{
		$sql = 'select pp_playlist_id, pp_playlist_shuffle, pp_playlist_autostart, pp_playlist_color 
				from user_profile_settings 
				where username="' . $username . '" 
				limit 1;';
		$result = @mysql_query($sql) or die('mysql error');
		if( mysql_num_rows($result) > 0 )
			$this->hasMusic = 1;
		else
			$this->hasMusic = 0;
		$array = mysql_fetch_array($result);
		
		$this->id = $array['pp_playlist_id'];
		$this->shuffle = $array['pp_playlist_shuffle'];
		$this->autostart = $array['pp_playlist_autostart'];
		
		switch( $array['pp_playlist_color'] )
		{
			case 'black':
				$this->color = 'black';
			break;
			case 'gray':
				$this->color = 'regular';
			break;
			case 'blue':
				$this->color = 'blue';
			break;
			case 'purple':
				$this->color = 'purple';
			break;
			case 'pink':
				$this->color = 'pink';
			break;
			case 'red':
				$this->color = 'red';
			break;
			case 'green':
				$this->color = 'green';
			break;
			default:
				$this->color = 'black';
			break;
		}
	}
	
	function displayMusicPlayer()
	{
		echo('
		<div style="text-align: center; margin-left: auto; visibility:visible; margin-right: auto; width:450px;">
			<embed style="width:435px; visibility:visible; height:270px;" allowScriptAccess="never" src="http://www.musicplaylist.us/mc/mp3player-othersite.swf?config=http://www.musicplaylist.us/mc/config/config_' . $this->color . '');
		if( $this->shuffle )
			echo('_shuffle');	
			
			echo('.xml&mywidth=435&myheight=270&playlist_url=http://www.musicplaylist.us/loadplaylist.php?playlist=' . $this->id . '" menu="false" quality="high" width="435" height="270" name="mp3player" wmode="transparent" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" border="0"/>
			<BR><a href=http://www.musicplaylist.us><img src=http://www.musicplaylist.us/mc/images/create_' . $this->color . '.jpg border=0></a><a href=http://www.musicplaylist.us/standalone/' . $this->id . ' target=_blank><img src=http://www.musicplaylist.us/mc/images/launch_' . $this->color . '.jpg border=0></a><a href=http://www.musicplaylist.us/download/' . $this->id . '><img src=http://www.musicplaylist.us/mc/images/get_' . $this->color . '.jpg border=0></a> </div>

		');
	}
	
	function updateMusicSettings( $username, $id, $shuffle, $autostart, $color )
	{

		global $db, $form, $notification;
		
		$field = "security";
		 if( !$username || strlen($username) == 0 )
			{$form->setError($field, " * Must be logged in *");}
			
		$field = "pp_id";
		 if( !$id || strlen($id) <= 0 )
			{$form->setError($field, "* You must have an ID *");}
		
		$field = "shuffle";
		 if( strlen($shuffle) < 0 || strlen($shuffle) > 1 )
			{$form->setError($field, "* Value must be 1 or 0 *");}
	
		$field = "autostart";
		 if( strlen($autostart) < 0 || strlen($autostart) > 1 )
			{$form->setError($field, "* Value must be 1 or 0 *");}
			
		$field = "color";
		 if( !$color || strlen($color) <= 0 )
			{$form->setError($field, "* No value found for color *");}
			
		if( $form->num_errors == 0 )  // If there were no errors with the submitted information
		{
			if( $this->hasMusic )
			{
				$sql = 'UPDATE `user_profile_settings` SET 
						`pp_playlist_id` = \'' . $id . '\', `pp_playlist_shuffle` = \'' . $shuffle . '\', 
						`pp_playlist_autostart` = \'' . $autostart . '\', `pp_playlist_color` = \'' . $color . '\' 
						WHERE CONVERT(`user_profile_settings`.`username` USING utf8) = \'' . $username . '\' LIMIT 1;';
				$result = @mysql_query($sql) or die("Mysql Error");
			}
			else
			{
				$sql = 'INSERT INTO `user_profile_settings` 
						(`username`, `pp_playlist_id`, `pp_playlist_shuffle`, `pp_playlist_autostart`, `pp_playlist_color`) 
					    VALUES 
						(\'' . $username . '\', \'' . $id . '\', \'' . $shuffle . '\', \'' . $autostart . '\', \'' . $color . '\');';
				$result = @mysql_query($sql) or die("Mysql Error");
			}
			$event = 'Music settings updated by ' . $db->titleFromUsername($username) . '.';
			$db->addToLogs($event, $username, 0);
			$db->setLoginInfo($username, time(), $_SERVER['REMOTE_ADDR']);
			return $event;
		}
		else
		{
			return false;
		}
	
	}
};

class Friend
{
	var $id;
	var $username;
	var $time;
	var $top_friend;
	var $top_rank;
	
	function Friend( $id, $username, $time, $top_friend, $top_rank )
	{
		$this->id = $id;
		$this->username = $username;
		$this->time = $time;
		$this->top_friend = $top_friend;
		$this->top_rank = $top_rank;
	}
};

class Friends
{
	var $friends;
	var $top_friends;
	var $num_friends;
	var $num_top_friends;
	
	function setFriends( $username )
	{   $this->num_friends = 0;
		$sql = 'select * from friends where username="' . $username . '";';
		$result = @mysql_query($sql) or die("mysql_error");
		while( $row = mysql_fetch_array($result))
		{
			$this->friends[$row['id']] = new Friend( $row['id'], $row['friend'], $row['time'], $row['top_friend'], $row['top_rank']);
			$this->num_friends++;
		}
		
		foreach( $this->friends as $friend )
		{
			if( $friend->top_friend )
				{
				$this->top_friends[$friend->top_rank] = $friend->username;
				$this->num_top_friends++;
				}
				
		}
	}
	
	function updateTopFriends()
	{
		
	}
	
	function addFriend()
	{
	
	}
	
	function removeFriend()
	{
		
	}
	
	function numFriends()
	{
		
	}
	function fake_friend($height, $width)
	{
		echo('<div style="height: ' . $height . '; width: ' . $width . '; border: 0; margin: 0; padding: 0;"></div>');
	}

};

?>
