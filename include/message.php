<?php
class Message
{
var $id;
var $title;
var $text;
var $from;
var $to;
var $date;
var $read;
var $messages;
var $class;


function getInfo($username, $id)
{
	$sql = 'select * 
			from messages
			where messages.to = "' . $username . '"
			and id = ' . $id . '
			limit 1;';
	$result = mysql_query($sql);
	$array = mysql_fetch_array($result);
	if(mysql_num_rows($result) == 0)
		$this->messages = 0;
	else
	{
		$this->id = $id;
		$this->messages = mysql_num_rows($result);
		$this->title = $array['title'];
		$this->text = $array['text'];
		$this->from = $array['from'];
		$this->to = $username;
		$this->date = $array['date'];
		$this->read = $array['read'];
		
		if($this->read == 1)
			$this->class = 'read';
		else if($This->read == 0)
			$this->class = 'not_read';
	}

}

function setRead()
{
$sql = 'UPDATE `messages` 
		SET `read` = \'1\' 
		WHERE `messages`.`id` = ' . $this->id . '
		LIMIT 1;';
$result = mysql_query($sql);
}

};


class PMsystem
{
	function displayMessage($id)
	{
		global $db, $user, $file_management;
		$message = new Message;
		$message->getInfo($user->username, $id);
		$message->setRead($user->username, $id);
			
		echo('<div class="green_contentbox">
			<div class="green_content_top">
				<table width="95%" class="news_header" cellpadding="0" cellspacing="0"><tr><td class="title" width="50%"><h3 class="content_box_header">' . $message->title . '</h3></td><td class="time">' . dateFromTimestamp_Long( $message->date ) . '</td></tr></table>
			</div>
			<div class="green_content">
				<table width="95%" class="general_info" cellpadding="3" cellspacing="0">
				<tr>
				<td class="news_left" valign="top">
				<div class="news_thumbnail">
					<a href="index.php?view=profile&user=' . $message->from . '">
					'); 
					$file_management->profileImage( $message->from, 110, 95 );
					echo('
					</a>
				</div>
				<div class="news_left">
				<span>' . $db->titleFromUsername( $message->from ) . '</span>
				</div>
				</td>
				<td valign="top">
			  <p class="news" width="100%" style="overflow: auto;">' . $message->text . '</p>
			  </td>
			  </tr>
			  </table>
			  <br />
			  <hr />
			  <form action="index.php?view=user_personal&cmd=sendMessage" method="post">
						<input type="hidden" name="reply" value="' . $message->from . '" />
						<input type="hidden" name="title" value="' . $message->title . '" />
						<input type="submit" value="Reply" />
					</form>
				<hr />
						<div><a class="return" href="index.php?view=console">Click Here to return to your Console</a></div>
		<div><a class="return" href="index.php?view=user_personal&cmd=displayInbox">Click Here to return to your Inbox</a></div>
		<hr />
			  </div>
			<div class="green_content_bottom">
			</div>
		</div>

				');
	}
		
	function displayInbox()
	{
		global $db, $form, $user;
				echo('
<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Personal Messaging System - Inbox</h3>
			</div>
			<div class="green_content">
					<form action="process.php" method="post">
					<input type="hidden" name="cmd" value="delete_message" />
					<table class="general_info" width="95%" cellpadding="0" cellspacing="0">
					<tr>
						<td width="5%">
						</td>
						<td class="minor_header" width="25%">
							<h4 class="content_box_minor_header">
								Title
							</h4>
						</td>
						<td class="minor_header" width="25%">
							<h4 class="content_box_minor_header">
								Preview
							</h4>
						</td>
						<td class="minor_header" width="25%">
							<h4 class="content_box_minor_header">
								From
							</h4>
						</td>
						<td class="minor_header" width="10%">
							<h4 class="content_box_minor_header">
								Date
							</h4>
						</td>
						<td class="minor_header" width="10%" >
							<h4 class="content_box_minor_header">
								Read?
							</h4>
						</td>
					</tr>
					');
					
			$sql = 'select id 
			from messages
			where messages.to = "' . $user->username . '"
			order by date desc
			;';
			$result = mysql_query($sql);
			while( $row = mysql_fetch_array($result) )
			{
				$message = new Message;
				$message->getInfo($user->username, $row['id']);
				if( $db->messages($user->username) == 0 )
				{
				echo('You do not have any messages.');
				}
				else
				{
				echo('
				<tr>
					<td>
						<input type="checkbox" name="delete[]" value="' . $message->id . '" />
					</td>
					<td>
						<a class="' . $message->class . '" href="index.php?view=user_personal&cmd=displayInbox&mid=' . $message->id . '">' . $message->title . '</font>
					</td>
					<td>
						' . trunc($message->text, 5) . '
					</td>
					<td>
						' . $db->titleFromUsername($message->from) . '
					</td>
					<td>
						' . dateFromTimestamp( $message->date ) . '
					</td>
					<td>
						');
						if($message->read == 1)
							echo('Yes');
						if($message->read == 0)
							echo('No');
						echo('
					</td>
				</tr>
				');
				}
			}
					
					echo('
					</table>
					<br />
					<hr class="cb" />	
					<input type="submit" value="Delete Selected" />
					<div>' . $form->error("security") . '</div>
					<div>' . $_SESSION['event'] . '</div>
					</form>
					<hr class="cb" />	
						<a class="return" href="index.php?view=console">Click Here to return to your Console</a>
			</div>
			<div class="green_content_bottom">
			</div>
		</div>


			');
	}

};


?>
