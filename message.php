<?php
require('include/message.php');
if( $security->runSecurity(1, $user->username, 1, 1, 1) )
{
$message = new Message;
$message->getInfo($user->username, $_GET['id']);
$message->setRead($user->username, $_GET['id']);
		echo('
<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">' . $message->title . '</h3>
								</div>
							</div>
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									Sent by <font class="big">' . $db->titleFromUsername($message->from) . '</font> - ' . dateFromTimestamp_Long( $message->date ) . '
								</h4>
							</div>
					<hr class="cb" />	
					<p class="cb">
						' . $message->text . '
					</p>
					<hr class="cb" />	
					<form action="index.php?view=user_personal&cmd=sendMessage" method="post">
						<input type="hidden" name="reply" value="' . $message->from . '" />
						<input type="hidden" name="title" value="' . $message->title . '" />
						<input type="submit" value="Reply" />
					</form>
			<br />
		<div><a class="return" href="index.php?view=console">Click Here to return to your Console</a></div>
		<div><a class="return" href="index.php?view=inbox">Click Here to return to your Inbox</a></div>
										</div>
					</div>
					<div align="center"><div class="content_box_footer"></div></div>
				</div>
			');
		unset($_SESSION['event']);
			
}
else
{
	echo('
	<SCRIPT LANGUAGE="JavaScript">
	window.location="index.php?view=security_error";
	</script>
	');
}
?>
