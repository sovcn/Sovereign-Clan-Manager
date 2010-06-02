<?php
class Notification
{
var $id;
var $title;
var $text;
var $time;
var $username1;
var $username2;

//>>>>>>>   This contstructor function takes the username and finds out if they have any Notifications to display
//>>>>>>>   If they have notifications, it displays them in a popup.

function displayNots($username)
{
	$sql = 'select * from notifications where username1 = "' . $username . '" and done = 0;';
	$result = mysql_query($sql);
	if( $this->hasNots($result) )
	{

	echo('<div 
	   id="uniquename" 
	   style="display:true;"
	   class="popup">
	   
	   <div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">User Notification System</h3>
			</div>
			<div class="green_content">');
	   
		while( $row = mysql_fetch_array($result) )
		{
			$this->setInfo($row);
			echo('
				<h3 class="content_box_header">' . $this->title . '</h3>
				<p>' . $this->text . '</p>
					<br />
					<br />
					on ' . dateFromTimestamp_Long($this->time) . '');
			$this->setDone($row['id']);
		}
	echo('
	<br />
	<br />
	<br />
	<a onclick="HideContent(\'uniquename\'); return true;"
	   href="javascript:HideContent(\'uniquename\')">
	[Close this window]
	</a>
	</div>
			<div class="green_content_bottom">
			</div>
		</div>
	</div>');
}
}

function hasNots( $result )
{
	if( mysql_num_rows($result) == 0 )
	return false;
	else
	return true;
}

function setInfo($row)
{
	$this->id = $row['id'];
	$this->text = $row['text'];
	$this->time = $row['time'];
	$this->title = $row['title'];
	$this->username1 = $row['username1'];
	$this->username2 = $row['username2'];
}

function setNot($username1, $username2, $title, $text)
{
$sql = 'INSERT INTO `notifications` 
		(`id`, `title`, `username1`, `username2`, `text`, `time`, `done`) 
		VALUES 
(NULL, \'' . $title . '\', \'' . $username1 . '\', \'' . $username2 . '\', \'' . $text . '\', \'' . time() . '\', \'0\');';
$result = mysql_query($sql);

}

function notifyAdmin($title, $text)
{
	$sql = 'select * from users where rank >= 21 and disabled = 0;';
	$result = mysql_query($sql);
	while( $row = mysql_fetch_object($result) )
	{
		$this->setNot( $row->username, 0, $title, $text );
	}
}

function setDone($id)
{
$sql = 'update notifications
		set done = 1
		where id = ' . $id . '
		limit 1;';
$result = mysql_query($sql);
}

};


$notification = new Notification;
?>
