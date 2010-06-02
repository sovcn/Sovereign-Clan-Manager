<?php
if( $security->runSecurity(1, $user->username, 0, 1, 0) ) // Runs the disablement security test.
{
	// If the user is logged in  --  Display USER MANAGEMENT CONSOLE
	if($user->logged_in) 
	{
	$db->setLoginInfo($user->username, time(), $_SERVER['REMOTE_ADDR']);
	$notification->displayNots($user->username);
		
	echo('

				<div class="green_contentbox">
				<div class="green_content_top">
					<h3 class="content_box_header">User Console</h3>
				</div>
				<div class="green_content">
							');
							if($db->isIa($user->username) )
							{
							echo('
							<form action="process.php" method="post">
							<input type="hidden" name="cmd" value="remove_ia" />
							<input type="submit" value="Remove IA Status" />
							</form>
							
							');
							}
							echo('
			<div class="button_120"><a class="console" href="index.php?view=user_personal&cmd=editProfile">Edit Your Profile</a></div>
			<div class="button_120"><a class="console" href="index.php?view=user_personal&cmd=changePassword">Change Your Password</a></div>
			<div class="button_120"><a class="console" href="index.php?view=profile&user=' . $user->username . '&ref=console">View Your Account</a></div>
			<div class="button_108"><a class="console" href="index.php?view=user_personal&cmd=requestIa">Request Inactivity</a></div>
			<div class="button_62"><a class="console" href="index.php?view=logs&page=1">View Logs</a></div>
			');
			if( $security->hasPermission( $permission->getPermission('news') ) )
			{
				echo('
				<div class="button_62"><a class="console" href="index.php?view=user_administration&cmd=news">Post News</a></div>
				');
			}
			if( $security->hasPermission( $permission->getPermission('modify_music_settings') ) )
			{
				echo('
				<div class="button_79"><a class="console" href="index.php?view=user_form&cmd=modify_music_settings">Music Player</a></div>
				');
			}
			echo('</div>
				<div class="green_content_bottom">
				</div>
			</div>');
			
			//>>>>>>>>>>>>>>>>>>>>   MESSAGING CONSOLE <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		echo('
		<div class="green_contentbox">
				<div class="green_content_top">
					<h3 class="content_box_header">Personal Messaging Console</h3>
				</div>
				<div class="green_content">
					<div class="button_93"><a class="console" href="index.php?view=user_personal&cmd=sendMessage">Send a Message</a></div>
					<div class="button_153"><a class="console" href="index.php?view=user_personal&cmd=displayInbox">Inbox ( ' . $db->unreadMessages($user->username) . ' unread messages )</a></div>
				</div>
				<div class="green_content_bottom">
				</div>
			</div>
			');
			
			//>>>>>>>>>>>>>>>>>>>>>>>>>>>> TagBox Console <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<//
		
		if( $security->hasPermission( $permission->getPermission('tagbox_admin') ) )
		{
			echo('
			<div class="green_contentbox">
				<div class="green_content_top">
					<h3 class="content_box_header">Tag Box Console</h3>
				</div>
				<div class="green_content">
					<div class="button_120"><a class="console" href="index.php?view=user_form&cmd=tagbox_admin&do=modify_settings">Edit Tag Box Settings</a></div>
					<div class="button_79"><a class="console" href="index.php?view=user_form&cmd=tagbox_admin">Delete Tag</a></div>
				</div>
				<div class="green_content_bottom">
				</div>
			</div>
			');
			
		}
			//>>>>>>>>>>>>>>>>>>>>   MEMBER MANAGMENT CONSOLE <<<<<<<<<<<<<<<<<<<<<<<
		if( $security->hasPermission( $permission->getPermission('addmember') ) || 	$security->hasPermission( $permission->getPermission('disable') ) || $security->hasPermission( $permission->getPermission('enable') ) || $security->hasPermission( $permission->getPermission('setrank') ) || $security->hasPermission( $permission->getPermission('promote') ) || $security->hasPermission( $permission->getPermission('demote') ) || $security->hasPermission( $permission->getPermission('news') ) )
		{
		echo('
		<div class="green_contentbox">
				<div class="green_content_top">
					<h3 class="content_box_header">Member Management Console</h3>
				</div>
				<div class="green_content">
				');
					if( $security->hasPermission( $permission->getPermission('addmember') ) )
					{
						echo('
						<div class="button_93"><a class="console" href="index.php?view=manage_users&cmd=addmember">Add a Member</a></div>
						');
					}
					if( $security->hasPermission( $permission->getPermission('disable') ) )
					{
						echo('
						<div class="button_108"><a class="console" href="index.php?view=manage_users&cmd=disable">Disable a Member</a></div>
						');
					}
					if( $security->hasPermission( $permission->getPermission('enable') ) )
					{
						echo('
						<div class="button_108"><a class="console" href="index.php?view=manage_users&cmd=enable">Enable a Member</a></div>
						');
					}
					if( $security->hasPermission( $permission->getPermission('setrank') ) )
					{
						echo('
						<div class="button_62"><a class="console" href="index.php?view=manage_users&cmd=setrank">Set Rank</a></div>
						');
					}
					if( $security->hasPermission( $permission->getPermission('promote') ) )
					{
						echo('
						<div class="button_93"><a class="console" href="index.php?view=manage_users&cmd=promote">Promote Member</a></div>
						');
					}
					if( $security->hasPermission( $permission->getPermission('demote') ) )
					{
						echo('
						<div class="button_93"><a class="console" href="index.php?view=manage_users&cmd=demote">Demote Member</a></div>
						');
					}

			echo('
				</div>
				<div class="green_content_bottom">
				</div>
			</div>
');
		}
		
		//>>>>>>>>>>>>> INFORMATION MANAGEMENT CONSOLES <<<<<<<<<<<<<<<<<<<//
	
	$information =  new Information;
	if( $user->rank_num >= $information->minToModify() )
		$information->displayConsole();
		
		
	//>>>>>>>>>>>>>>> FILE MANAGEMENT CONSOLE <<<<<<<<<<<<<<<<<<<<//
	
	$file_management->displayConsole();
	
	
	
	// >>>>>>>>>>>>>>>   GROUP CONSOLES  <<<<<<<<<<<<<<<<<<<//
	$group = new Group;
	echo('
	<div class="green_contentbox">
				<div class="green_content_top">
					<h3 class="content_box_header">Group Consoles</h3>
				</div>
				<div class="green_content">
	<div class="button_153"><a class="console" id="showLink" style="display: true;"
	  onclick="ShowContent(\'consoles\'); return true; HideContent(\'showLink\');"
	   href="javascript:ShowContent(\'consoles\')">
	Show Group Consoles
	</a></div>
	</div>
				<div class="green_content_bottom">
				</div>
			</div>
	
	
	<div style="display: none;" id="consoles" >
	');	
	$group->displayConsoles($user->username);
	echo('</div>
	');
	
	//>>>>>>>>>>>>>>>>>>>>    ADMINISTRATION CONSOLE
		if( $security->hasPermission( $permission->getPermission('admin') ) )
			{
				echo('
			<div class="green_contentbox">
				<div class="green_content_top">
					<h3 class="content_box_header">Administration Console</h3>
				</div>
				<div class="green_content">
				<div class="button_93"><a class="console" href="index.php?view=user_administration&cmd=reset_password">Reset Password</a></div>
				<div class="button_153"><a class="console" href="index.php?view=user_administration&cmd=mass_message">Mass Message/Notification</a></div>
				<div class="button_120"><a class="console" href="index.php?view=user_administration&cmd=approve_ia">Approve Inactivity</a></div>');
				if( $security->hasPermission( $permission->getPermission('commander') ) )
					{
					echo('
					<div class="button_120"><a class="console" href="index.php?view=user_commander&cmd=edit_settings">Edit General Settings</a></div>
					');
					}
				if( $security->hasPermission( $permission->getPermission('commander') ) )
					{
					echo('
					<div class="button_79"><a class="console" href="index.php?view=user_commander&cmd=edit_template">Edit Template</a></div>
					');
					}
				if( $security->hasPermission( $permission->getPermission('commander') ) )
					{
					echo('
						<div class="button_93"><a class="console" href="index.php?view=user_commander&cmd=modify_permissions">Edit Permissions</a></div>
					');		
					}
				if( $security->hasPermission( $permission->getPermission('changename') ) )
					{
					echo('
				 	<div class="button_79"><a class="console" href="index.php?view=user_administration&cmd=changename">Change Name</a></div>
					');	
					}
					echo('</div>
				<div class="green_content_bottom">
				</div>
			</div>');
				}
		if( $security->hasPermission( 24 ) )
			{
				echo('
			<div class="green_contentbox">
				<div class="green_content_top">
					<h3 class="content_box_header">Group Administration Console</h3>
				</div>
				<div class="green_content">
				<div class="button_62"><a class="console" href="index.php?view=group_function&cmd=add_corps&id=1">Add Corps</a></div>
				<div class="button_79"><a class="console" href="index.php?view=group_function&cmd=add_division&id=1">Add Division</a></div>
				<div class="button_79"><a class="console" href="index.php?view=group_function&cmd=add_squadron&id=1">Add Squadron</a></div>
				
				');
			}
			
			echo('</div>
				<div class="green_content_bottom">
				</div>
			</div>
					<br/>
					<br/>
				<br />
				<a class="logout" href="process.php">Click here to logout</a>
				');
	}
	if(!$user->logged_in )
	{
		$array = $_SESSION['value'];  // Gets the values that the user submited in their first attempt so that they can be used again
		unset($_SESSION['value']);
		
		?>
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Login</h3>
			</div>
			<div class="green_content">
			<h6 align="center"><?php echo( $form->error("disabled") . $form->error("info") ); ?></h6>
		<?php
		
		echo('	<form action="process.php" method="post">
				<table class="general_info" width="95%" cellspacing="0" cellpadding="0">
					<tr>
						<td>
							<h5>Username:</h5>
						</td>
						<td> 
							<input type="text" class="text" name="username" value="' . $array['username'] . '" />
						</td>
						<td>
							<font class="error">' . $form->error("user") . '</font>
						</td>
					</tr>
					<tr>
						<td>
							<h5>Password:</h5>
						</td>
						<td>
							<input type="password" class="text" name="password" value="' . $array['password'] . '" />
						</td>
						<td>
							<font class="error">' . $form->error("pass") . '</font>
						</td>
					</tr>
					<tr>
						<td>
							<h5>Remember Me?</h5>
						</td>
						<td>
							<input type="checkbox" name="remember" />
						</td>
						<td>
							<font class="error">' . $form->error("remember") . '</font>
						</td>
					</tr>
					<tr>
						<td colspan="3" align="center">
							<input type="submit"  value="Login" />
						</td>
					</tr>
				</table>
				<input type="hidden" name="cmd" value="login" />
				</form>
				</div>
			<div class="green_content_bottom">
			</div>
		</div>
		');
		
		
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
