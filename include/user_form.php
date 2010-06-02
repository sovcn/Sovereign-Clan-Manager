<?php

class User_Form
{
	function loadForm( $cmd )
		{
			global $security, $permission, $user;
			if( $security->runSecurity($permission->getPermission($cmd), $user->username, 1, 1, 1) )
				{
					$this->$cmd();
				}
				else
				{
					echo('
					<SCRIPT LANGUAGE="JavaScript">
					window.location="index.php?view=security_error";
					</script>
					');
				}
		}
	
};

class User_Misc_Form extends User_Form
{
	function loadForm( $cmd, $do, $param)
	{
		global $security, $permission, $user;
		if( $security->runSecurity($permission->getPermission($cmd), $user->username, 1, 1, 1) )
		{
			$this->$cmd($do);
		}
		else
		{
			echo('
			<SCRIPT LANGUAGE="JavaScript">
			window.location="index.php?view=security_error";
			</script>
			');
		}	
	}
	
	function modify_music_settings()
	{
		global $db, $user, $form, $group, $games, $config, $array;
		$music = new Music($user->username);
		echo('
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Modifying Music Settings</h3>
			</div>
			<div class="green_content">
		
		
								<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '							
									' . $form->error("security") . '
								</h4>
							</div>
		<form action="process.php" method="POST">
		<input type="hidden" name="cmd" value="modify_music_settings" />
		<table class="form" border="0" cellspacing="0" cellpadding="3">
			<tr>
				<td>
				<h3 class="form_label_cb">Autostart?</h3>
				<p class="form_description">This determines whether or not your player will automatically begin playing once the page is loaded.</p>
				<font class="error">' . $form->error('autostart') . '</font>
				</td>
				<td>
					<input type="checkbox" value="1" name="autostart" checked="'); if( $music->autostart ){ echo('yes'); } else {echo('no');} echo('" />
				</td>
			</tr>
			<tr>
				<td>
				<h3 class="form_label_cb">Shuffle?</h3>
				<p class="form_description">This determines whether or not your player will play songs in a mixed up order.</p>
				<font class="error">' . $form->error('shuffle') . '</font>
				</td>
				<td>
					<input type="checkbox" value="1" name="shuffle"'); if( $music->shuffle ){ echo('checked="yes"'); } echo('" />
				</td>
			</tr>
			<tr>
				<td>
				<h3 class="form_label_cb">Color</h3>
				<p class="form_description">This determines the color of your music player</p>
				<font class="error">' . $form->error('color') . '</font>
				</td>
				<td>
					<select name="color">
						<option value="' . $music->color . '">' . $music->color . '</option>
						<option value="black">Black</option>
						<option value="regular">Gray</option>
						<option value="blue">Blue</option>
						<option value="purple">Purple</option>
						<option value="pink">Pink</option>
						<option value="red">Red</option>
						<option value="green">Green</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
				<h3 class="form_label_cb">Project Playlist - Playlist ID</h3>
				<p class="form_description">This is the ID of your Project Playlist playlist.  If you want to know how to get this, <a href="index.php?view=project_playlist" target="_blank">click here.</a></p>
				<div><a href="index.php?view=project_playlist" target="_blank">Project Playlist Tutorial</a></div>
				<font class="error">' . $form->error('pp_id') . '</font>
				</td>
				<td>
					<input class="text" type="text" name="pp_id" value="' . $music->id . '" />
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" value="Edit Settings" />
				</td>
			</tr>
		</table>
		</form>
		<br />
		<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
			<div class="green_content_bottom">
			</div>
		</div>	');
	}
	
	function modify_file_settings()
	{
		global $db, $user, $form, $group, $games, $config, $array, $config_file;
		echo('
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Modifying File Settings</h3>
			</div>
			<div class="green_content">
		
		
								<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '							
									' . $form->error("security") . '
								</h4>
							</div>
		<form action="process.php" method="POST">
		<input type="hidden" name="cmd" value="modify_file_settings" />
		<table class="form" border="0" cellspacing="5" cellpadding="3">
		');
		foreach( $config_file->config_list as $row )
		{
			echo('
			<tr>
				<td>
				<h3 class="form_label_cb">' . $row->name . '</h3>
				<p class="form_description">' . $row->description . '</p>
				<font class="error">' . $form->error($row->id) . '</font>
				</td>
				<td>
					<input size="10" type="text" class="text" name="' . $row->id . '" value="' . $row->value . '" />
				</td>
			</tr>
			');
		}
		echo('
			<tr>
				<td colspan="2" align="center">
					<input type="submit" value="Edit Config" />
				</td>
			</tr>
		</table>
		</form>
		<br />
		<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
			<div class="green_content_bottom">
			</div>
		</div>	');
	}
	
	function tagbox_admin($do)
	{
		switch( $do )
		{
		case 'modify_settings':
		global $db, $user, $form, $group, $games, $config, $array, $config_tagbox;
		echo('
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Modifying Tag Box Settings</h3>
			</div>
			<div class="green_content">
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '							
									' . $form->error("security") . '
								</h4>
							</div>
		<form action="process.php" method="POST">
		<input type="hidden" name="cmd" value="tagbox_admin" />
		<table class="general_info" width="95%" border="0" cellspacing="0" cellpadding="3">
		');
		foreach( $config_tagbox->config_list as $row )
		{
			echo('
			<tr>
				<td>
					<h3 class="form_label_cb">' . $row->name . '</h3>
					<p class="form_description">' . $row->description . '</p>
					<font class="error">' . $form->error($row->id) . '</font>
				</td>
				<td>
					<input size="10" type="text" class="text" name="' . $row->id . '" value="' . $row->value . '" />
				</td>
			</tr>
			');
		}
		echo('
			<tr>
				<td colspan="2" align="center">
					<input type="submit" value="Edit Config" />
				</td>
			</tr>
		</table>
		</form>
		<br />
		<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
			<div class="green_content_bottom">
			</div>
		</div>	');
		
		break;
		case 'delete':
		
		break;
		}
	}
	
	function upload($do)
	{
		global $form, $user, $file_management;
		switch( $do )
		{
			case 'new':
				echo('
				<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Uploading File</h3>
			</div>
			<div class="green_content">
									<div class="content_box_minor_header">
										<h4 class="content_box_minor_header">
											' . $_SESSION['event'] . '
											' . $form->error("security") . '
										</h4>
									</div>
									<hr class="cb" />
									<p class="form_description">
			This form allows you to upload a file to the websever.  You may only upload files in the following formats: .png, .jpg, .jpeg, .gif.
			any attempt to upload a file in a different format will result in an error.
			</p>
			<hr class="cb" />
				<form action="process.php" method="POST" enctype="multipart/form-data">
				<table class="form" border="0" cellspacing="0" cellpadding="0" align="center">
					<tr>
						<td>
						<h4 class="form_label_cb">Browse for File</h4>
						</td>
						<td colspan="2">
							<input type="file" class="file" name="file" />
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<font class="error">' . $form->error("type") . ' ' . $form->error("size") . ' ' . $form->error("exists") . '</font>
						</td>
					</tr>
					<tr>
						<td>
						<h4 class="form_label_cb">Name of File:</h4>
						</td>
						<td>
							<input type="text" class="text" name="name" value="' . $array['name'] . '" />
						</td>
						<td>
							<font class="error">' . $form->error("name") . '</font>
						</td>
					</tr>
					<tr>
						<td>
						<h4 class="form_label_cb">Description:</h4>
						</td>
						<td colspan="2">
							<textarea class="textarea" cols="40" rows="10" name="description">' . $array['description'] . '</textarea>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<font class="error">' . $form->error("name") . '</font>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<input type="submit" value="Upload File" />
						</td>
					</tr>
				</table>
				<input type="hidden" name="cmd" value="upload" />
				</form>
				<br />
				<hr />
			<div><a class="return" href="index.php?view=console">Click Here to return to your Console</a></div>
			<hr />
			</div>
			<div class="green_content_bottom">
			</div>
		</div>
			');
		break;
		case 'view':
			echo('
				<div class="green_contentbox">
							<div class="green_content_top">
								<h3 class="content_box_header">Viewing User Uplaods</h3>
							</div>
							<div class="green_content">
									<div class="content_box_minor_header">
										<h4 class="content_box_minor_header">
											' . $_SESSION['event'] . '
											' . $form->error("security") . '
										</h4>
									</div>
									<hr class="cb" />
									<p class="form_description">
										These are all of your uploads.  You may add to/delete/modify them as you please.
										 You have been provided with a url to them so that you can link to them from another website or
										 use them as a forum sig.
									</p>
									<hr class="cb" />
				<form action="process.php" method="post">
				<table class="form" border="0" cellspacing="0" cellpadding="0" align="center">
					<tr>
						<td align="center">
							<h4 class="form_label_cb">Delete?</h4>
						</td>
						<td align="center">
							<h4 class="form_label_cb">Thumbnail</h4>
						</td>
						<td align="center">
							<h4 class="form_label_cb">Name</h4>
						</td>
						<td align="center">
							<h4 class="form_label_cb">Description</h4>
						</td>
					</tr>
					');
					$file_management->displayUploads($user->username);
					echo('
					<tr>
						<td colspan="4" align="center">
							<input type="submit" value="Delete Selected" />
						</td>
					</tr>
				</table>
				<input type="hidden" name="cmd" value="delete_upload" />
				</form>
				<br />
				<form action="process.php" method="post">
					<input type="hidden" name="cmd" value="new_profile_image" />
					<table class="form" border="0" cellspacing="0" cellpadding="0" align="center">
						<tr>
							<td align="center">
								<input type="submit" value="Set as Profile Image" />
							</td>
						</tr>
						<tr>
							<td align="center">
								<select name="profile_path" class="select">
								');
								$sql = 'select name, path from upload where username = "' . $user->username . '" order by time desc;';
								$result = mysql_query($sql) or die( mysql_error() );
								while( $row = mysql_fetch_array($result) )
								{
									echo('<option value="' . $row['path'] . '">' . $row['name'] . '</option>');
								}
								echo('
								</select>
							</td>
						</tr>
					</table>
				</form>
				
			<hr />
			<div><a class="return" href="index.php?view=console">Click Here to return to your Console</a></div>
			<hr />
			</div>
			<div class="green_content_bottom">
			</div>
		</div>
			');
		break;	
		}
		
	}
};

class User_Management_Form extends User_Form
{
	function addmember()
	{
		global $db, $user, $form, $group, $games, $config, $array;
		
		echo('
			<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Add Member</h3>
			</div>
			<div class="green_content">
								<div class="content_box_minor_header">
									<h4 class="content_box_minor_header">
										' . $_SESSION['event'] . '
										' . $form->error("security") . '
									</h4>
								</div>
			<form action="process.php" method="POST">
			<table class="form" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
					<h4 class="form_label_cb">Username:</h4>
					</td>
					<td>
						<input type="text" class="text" name="username" value="' . $array['username'] . '" />
					</td>
					<td>
						<font class="error">' . $form->error("username") . '</font>
					</td>
				</tr>
				<tr>
					<td>
					<h4 class="form_label_cb">Email:</h4>
					</td>
					<td>
						<input type="text" class="text" name="email" value="' . $array['email'] . '"  />
					</td>
					<td>
						<font class="error">' . $form->error("email") . '</font>
					</td>
				</tr>
				<tr>
					<td>
					<h4 class="form_label_cb">AIM:</h4>
					</td>
					<td>
						<input type="text" class="text" name="aim" value="' . $array['aim'] . '"  />
					</td>
					<td>
						<font class="error">' . $form->error("aim") . '</font>
					</td>
				</tr>
				<tr>
					<td>
					<h4 class="form_label_cb">Main Game</h4>
					</td>
					<td>
						');
				$games->listRadioGames();
						echo('
					</td>
					<td>
						<font class="error">' . $form->error("game") . '</font>
					</td>
				</tr>
					<tr>
					<td colspan="0">
						<input type="submit" value="Add Member" />
					</td>
				</tr>
			</table>
			<input type="hidden" name="cmd" value="add" />
			</form>
			<br />
			<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
			<div class="green_content_bottom">
			</div>
		</div>
		');
	
	}

	function demote()
	{
		global $db, $user, $form, $group, $games, $config, $array;
		echo('
			<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Demoting a Member</h3>
			</div>
			<div class="green_content">
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '
									' . $form->error("security") . '
								</h4>
							</div>
			<form action="process.php" method="post">
			<table class="form" cellpadding="0" cellspacing="5">
			<input type="hidden" name="cmd" value="demote" />
			<tr>
				<td>
					<h4 class="form_label_cb">Name: </h4>
				</td>
				<td>
					<select class="select" name="username">
						');
					select_user(0, $user->rank_num);
					echo('</select>
				</td>
				<td>
					<font class="error">' . $form->error("username") . '</font>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" value="Demote" />
				</td>
			</tr>
			</table>
			</form>
			<br />
		
<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
			<div class="green_content_bottom">
			</div>
		</div>
			');
	}
	
	
	function disable()
	{
	global $db, $user, $form, $group, $games, $config, $array;

		echo('
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Disabling a Member</h3>
			</div>
			<div class="green_content">
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '
									' . $form->error("security") . '
									' . $form->error("username") . '
								</h4>
							</div>
			<form action="process.php" method="post">
			<table class="form" cellpadding="0" cellspacing="5">
			<input type="hidden" name="cmd" value="disable" />
			<p class="form_description">
			This form will allow you to disable a member.  Once a member has been disabled they will no longer be able to log in. Use your common sense when disabling members, you will be held responsible.
			</p>
			<tr>
				<td>
					<h4 class="form_label_cb">Name:</h4>
				</td>
				<td>
					<select class="select" name="username" size="20">
						');
				select_user(0, $user->rank_num);
					echo('</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" value="Disable" />
				</td>
			</tr>
			</table>
			</form>
			<br />
		
<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
			<div class="green_content_bottom">
			</div>
		</div>
			');
	}
	
	function enable()
	{
	global $db, $user, $form, $group, $games, $config, $array;
		echo('
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Enabling a Member</h3>
			</div>
			<div class="green_content">
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '
									' . $form->error("security") . '
									' . $form->error("username") . '
								</h4>
							</div>
			<form action="process.php" method="post">
			<table class="general_info" width="95%" cellpadding="3" cellspacing="0">
			<input type="hidden" name="cmd" value="enable" />
			<hr class="cb" />
		<p class="general_info">
		This form will allow you to remove a member from the disabled list.
		<br />Do not enable anyone that has been disabled by someone thats a higher rank than you without their permission.
		</p>
		<hr class="cb" />
			<tr>
				<td>
					<select class="select" name="username" size="20">
						');
				$sql = 'select username, displayname, ranks.name
						from users, ranks
						where disabled = 1
						and users.rank = ranks.id
						and users.rank < 25
						order by  username asc;';
				$result = mysql_query($sql);
				while( $row = mysql_fetch_array($result) )
					{
						echo('<option value="' . $row['username'] . '">' . $row['name'] . ' ' . $row['username'] . '</option>');
					
					}
					echo('</select>
				</td>
			</tr>
			<tr>
				<td align="center">
					<input type="submit" value="Enable" />
				</td>
			</tr>
			</table>
			</form>
			<br />
		<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
								</div>
			<div class="green_content_bottom">
			</div>
		</div>
			');
	}
	
	function promote()
	{
		global $db, $user, $form, $group, $games, $config, $array;
		echo('
			<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Promoting a Member</h3>
			</div>
			<div class="green_content">
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '
									' . $form->error("security") . '
								</h4>
							</div>
			<form action="process.php" method="post">
			<table class="form" cellpadding="0" cellspacing="5">
			<input type="hidden" name="cmd" value="promote" />
			<tr>
				<td>
					<h4 class="form_label_cb">Name: </h4>
				</td>
				<td>
					<select class="select" name="username">
						');
					select_user(0, $user->rank_num);
					echo('</select>
				</td>
				<td>
					<font class="error">' . $form->error("username") . '</font>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" value="Promote" />
				</td>
			</tr>
			</table>
			</form>
			<br />
		

<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
			<div class="green_content_bottom">
			</div>
		</div>
			');
	}
	
	function setrank()
	{
		global $db, $user, $form, $group, $games, $config, $array;
		echo('
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Modifying User Rank</h3>
			</div>
			<div class="green_content">
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '
									' . $form->error("security") . '
								</h4>
							</div>
			<form action="process.php" method="post">
			<table class="form" cellpadding="0" cellspacing="5">
			<input type="hidden" name="cmd" value="setrank" />
			<tr>
				<td>
					<h4 class="form_label_cb">Name:</h4>
				</td>
				<td>
					<select class="select" name="username">
						');
					if($user->rank_num == 25){select_user(0, 26);}
					else{select_user(0, $user->rank_num);}
					echo('</select>
				</td>
				<td>
					<font class="error">' . $form->error("username") . '</font>
				</td>
			</tr>
			<tr>
				<td>
					<h4 class="form_label_cb">New Rank:</h4>
				</td>
				<td>
					<select class="select" name="rank">
					');
					if($user->rank_num == 25){select_rank(26);}
					else{select_rank($user->rank_num);}
					echo('</select>
				</td>
				<td>
					<font class="error">' . $form->error("rank") . '</font>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" value="Set Rank" />
				</td>
			</tr>
			</table>
			</form>
			<br />
	
<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
			<div class="green_content_bottom">
			</div>
		</div>
			');
	}
};

class User_Personal_Form extends User_Form
{
	function loadForm( $cmd, $arg2 = 0 )
	{
		global $security, $permission, $user;
			if( $security->runSecurity(1, $user->username, 1, 1, 1) )
				{
					$this->$cmd($arg2);
				}
				else
				{
					echo('  
					<SCRIPT LANGUAGE="JavaScript">
					window.location="index.php?view=security_error";
					</script>
					');
				}
	}
	
	function displayInbox( $mid )
	{
		include_once('include/message.php');
		$inbox = new PMsystem();
		global $db, $user, $form, $group, $games, $config, $array;
		if( $mid )
		$inbox->displayMessage($mid);
		else
		$inbox->displayInbox();
	}
	
	function editProfile()
	{
		global $db, $user, $form, $group, $games, $config, $array;
		$profile = new Profile($user->username);
		echo('
				<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Modifying Profile Information</h3>
			</div>
			<div class="green_content">

							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '
								</h4>
							</div>
		<form action="process.php" method="POST">
		<input type="hidden" name="cmd" value="edit_profile" />
		<hr class="cb" />
		<table class="form" border="0" cellspacing="5" cellpadding="3">
			<tr>
				<td>
				<h4 class="form_label_cb">AIM:</h4>
				</td>
				<td>
					<input type="text" class="text" name="aim" value="' . $user->aim . '"  />
				</td>
				<td>
					<font class="error">' . $form->error("aim") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">Email</h4>
				</td>
				<td>
					<input type="text" class="text" name="email" value="' . $user->email . '"  />
				</td>
				<td>
					<font class="error">' . $form->error("email") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">Location</h4>
				</td>
				<td>
					<input type="text" class="text" name="location" value="' . $profile->location . '" />
				</td>
				<td>
					<font class="error">' . $form->error("location") . '</font>
				</td>
			</tr>
			<tr>
						<tr>
				<td>
				<h4 class="form_label_cb">Favorite Quote</h4>
				</td>
				<td>
					<textarea class="textarea" name="quote">
						' . $profile->quote . '
					</textarea>
				</td>
				<td>
					<font class="error">' . $form->error("quote") . '</font>
				</td>
			</tr>
				<td>
				<h4 class="form_label_cb">Main Game:</h4>
				</td>
				<td>');
					$games->listRadioGamesGroups($profile->maingame);
					echo('
				</td>
				<td>
					<font class="error">' . $form->error("game") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">Games Played:</h4>
				</td>
				<td>
					<table cellpadding="0" cellspacing="3px">
						<tr>
							<td>
								<img src="images/cs.gif" alt="Counter Strike" />
							</td>
							<td>
								<img src="images/css.gif" alt="Counter Strike: Source" />
							</td>
							<td>
								<img src="images/bw.gif" alt="Starcraft Brood War" />
							</td>
							<td>
								<img src="images/d2.gif" alt="Diablo 2" />
							</td>
							<td>
								<img src="images/wow.gif" alt="World of Warcraft" />
							</td>
							<td>
								<img src="images/h3.gif" alt="Halo 3" />
							</td>
							<td>
								<img src="images/gw.gif" alt="Guild Wars" />
							</td>
							<td>
								<img src="images/eaw.gif" alt="Guild Wars" />
							</td>
							<td>
								<img src="images/war3.gif" alt="Warcraft 3" />
							</td>
						</tr>
						<tr>
							<td>
							');
							if( $profile->cs )
							{
								echo('<input type="checkbox" name="cs" value="1" checked="yes" />');
								}
							else
							{
								echo('<input type="checkbox" name="cs" value="1" />');
								}
							echo('
							</td>
							<td>
							');
							if( $profile->css )
							{
								echo('<input type="checkbox" name="css" value="1" checked="yes" />');
								}
							else
							{
								echo('<input type="checkbox" name="css" value="1" />');
								}
							echo('
							</td>
							<td>
							');
							if( $profile->bw )
							{
								echo('<input type="checkbox" name="bw" value="1" checked="yes" />');
								}
							else
							{
								echo('<input type="checkbox" name="bw" value="1" />');
								}
							echo('
							</td>
							<td>
							');
							if( $profile->d2 )
							{
								echo('<input type="checkbox" name="d2" value="1" checked="yes" />');
								}
							else
							{
								echo('<input type="checkbox" name="d2" value="1" />');
								}
							echo('							</td>
							<td>
							');
							if( $profile->wow )
							{
								echo('<input type="checkbox" name="wow" value="1" checked="yes" />');
								}
							else
							{
								echo('<input type="checkbox" name="wow" value="1" />');
								}
							echo('							</td>
							<td>
							');
							if( $profile->h3 )
							{
								echo('<input type="checkbox" name="h3" value="1" checked="yes" />');
								}
							else
							{
								echo('<input type="checkbox" name="h3" value="1" />');
								}
							echo('							</td>
							<td>
							');
							if( $profile->gw)
							{
								echo('<input type="checkbox" name="gw" value="1" checked="yes" />');
								}
							else
							{
								echo('<input type="checkbox" name="gw" value="1" />');
								}
							echo('							</td>
							<td>
							');
							if( $profile->eaw )
							{
								echo('<input type="checkbox" name="eaw" value="1" checked="yes" />');
								}
							else
							{
								echo('<input type="checkbox" name="eaw" value="1" />');
								}
							echo('							</td>
							<td>
							');
							if( $profile->war3 )
							{
								echo('<input type="checkbox" name="war3" value="1" checked="yes" />');
								}
							else
							{
								echo('<input type="checkbox" name="war3" value="1"  />');
								}
							echo('</td>
						</tr>	
					</table>
				</td>
				<td>
					<font class="error">' . $form->error("gameplayed") . '</font>
				</td>
			</tr>
				<tr>
				<td colspan="0">
					<input type="submit" value="Edit Profile" />
				</td>
			</tr>
		</table>
		</form>
		<hr class="cb" />
		<br />
		

<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
			<div class="green_content_bottom">
			</div>
		</div>');
	}

	function changePassword()
	{
		global $db, $user, $form, $group, $games, $config, $array;
		echo('
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Changing Password</h3>
			</div>
			<div class="green_content">
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '
									' . $form->error("security") . '
								</h4>
							</div>
		<form action="process.php" method="POST">
		<input type="hidden" name="cmd" value="change_password" />
		<hr class="cb" />
		<table class="form" border="0" cellspacing="5" cellpadding="3">
			<tr>
				<td>
				<h4 class="form_label_cb">Current Password:</h4>
				</td>
				<td>
					<input size="15" type="password" name="curpass"  />
				</td>
				<td>
					<font class="error">' . $form->error("currentPassword") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">New Password:</h4>
				</td>
				<td>
					<input size="15" type="password" name="newpass"   />
				</td>
				<td>
					<font class="error">' . $form->error("newPassword") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">Confirm New Password:</h4>
				</td>
				<td>
					<input size="15" type="password" name="confnewpass"  />
				</td>
				<td>
					<font class="error">' . $form->error("confirmNewPassword") . '</font>
				</td>
			</tr>
				<tr>
				<td colspan="0">
					<input type="submit" value="Change Password" />
				</td>
			</tr>
		</table>
		</form>
		<hr class="cb" />
		<br />
		<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
			<div class="green_content_bottom">
			</div>
		</div>');
	}
	
	function requestIa()
	{
		global $db, $user, $form, $group, $games, $config, $array;
		echo('
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Posting IA Request</h3>
			</div>
			<div class="green_content">
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
			<div class="green_content_bottom">
			</div>
		</div>');
	}
	
	function sendMessage($to)
	{
		global $db, $user, $form, $group, $games, $config, $array;
		echo('
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Sending Personal Message</h3>
			</div>
			<div class="green_content">

							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '
								</h4>
							</div>
		<form action="process.php" method="POST">
		<input type="hidden" name="cmd" value="send_pm" />
		<hr class="cb" />
		<table class="form" border="0" cellspacing="5" cellpadding="3">
			<tr>
				<td>
				<h4 class="form_label_cb">To:</h4>
				</td>
				<td colspan="2">');
				if($_POST['reply'])
				{
				echo('
					Replying to ' . $db->titleFromUsername($_POST['reply']) . '
					<input type="hidden" name="to" value="' . $_POST['reply'] . '" />');
				}
				else if($to)
				{	
				echo('
					' . $db->titleFromUsername($to) . '
					<input type="hidden" name="to" value="' . $to . '" />');
				}
				else
				{
				echo('
					<select class="select" name="to">');
					select_user_all(0);
					echo('
					</select>');
				}
				echo('
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">Title:</h4>
				</td>
				<td>
					');
					if( $_POST['title'] )
					echo('<input type="text" class="text" name="title" value="Re: ' . $_POST['title'] . '" />');
					else
					echo('<input type="text" class="text" name="title" value="' . $array['title'] . '" />');
					
					echo('
				</td>
				<td>
					<font class="error">' . $form->error("title") . '</font>
				</td>
			</tr>
			<tr>
				<td valign="top">
				<h4 class="form_label_cb">Text:</h4>
				</td>
				<td class="wysiwyg">
					<textarea id="wysiwyg" class="textarea" name="text" cols="60" rows="15">' . $array['text'] . '</textarea>
					<script type="text/javascript">
					buttonPath = "js/images/wysiwygbuttons/"; //directory holding button images
					makeWhizzyWig("wysiwyg", "all");
					</script>
				</td>
				<td>
					<font class="error">' . $form->error("text") . '</font>
				</td>
			</tr>
				<tr>
				<td colspan="2" align="center">
					<input type="submit" value="Send" />
				</td>
			</tr>
		</table>
		</form>
		<hr class="cb" />
		<br />
		
<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
			<div class="green_content_bottom">
			</div>
		</div>');
	}
};

class User_Administration_Form extends User_Form
{

	function mass_message()
	{
		global $db, $user, $form, $group, $games, $config, $array;
		echo('
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Sending a Mass Message</h3>
			</div>
			<div class="green_content">
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '
									' . $form->error("security") . '
								</h4>
							</div>
		<form action="process.php" method="POST">
		<input type="hidden" name="cmd" value="send_mass_pm" />
		<hr class="cb" />
		<p class="form_description">
		This is the mass message form.  This will allow you to send a message or notification to several different user groups.  
		You can send to more than one group at a time by control clicking on the group name in the form below.
		</p>
		<hr class="cb" />
		<table class="form" border="0" cellspacing="3px" cellpadding="5px">
			<tr>
				<td colspan="3">
					<h3>Message or Notification</h3>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					Message<input type="radio" name="medium" value="1" /> - Notification<input type="radio" name="medium" value="2" />
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<font class="error">' . $form->error("medium") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">To:</h4>
				</td>
				<td colspan="2">
					<select name="to[]" size="25" multiple class="select" style="width: 500px;">
						<optgroup label="By Rank">
							<option value="1">Entire Clan</option>
							<option value="2">Commanders Only</option>
							<option value="3">Administrators Only</option>
							<option value="4">Officers Only</option>
							<option value="5">Warrant Officers Only</option>
							<option value="6">Enlisted Only</option>
							<option value="7">Administrators and Above</option>
							<option value="8">Officers and Above</option>
							<option value="9">Warrant Officers and Above</option>
							<option value="10">Trial Members(if applicable)</option>
						</optgroup>
						<optgroup label="By DSL">
							<option value="11">0 DSL</option>
							<option value="12">1 DSL</option>
							<option value="13">2 DSL</option>
							<option value="14">3 DSL</option>
							<option value="15">4 DSL</option>
							<option value="16">5 DSL</option>
							<option value="17">6 DSL</option>
							<option value="18">7 DSL</option>
						</optgoup>
						<optgroup label="By Group">
							<option value="19">Corps Leaders</option>
							<option value="20">Division Leaders</option>
							<option value="21">Squadron Leaders</option>
						</optgroup>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<font class="error">' . $form->error("to") . '</font>
				</td>
			</tr>
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
				<td valign="top">
				<h4 class="form_label_cb">Text:</h4>
				</td>
				<td class="wysiwyg">
					<textarea id="wysiwyg" class="textarea" name="text" cols="75" rows="15">' . $array['text'] . '</textarea>
					<script type="text/javascript">
					buttonPath = "js/images/wysiwygbuttons/"; //directory holding button images
					makeWhizzyWig("wysiwyg", "all");
					</script>
				</td>
				<td>
					<font class="error">' . $form->error("text") . '</font>
				</td>
			</tr>
				<tr>
				<td colspan="2" align="center">
					<input type="submit" value="Send" />
				</td>
			</tr>
		</table>
		</form>
		<hr class="cb" />
		<br />
		
<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
			<div class="green_content_bottom">
			</div>
		</div>');
	}
	
	function news()
	{
		global $db, $user, $form, $group, $games, $config, $array;
		echo('
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Posting News Bulletin</h3>
			</div>
			<div class="green_content">
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
				<td class="wysiwyg">
					<textarea id="wysiwyg" class="textarea" name="news" cols="60" rows="20">' . $array['news'] . '</textarea>
					<script type="text/javascript">
					buttonPath = "js/images/wysiwygbuttons/"; //directory holding button images
					makeWhizzyWig("wysiwyg", "all");
					</script>
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
			<div class="green_content_bottom">
			</div>
		</div>');
	}
	
	function changename()
	{
		global $db, $user, $form, $group, $games, $config, $array;
		echo('This function has been disabled due to poor design');
		/*echo('
		<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">Changing User\'s Name</h3>
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
			<input type="hidden" name="cmd" value="changename" />
			<tr>
				<td>
					<h4 class="form_label_cb">Name:</h4>
				</td>
				<td>
					<select class="select" name="username" class="select">
						');
					if($user->rank_num == 25){select_user(0, 26);}
					else{select_user(0, $user->rank_num);}
					echo('</select>
				</td>
				<td>
					<font class="error">' . $form->error("username") . '</font>
				</td>
			</tr>
			<tr>
				<td>
					<h4 class="form_label_cb">New Name:</h4>
				</td>
				<td>
					<input type="text" class="text" class="text" value="' . $array['newname'] . '" name="newname" />
				</td>
				<td>
					<font class="error">' . $form->error("newname") . '</font>
				</td>
			</tr>
			<tr>
				<td colspan="3" align="center">
					<input type="submit" value="Change Name" />
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
			');*/
	}
	
	function reset_password()
	{
		global $db, $user, $form, $group, $games, $config, $array;
		echo('
			<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Reseting User Password</h3>
			</div>
			<div class="green_content">
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
			<div class="green_content_bottom">
			</div>
		</div>
			');
	}
	
	function approve_ia()
	{
		global $db, $user, $form, $group, $games, $config, $array;
		echo('
			<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Approving User IA Request</h3>
			</div>
			<div class="green_content">

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
			<div class="green_content_bottom">
			</div>
		</div>
			');
	}

};

class User_Commander_Form extends User_Form
{

	function loadForm( $cmd )
		{
			global $security, $permission, $user;
			if( $security->runSecurity($permission->getPermission('commander'), $user->username, 1, 1, 1) )
				{
					$this->$cmd();
				}
				else
				{
					echo('
					<SCRIPT LANGUAGE="JavaScript">
					window.location="index.php?view=security_error";
					</script>
					');
				}
		}

	function edit_settings()
	{
		global $db, $user, $form, $group, $games, $config, $array;
		echo('
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Modifying Basic Settings</h3>
			</div>
			<div class="green_content">
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '							
									' . $form->error("security") . '
								</h4>
							</div>
		<form action="process.php" method="POST">
		<input type="hidden" name="cmd" value="config" />
		<table class="form" border="0" cellspacing="5" cellpadding="3">
		');
		foreach( $config->config_list as $row )
		{
			echo('
			<tr>
				<td>
				<h3 class="form_label_cb">' . $row->name . '</h4>
				<p class="form_description">' . $row->description . '</p>
				<font class="error">' . $form->error($row->id) . '</font>
				</td>
				<td>
					<input size="10" type="text" class="text" name="' . $row->id . '" value="' . $row->value . '" />
				</td>
			</tr>
			');
		}
		echo('
			<tr>
				<td colspan="2" align="center">
					<input type="submit" value="Edit Config" />
				</td>
			</tr>
		</table>
		</form>
		<br />
		
<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
			<div class="green_content_bottom">
			</div>
		</div>');
	}
	
	
	
	function edit_template()
	{
		global $db, $user, $form, $group, $games, $config, $array;
		echo('
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Modifying Template Settings</h3>
			</div>
			<div class="green_content">
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '
								</h4>
							</div>
			<form action="process.php" method="post">
			<table class="form" cellpadding="0" cellspacing="5">
			<input type="hidden" name="cmd" value="template" />
			<tr>
				<td>
					<h4 class="form_label_cb">Template to Use: </h4>
				</td>
				<td>
					<select class="select" name="template">
						');
						$result = $db->getCurrentTemplateInfo();
						$array = mysql_fetch_array($result);
						$name = $array['name'];
						$id = $array['id'];
						echo('
						<option value="' . $id . '">' . $name . '</option>');
						select_templates();
					echo('</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<br />
					<input type="submit" value="Update" />
				</td>
			</tr>
			</table>
			</form>
			<br />
		<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
			<div class="green_content_bottom">
			</div>
		</div>
			');
	}
	
	function modify_permissions()
	{
		global $db, $user, $form, $group, $games, $config, $array, $permission;
		echo('
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Modifying Permission Settings</h3>
			</div>
			<div class="green_content">

							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '							
									' . $form->error("security") . '
								</h4>
							</div>
		<form action="process.php" method="POST">
		<input type="hidden" name="cmd" value="modify_permissions" />
		<table class="form" border="0" cellspacing="5" cellpadding="3">
			');
			foreach( $permission->permissions as $row )
			{
				echo('
					<tr>
						<td>
						<h3 class="form_label_cb">' . $row->name . '</h4>
						<p class="form_description">' . $row->description . '</p>
						<font class="error">' . $form->error($row->id) . '</font>
						</td>
						<td>
							');
							$permission->selectRanks( $row->id, $row->value );
							echo('
						</td>
					</tr>
				');
			}
			echo('
			<tr>
				<td colspan="2" align="center">
					<input type="submit" value="Modify Permissions" />
				</td>
			</tr>
		</table>
		</form>
		<br />
		<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
			<div class="green_content_bottom">
			</div>
		</div>');
		}

};

?>
