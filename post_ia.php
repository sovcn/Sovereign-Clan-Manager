<?php
if( $security->runSecurity(1, $user->username, 1, 1, 1) )
{
	if($_SESSION['success'])
	{
		echo('<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">Posting IA Request</h3>
								</div>
							</div>
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									IA Request Submited
								</h4>
							</div>
						<p class="event">' . $_SESSION['event'] . '</p>
		<br /><a class="return" href="index.php?view=console">Click Here to return to your console.</a>
		
								</div>
					</div>
					<div align="center"><div class="content_box_footer"></div></div>
				</div>');
		
		unset($_SESSION['success']);
		unset($_SESSION['event']);
	}
	else  // If not successful in adding the member or if no attempt as been made
	{
		$array = $_SESSION['value'];  // Gets the values that the user submited in their first attempt so that they can be used again
		unset($_SESSION['value']);
		
		echo('
		<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">Posting IA Request</h3>
								</div>
							</div>
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '
									' . $form->error("security") . '
								</h4>
							</div>
		<form action="process.php" method="POST">
		<input type="hidden" name="cmd" value="post_ia" />
		<hr class="cb" />
		<table class="form" border="0" cellspacing="5" cellpadding="3">
			<tr>
				<td>
				<h4 class="form_label_cb"><- Reason for being IA -></h4>
				</td>
			</tr>
			<tr>
				<td>
				Please write a brief description of why you need to go inactive and how long you will be gone.
				</td>
			</tr>
			<tr>
				<td>
					<textarea class="textarea" cols="50" rows="10" name="reason">' . $array['reason'] . '</textarea>
				</td>
			</tr>
			<tr>
				<td>
					<font class="error">' . $form->error("title") . '</font>
				</td>
			</tr>
				<tr>
				<td colspan="0">
					<input type="submit" value="Post IA Request" />
				</td>
			</tr>
		</table>
		</form>
		<hr class="cb" />
		<br />
		<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
								</div>
					</div>
					<div align="center"><div class="content_box_footer"></div></div>
				</div>');
		unset($_SESSION['event']);
	}
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
