<?php
require('include/message.php');
if( $security->runSecurity($permission->admin, $user->username, 1, 1, 1) )
{
		echo('
<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">Pending IA Requests</h3>
								</div>
							</div>
					<form action="process.php" method="post">
					<input type="hidden" name="cmd" value="approve_ia" />
					<table class="minor_header">
					<tr>
						<td width="5%">
						</td>
						<td class="minor_header" width="25%">
							<h4 class="content_box_minor_header">
								Username
							</h4>
						</td>
						<td class="minor_header" width="70%">
							<h4 class="content_box_minor_header">
								Reason
							</h4>
						</td>
						<td class="minor_header" width="25%">
							<h4 class="content_box_minor_header">
								Time
							</h4>
						</td>
					</tr>
					');
					
			$sql = 'select id 
			from ia_request
			;';
			$result = mysql_query($sql);
			while( $row = mysql_fetch_array($result) )
			{
				$request = new Ia($row['id']);
				if( !$request->exists )
				{
				echo('There are no pending Requests.');
				}
				else
				{
				echo('
				<tr>
					<td>
						<input type="radio" name="id" value="' . $row['id'] . '" />
					</td>
					<td>
						<a class="members" href="index.php?view=profile&user=' . $request->username . '&ref=ia_list.php">' . $request->username . '</a>
					</td>
					<td>
						<p>' . $request->reason . '</p>
					</td>
					<td>
						' . dateFromTimestamp( $request->time ) . '
					</td>
				</tr>
				');
				}
			}
					
					echo('
					</table>
					<br />
					<input type="submit" value="Approve Selected" />
					<div>' . $form->error("security") . '</div>
					<div>' . $_SESSION['event'] . '</div>
					</form>
					<hr class="cb" />		
			<br />
		<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
										</div>
					</div>
					<div align="center"><div class="content_box_footer"></div></div>
				</div>
				<br />
				<br />
				<br />
				<br />
				<br />

			');
		unset($_SESSION['success']);
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
