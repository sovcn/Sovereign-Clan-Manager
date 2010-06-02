<?php
class File_Management
{
	function uploadFile( $file, $name, $description )
	{
		global $form, $user, $config, $config_file, $db;
		$field="type";
		if( $file['type'] != 'image/png' && $file['type'] != 'image/pjpeg' && $file['type'] != 'image/jpeg' && $file['type'] != 'image/gif' )
		$form->setError($field, " * File must be in one of the following formats: .png, .jpg, .jpeg, or .g0if * ". $file['type']);
		
		$field="size";
		if( $file['size'] > 5 * 1024 * 1024 )
		$form->setError($field, " * File must be less than 1MB or " . 5 * 1024 . "KB. *" . $file['size']);
		
		$field="security";
		if( $file['error'] > 0 )
		$form->setError($field, " * There was an error while uploading your the file. *");
		
		switch( $db->catIDFromUsername($user->username) )
		{
			case 1:
				if( $this->getTotalFiles( $user->username ) >= $config_file->getSetting('max_files_enlisted') && $config_file->getSetting('max_files_enlisted') > 0 )
				$form->setError($field, " * An enlisted member may only have a total of " . $config_file->getSetting('max_files_enlisted') . " uploaded files. *");
			break;
			case 2:
				if( $this->getTotalFiles( $user->username ) >= $config_file->getSetting('max_files_warrant') && $config_file->getSetting('max_files_warrant') > 0 )
				$form->setError($field, " * A warrant officer may only have a total of " . $config_file->getSetting('max_files_warrant') . " uploaded files. *");
			break;
			case 3:
				if( $this->getTotalFiles( $user->username ) >= $config_file->getSetting('max_files_officer') && $config_file->getSetting('max_files_officer') > 0 )
				$form->setError($field, " * An officer may only have a total of " . $config_file->getSetting('max_files_officer') . " uploaded files. *");
			break;
			case 4:
				if( $this->getTotalFiles( $user->username ) >= $config_file->getSetting('max_files_admin') && $config_file->getSetting('max_files_admin') > 0 )
				$form->setError($field, " * An administrator may only have a total of " . $config_file->getSetting('max_files_admin') . " uploaded files. *");
			break;
			case 5:
				if( $this->getTotalFiles( $user->username ) >= $config_file->getSetting('max_files_commander') && $config_file->getSetting('max_files_commander') > 0 )
				$form->setError($field, " * A commander may only have a total of " . $config_file->getSetting('max_files_commander') . " uploaded files. *");
			break;
			case 6:
				if( $this->getTotalFiles( $user->username ) >= $config_file->getSetting('max_files_trial') && $config_file->getSetting('max_files_trial') > 0 )
				$form->setError($field, " * A trial member may only have a total of " . $config_file->getSetting('max_files_trial') . " uploaded files. *");
			break;
		}
		
		if( $form->num_errors == 0 )
		{
			$filename = $user->generateRandStr(8);
			switch( $file['type'])
			{
				case 'image/png':
					$filename = preg_replace("/[^a-zA-Z0-9s]/", "", $user->username) . '_' . $filename . '.png';
					$extension = 'png';
				break;
				case 'image/pjpeg':
					$filename = preg_replace("/[^a-zA-Z0-9s]/", "", $user->username) . '_' . $filename . '.jpg';
					$extension = 'jpg';
				break;
				case 'image/jpeg':
					$filename = preg_replace("/[^a-zA-Z0-9s]/", "", $user->username) . '_' . $filename . '.jpg';
					$extension = 'jpg';
				break;
				case 'image/gif':
					$filename = preg_replace("/[^a-zA-Z0-9s]/", "", $user->username) . '_' . $filename . '.gif';
					$extension = 'gif';
				break;
			}
			$uploaddir = "uploads/user_uploads/" . preg_replace("/[^a-zA-Z0-9s]/", "", $user->username) . "/";
			
			if( !file_exists($uploaddir) )
			{
				mkdir($uploaddir);
			}
			
			$field="exists";
			if( file_exists("uploads/user_uploads/" . preg_replace("/[^a-zA-Z0-9s]/", "", $user->username) . "/" . $filename ) )
			$form->setError($field, " * That file already exists *");
			
			
			
			
			if( $form->num_errors == 0 )
			{
				if( $file['type'] == 'image/png' || $file['type'] == 'image/pjpeg' || $file['type'] == 'image/jpeg' || $file['type'] == 'image/gif' )
					$image = true;
				else 
					$image = false;
				$this->addFileDB( $uploaddir . $filename, $image, $name, $description, $extension, $user->username, $file['type']);
				move_uploaded_file( $file['tmp_name'], $uploaddir . $filename );
				$event =  $file['name'] . ' has been uploaded to the website as ' . $uploaddir . $filename;
				return $event;
			
			}
			else
				return false;
		}
		else
			return false;
	}
	
