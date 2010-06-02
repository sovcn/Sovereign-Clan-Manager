<?php

class Tag
{
	var $id;
 	var $username;
	var $text;
	var $time;
	
	function Tag( $id, $username, $text, $time )
	{
		$this->id = $id;
		$this->username = $username;
		$this->text = $text;
		$this->time = $time;
	}
};

class Tagbox
{
	var $tags;
	function displayTags()
	{
		global $file_management, $db, $user, $permission;
		$sql = 'select * from tagbox order by time desc limit 10;';
		$result = mysql_query($sql);
		while( $row = mysql_fetch_array($result) )
		{
			$this->tags[$row['id']] = new Tag( $row['id'], $row['username'], $row['text'], $row['time'] );
		}
		foreach( $this->tags as $tag )
		{
			echo('
				<div class="tag">
					<a href="index.php?view=profile&user=' . $tag->username . '"><span class="tag_image">');
					 $file_management->profileImage($tag->username, 40, 40); 
					 echo('
					 </span></a>
					<a href="index.php?view=profile&user=' . $tag->username . '"><h6 class="tagbox">' . $db->titleFromUsername($tag->username) . '</h6></a>
					<span class="tag">' . $tag->text . '</span>
					');
				if( $db->rank_numFromUsername($user->username) >= $permission->getPermission('tagbox_admin') || $user->username == $tag->username )
				{
					echo('
					<div><form style="margin: 0; padding: 0;" action="process.php" method="post"><input  style="margin: 0; padding: 0;" type="hidden" name="cmd" value="deleteTag" /><input type="hidden" name="tagId" value="' . $tag->id . '" /><input style="margin: 0; padding: 0;" type="image" src="templates/green/images/deletetag_48.png" /></form></div>');
					}
					echo('
				</div>
			');
		}
		
	}
	
	function displayTags_UM()
	{
		global $file_management, $db, $user, $permission;
		$sql = 'select * from tagbox order by time desc limit 20;';
		$result = mysql_query($sql);
		while( $row = mysql_fetch_array($result) )
		{
			$this->tags[$row['id']] = new Tag( $row['id'], $row['username'], $row['text'], $row['time'] );
		}
		foreach( $this->tags as $tag )
		{
			echo('	<div class="tag">
					<h6>' . $db->titleFromUsername($tag->username) . '</h6>
					<p>' . $tag->text . '</p>
					</div>
				
			');
		}
		
	}
	
	function newTag( $username, $text )
	{
		global $db, $form, $config_tagbox;
	
		/* Title Error Checking */
		$field = "tagbox";
		 if( !$username || strlen($username) == 0 )
			{$form->setError($field, " * You must be logged in * ");}
			
		if( !$text || strlen($text) == 0 )
			{$form->setError($field, " * You must enter a tag * ");}
			
		if( strlen($text) > $config_tagbox->getSetting('max_tag_length') )
			{$form->setError($field, " * Tag must be less than " . $config_tagbox->getSetting('max_tag_length') . " characters long * ");}
			
		if( $form->num_errors == 0 )  // If there were no errors with the submitted information
		{
			$sql = 'INSERT INTO `tagbox` 
						(`id`, `text`, `time`, `username`) 
					VALUES 
						(NULL, \'' . $text . '\', \'' . time() . '\', \'' . $username . '\');';
			$result = @mysql_query($sql) or die("mysql error");
						
			$db->setLoginInfo($username, time(), $_SERVER['REMOTE_ADDR']);
			return true;
		}
		else
		{
			return false;
		}
		
	}
	
	function isOwner( $id, $username )
	{
		$sql = 'select id from tagbox where username = "' . $username . '" and id = ' . $id . ' limit 1;';
		$result = mysql_query($sql);
		if( mysql_num_rows($result) == 1 )
			return true;
		else
			return false;
	}
	
	function deleteTag( $id, $username )
	{
		global $db, $form, $config_tagbox, $permission;
	
		/* Title Error Checking */
		$field = "tagbox";
		 if( !$username || strlen($username) == 0 )
			{$form->setError($field, " * You must be logged in * ");}
			
		if( !$id || strlen($id) == 0 )
			{$form->setError($field, " * No tag id selected * ");}
			
		if( !$this->isOwner( $id, $username) && $db->rank_numFromUsername($username) < $permission->getPermission('tagbox_admin') )
			{$form->setError($field, " * You must be a tagbox admin or the poster of that tag * ");}
			
		if( $form->num_errors == 0 )  // If there were no errors with the submitted information
		{
			$sql = 'DELETE FROM `tagbox` WHERE `tagbox`.`id` = ' . $id . ' LIMIT 1;';
			$result = @mysql_query($sql) or die("mysql error");
						
			$db->setLoginInfo($username, time(), $_SERVER['REMOTE_ADDR']);
			return true;
		}
		else
		{
			return false;
		}
	}	

};


?>