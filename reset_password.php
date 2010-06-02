<?php
if( $security->runSecurity($permission->admin, $user->username, 1, 1, 1) )
{
	if($_SESSION['success'])
	{
		echo('<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">Reseting User\'s Password</h3>
								</div>
							</div>
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									Password Reset
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
	else
	{
		echo('
			<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">Reseting User\'s Password</h3>
								</div>
							</div>
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '
									' . $form->error("security") . '
								</h4>
							</div>
			<form action="process.php" method="post">
			<table class="form" cellpadding="0" cellspacing="5">
			<input type="hidden" name="cmd" value="reset" />
			<tr>
				<td>
					<h4 class="form_label_cb">Name: </h4>
				</td>
				<td>
					<select class="select" name="username">
						');
					select_user_all(0);
					echo('</select>
				</td>
				<td>
					<font class="error">' . $form->error("username") . '</font>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" value="Reset Password" />
				</td>
			</tr>
			</table>
			</form>
			<br />
		<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
												</div>
					</div>
					<div align="center"><div class="content_box_footer"></div></div>
				</div>
			');
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