	function displayConsole()
	{
		global $permission, $user, $security, $permission;
		
		if( $permission->getPermission('upload') )
		{
		echo('
		<div class="green_contentbox">
				<div class="green_content_top">
					<h3 class="content_box_header">File Management Console</h3>
				</div>
				<div class="green_content">
			<div class="button_79"><a class="console" href="index.php?view=user_form&cmd=upload&do=new">Upload File</a></div>
			<div class="button_153"><a class="console" href="index.php?view=user_form&cmd=upload&do=view">View Your Uploads(' . $this->getTotalFiles($user->username) . ' Total)</a></div>
			');
			if( $security->hasPermission( $permission->getPermission('modify_file_settings') ) )
					{
					echo('
					<div class="button_108"><a class="console" href="index.php?view=user_form&cmd=modify_file_settings">Edit File Settings</a></div>
					');
					}
					echo('</div>
				<div class="green_content_bottom">
				</div>
			</div>');
		}
	}
	
	function addFileDB( $path, $image, $name, $description, $extension, $username, $mime)
	{
		$sql = 'INSERT INTO `upload` 
					(`path`, `image`, `name`, `description`, `time`, `extension`, `username`, mime) 
				VALUES 
					(\'' . $path . '\', \'' . $image . '\', \'' . $name . '\', \'' . $description . '\', \'' . time() . '\', \'' . $extension . '\', \'' . $username . '\', \'' . $mime . '\');';
		$result = mysql_query($sql) or die("error");
	}
	
	function getTotalFiles( $username )
	{
		$sql = 'select path from upload where username ="' . $username . '";';
		$result = mysql_query($sql);
		return mysql_num_rows($result);
	}
	
	function getTotalFileSize( )
	{
		
	}
	
	function delete($pathList)
	{
		global $db, $form, $config, $user;
		$field = "security";
		foreach( $pathList as $path )
		{
			if( !$this->verifyOwnership( $path, $user->username ) )
		 	{$form->setError($field, " * That is not your file. * ");}
			
		}
		/* Ownership Security Checking */
	
		if( $form->num_errors == 0 )  // If there were no errors with the submitted information
			{
				foreach( $pathList as $path )
				{
					unlink( $path );
					$this->deleteSql( $path );
				}
				$event = 'A file or several files was/were deleted by ' . $user->title . '.';
				$db->addToLogs($event, $user->username, 0);
				$db->setLoginInfo($user->username, time(), $_SERVER['REMOTE_ADDR']);
				return $event;
			}
		else
			{
				return false;
			}
			
	}
	
	function deleteSql( $path )
	{
		$sql = 'DELETE FROM `upload` WHERE CONVERT(`upload`.`path` USING utf8) = \'' . $path . '\' LIMIT 1;';
		$result = mysql_query($sql) or die(mysql_error);	
	}
	
	function getFileInfo($path)
	{
		$sql = 'select * from upload where path = "' . $path . '" limit 1;';
		$result = mysql_query($sql) or die(mysql_error());
		$array = mysql_fetch_array($result);
		return $array;
	}
	
	function displayThumbnail( $path, $width_display = 200 )
	{
		$imagesize = @getimagesize($path);
		if( $imagesize )
		{
			// File is an image
			$width = $imagesize[0];
			$height = $imagesize[1];
			if( $width > $width_display )
			{
				$ratio = $height / $width;
				$width = $width_display;
				$height = $width * $ratio;	
			}
			echo('<img border="0" src="' . $path . '" width="' . $width . '" height="' . $height . '" />');
		}
		else
		{
			// File is not an image
			$imagesize = @getimagesize('images/unknown_file.gif');
			$width = $imagesize[0];
			$height = $imagesize[1];
			if( $width > $width_display )
			{
				$ratio = $height / $width;
				$width = $width_display;
				$height = $width * $ratio;	
			}
			echo('<img border="0" src="images/unknown_file.gif" width="' . $width . '" height="' . $height . '" />');
		}	
	}
	
	function displayThumbnail_height( $path, $width_display, $height_display )
	{
		$imagesize = @getimagesize($path);
		$width = $imagesize[0];
		$height = $imagesize[1];
		if( $width > $width_display )
		{
			$ratio = $height / $width;
			
			$width = $width_display;
			$height = $width * $ratio;
		}
		if( $height > $height_display )
		{
			$height = $height_display;
			$ratio = $width / $height_display;
			$width = $height_display * $ratio;
		}
		if( $imagesize )
		echo('<img border="0" src="' . $path . '" height="' . $height . 'px" width="' . $width . 'px" />');
		else
		echo('<div style="width: ' . $width_display . 'px; height: ' . $height_display . 'px; margin: auto; text-align: center; vertical-align: middle; border: 1px #333333 solid;">No Profile Image</div>');
	}
	
	function profileImage( $username, $width_display, $height_display )
	{
		$sql = 'select image_path from users where username = "' . $username . '" limit 1;';
		$result = mysql_query($sql);
		$array = mysql_fetch_array($result);
		$path = $array['image_path'];
		$this->displayThumbnail_height( $path, $width_display, $height_display );
	}
	
	function displayFile( $path, $width = 0 )
	{
		global $user, $config_file, $db;
		$file = $this->getFileInfo($path);
		$imagesize = @getimagesize($path);
		
		if( !$imagesize ) // File is not an Image
		{
			echo('
				<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">Viewing User File</h3>
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
		<div><a class="return" href="index.php?view=user_personal&cmd=displayInbox">Click Here to return to your Inbox</a></div>
										</div>
					</div>
					<div align="center"><div class="content_box_footer"></div></div>
				</div>
			');	
		}
		else // File is an image
		{
			$width = $imagesize[0];
			$height = $imagesize[1];
			if( $width > $config_file->getSetting('image_max_display_width') )
			{
			$ratio = $height / $width;
			
			$width = $config_file->getSetting('image_max_display_width');
			$height = $width * $ratio;
			}
			echo('
				<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">Viewing User Image - ' . $db->titleFromUsername($file['username']) . '</h3>
								</div>
							</div>
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									Image Name: ' . $file['name'] . '
								</h4>
							</div>
					<table class="form" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td>
							<h4 class="form_label_cb">File Type:</h4>
							</td>
							<td>
								' . $file['mime'] . '
							</td>
						</tr>
						<tr>
							<td>
							<h4 class="form_label_cb">Description:</h4>
							</td>
							<td>
								<p class="cb">
								' . $file['description'] . '
								</p>
							</td>
						</tr>
						<tr>
							<td>
							<h4 class="form_label_cb">Image Real Dimensions:</h4>
							</td>
							<td>
								' . $imagesize[0] . ' x ' . $imagesize[1] . '
							</td>
						</tr>
						<tr>
							<td>
							<h4 class="form_label_cb">URL:</h4>
							</td>
							<td>
								<font class="url">http://' . $_SERVER['HTTP_HOST'] . '/' . $file['path'] . '</font>
							</td>
						</tr>
						<tr>
							<td>
							<h4 class="form_label_cb">Uploaded:</h4>
							</td>
							<td>
								' . dateFromTimestamp_Long($file['time']) . '
							</td>
						</tr>
						<tr>
							<td colspan="2" align="center">
								<div class="upload_image"><img src="' . $path . '" height="' . $height . '" width="' . $width . '" /></div>
							</td>
						</tr>
					</table>
					
			<br />
		<div><a class="return" href="' . $_SERVER['HTTP_REFERER'] . '">Return to Previous Page</a></div>
										</div>
					</div>
					<div align="center"><div class="content_box_footer"></div></div>
				</div>
			');	
		}
	}
	
	function verifyOwnership( $path, $username )
	{
		$sql = 'select path from upload where path = "' . $path . '" and username = "' . $username . '" limit 1;';
		$result = mysql_query($sql) or die(mysql_error());
		if( mysql_num_rows($result) == 1 )
			return true;
		else
			return false;	
	}
	
	function displayUploads($username)
	{
		$sql = 'select * from upload where username = "' . $username . '" order by time desc;';
		$result = mysql_query($sql);
		while( $row = mysql_fetch_array($result) )
		{
			echo('
			<tr>
				<td>
					<input type="checkbox" name="delete[]" value="' . $row['path'] . '" />
				</td>
				<td>');
				if( strrpos($row['mime'], 'mage/') )
				{
					$imagesize = getimagesize($row['path']);
					$width = $imagesize[0];
					$height = $imagesize[1];
					if( $width >= $height )
					{
						$ratio = $height / $width;
						$width = 100;
						$height = $width * $ratio;
					}
					else
					{
						$ratio = $width / $height;
						$height = 100;
						$width = $height * $ratio;
					}
					echo('
					<a href="index.php?view=file&file=' . $row['path'] . '">
						<img border="0" src="' . $row['path'] . '" height="' . $height . '" width="' . $width . '" />
					</a>
					');
					}
				  else
				  {
				  	echo('<image src="images/unknown_file.gif" alt="unknown" />');
				  }
				  echo('
				</td>
				<td align="center">
				<a href="index.php?view=file&file=' . $row['path'] . '">
					<h4 class="form_label_cb">' . $row['name'] . '</h4>
				</a>
				</td>
				<td align="center">
					' . $row['description'] . '
				</td>
			</tr>
			<tr>
				<td colspan="4">
					<font class="url">http://' . $_SERVER['HTTP_HOST'] . '/' . $row['path'] . '</font>
				</td>
			</tr>
			<tr>
				<td colspan="4">
					<br />
				</td>
			</tr>
			');
		}
	}
	
};

$file_management = new File_Management();
?>
