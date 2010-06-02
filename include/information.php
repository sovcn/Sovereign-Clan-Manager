<?php
class File
{
	var $id;
	var $name;
	var $path;
	var $modify;

	function File( $id, $name, $path, $modify )
	{
		$this->id = $id;
		$this->name = $name;
		$this->path = $path;
		$this->modify = $modify;
	}
};

class Information
{
	var $filelist;
	
	function Information()
	{
		$sql = 'select * from information;';
		$result = mysql_query($sql);
		while( $row = mysql_fetch_array($result) )
			{
				$this->filelist[$row['id']] = new File( $row['id'], $row['name'], $row['path'], $row['modify'] );
			}
	
	}
	function displayFile( $fileid )
	{	echo('
		<div class="green_contentbox">
				<div class="green_content_top">
					<h3 class="content_box_header">' . $this->filelist[$fileid]->name . '</h3>
				</div>
				<div class="green_content">
		');
		include( $this->filelist[$fileid]->path );
		echo('
				</div>
				<div class="green_content_bottom">
				</div>
			</div>
		');
	}
	
	function minToModify()
	{
		$sql = 'select modify from information order by modify asc limit 1;';
		$result = mysql_query($sql);
		$array = mysql_fetch_array($result);
		return $array['modify'];
	}
	
	function displayNavigation( )
	{
		$sql = 'select * from information;';
		$result = mysql_query($sql);
		$counter = 1;
		while( $row = mysql_fetch_array($result) )
		{
			if( $counter > 1 )
			{
				echo('
				<li >
					<a class="side_nav" href="index.php?view=information&file=' . $row['id'] . '">' . $row['name'] . '</a>
				</li>
				');
			}
			$counter++;
		}
	}
	
	function displayConsole()
	{
		global $user;
		echo('
		<div class="green_contentbox">
				<div class="green_content_top">
					<h3 class="content_box_header">Information Management Console</h3>
				</div>
				<div class="green_content">
			');
		foreach( $this->filelist as $row )
		{
			if( strlen($row->name) <= 5 )
				echo('<div class="button_55"><a class="console" href="index.php?view=edit_information&file=' . $row->id . '">Edit ' . $row->name . '</a></div>');
			else if( strlen($row->name) > 5 && strlen($row->name) <= 8 )
				echo('<div class="button_62"><a class="console" href="index.php?view=edit_information&file=' . $row->id . '">Edit ' . $row->name . '</a></div>');
			else if( strlen($row->name) > 8 && strlen($row->name) <= 10 )
				echo('<div class="button_79"><a class="console" href="index.php?view=edit_information&file=' . $row->id . '">Edit ' . $row->name . '</a></div>');
			else if( strlen($row->name) > 10 && strlen($row->name) <= 13 )
				echo('<div class="button_93"><a class="console" href="index.php?view=edit_information&file=' . $row->id . '">Edit ' . $row->name . '</a></div>');
			else if( strlen($row->name) > 13 && strlen($row->name) <= 16 )
				echo('<div class="button_108"><a class="console" href="index.php?view=edit_information&file=' . $row->id . '">Edit ' . $row->name . '</a></div>');
			 else if( strlen($row->name) > 16 && strlen($row->name) <= 19 )
				echo('<div class="button_120"><a class="console" href="index.php?view=edit_information&file=' . $row->id . '">Edit ' . $row->name . '</a></div>');
			else if( strlen($row->name) > 19 && strlen($row->name) <= 21 )
				echo('<div class="button_141"><a class="console" href="index.php?view=edit_information&file=' . $row->id . '">Edit ' . $row->name . '</a></div>');
			else if( strlen($row->name) > 21 )
				echo('<div class="button_153"><a class="console" href="index.php?view=edit_information&file=' . $row->id . '">Edit ' . $row->name . '</a></div>');
			else 
				echo('<div class="button_153"><a class="console" href="index.php?view=edit_information&file=' . $row->id . '">Edit ' . $row->name . '</a></div>');
		}
		
		echo('</div>
				<div class="green_content_bottom">
				</div>
			</div>');
	}
	function editFile( $fileid )
	{
		global $db, $user, $form, $group, $games, $config, $array, $security;
		$file = $this->filelist[$fileid];
		if( $security->runSecurity($file->modify, $user->username, 1, 1, 1) )
		{
			
			if( $_POST['content'] )
			{
				$fh = fopen($file->path, 'w') or die("can't open file");
				$stringData = "" . $_POST['content'] . "";
				fwrite($fh, stripslashes($stringData));
				fclose($fh);
				
				echo('		<div class="contentbox">
							<div align="center">
								<div class="content_back">
									<div align="center">
										<div class="content_header">
											<h3 class="content_box_header">
												Editing the ' . $file->name . ' informational file
											</h3>
										</div>
									</div>
									<div class="content_box_minor_header">
										<h4 class="content_box_minor_header">
											File Successfully Edited
										</h4>
									</div>
				<br /><a class="return" href="index.php?view=console">Click Here to return to your console.</a>
				
										</div>
							</div>
							<div align="center"><div class="content_box_footer"></div></div>
						</div>');
				
			}
			else  // If not successful in adding the member or if no attempt as been made
			{
				$contents = file($file->path); 
				$string = implode($contents);
						echo('
						<div class="green_contentbox">
						<div class="green_content_top">
							<h3 class="content_box_header">Editing the ' . $file->name . ' informational file</h3>
						</div>
						<div class="green_content">
							
					<form action="index.php?view=edit_information&file=' . $file->id . '" method="post">
						<table cellpadding="0" cellspacing="0">
							<tr>
							<td class="wysiwyg">
								<textarea id="wysiwyg" name="content" cols="75" style="margin: 0; padding: 0; background-color: #FFFFFF; color: #000000;">' . $string . '</textarea>
								<script type="text/javascript">
								buttonPath = "js/images/wysiwygbuttons/"; //directory holding button images
								makeWhizzyWig("wysiwyg", "all");
								</script>
							</td>
							</tr>
						</table>
						<div><input type="submit" value="Submit" /></div>
					</form>
					<br />
						<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
			<div class="green_content_bottom">
			</div>
		</div>	');
					
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
		
	}
	
};
?>
