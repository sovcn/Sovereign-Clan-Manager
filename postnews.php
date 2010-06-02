<?php
if( $security->runSecurity($permission->news, $user->username, 1, 1, 1) )
{
	if($_SESSION['success'])
	{
		echo('<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">New News Post</h3>
								</div>
							</div>
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									News Posted Successfully
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
									<h3 class="content_box_header">Posting News</h3>
								</div>
							</div>
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '
								</h4>
							</div>
		<form action="process.php" method="POST">
		<input type="hidden" name="cmd" value="post_news" />
		<hr class="cb" />
		<table class="form" border="0" cellspacing="5" cellpadding="3">
			<tr>
				<td>
				<h4 class="form_label_cb">Title:</h4>
				</td>
				<td>
					<input type="text" class="text" name="title" value="' . $array['title'] . '" />
				</td>
				<td>
					<font class="error">' . $form->error("title") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">Description:</h4>
				</td>
				<td>
					<input type="text" class="text" name="description" value="' . $array['description'] . '"  />
				</td>
				<td>
					<font class="error">' . $form->error("description") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">News:</h4>
				</td>
				<td>
					<textarea class="textarea" name="news">' . $array['news'] . '</textarea>
				</td>
				<td>
					<font class="error">' . $form->error("news") . '</font>
				</td>
			</tr>
				<tr>
				<td colspan="0">
					<input type="submit" value="Post News" />
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
