<?
$profile = new Profile($_GET['user']);
	?>
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header"><?php echo($db->titleFromUsername($_GET['user']));?></h3>
			</div>
			<div class="green_content">
				<table class="general" cellpadding="0" cellspacing="5px">
					<tr>
						<td>
							<div>
							<?php
							if($profile->status == 1)
							 echo('<h4 class="online">This User is Online!</h4>');
							 else
							 echo('<h4 class="offline">This User is Offline.</h4>');
							 ?>
							</div>
							<div class="profile_thumnail_top"></div>
							<div class="profile_thumnail_back">
								<?php 
								$file_management->profileImage( $_GET['user'], 230, 205 );
								 ?>
							</div>
							<div class="profile_thumnail_bottom"></div>
							<div>
								<table width="95%"  cellpadding="2px" cellspacing="0">
									<tr>
										<td><a href="index.php?view=user_personal&cmd=sendMessage&username=<?php echo($_GET['user']); ?>"><img border="0" src="templates/green/images/buttons_29.png" alt="Send Message"/></a></td>
										<!--<td><img src="templates/green/images/buttons_31.png" alt="Add Friend"/></td>
										<td><img src="templates/green/images/buttons_35.png" alt="View Images"/></td>-->
									</tr>
								</table>
							</div>
						</td>
						<td valign="top" width="65%">
							<table width="95%" class="general_info" cellpadding="15px" cellspacing="0">
								<tr>
									<td><h3>Username: </h3></td>
									<td><?php echo( $profile->username ); ?></td>
								</tr>
								<tr>
									<td><h3>Rank: </h3></td>
									<td><?php echo( $profile->image ); ?></td>
								</tr>
								<tr>
									<td><h3>Email: </h3></td>
									<td><?php echo( $profile->email ); ?></td>
								</tr>
								<tr>
									<td><h3>Aim: </h3></td>
									<td><?php echo( $profile->aim ); ?></td>
								</tr>
								<tr>
									<td><h3>Location: </h3></td>
									<td><?php echo( $profile->location ); ?></td>
								</tr>
								<tr>
									<td><h3>Main Game: </h3></td>
									<td><?php echo( '<img src="images/' . $profile->maingame . '.gif" alt="' . $profile->maingame . '" />' ); ?></td>
								</tr>
								<tr>
									<td><h3>Favorite Quote: </h3></td>
									<td><?php echo( '<p class="general_info">' . $profile->quote . '</p>' ); ?></td>
								</tr>
								
							</table>
						</td>
						<?php
						?>
					</tr>
				</table>
			</div>
			<div class="green_content_bottom">
			</div>
		</div>
	
			<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Recruitment/History Information</h3>
			</div>
			<div class="green_content">
				
					<table width="95%" class="general_info" cellpadding="15px" cellspacing="0">
								<tr>
									<td><h3>Recruited By: </h3></td>
									<td><?php echo( '<a class="members" href="index.php?view=profile&user=' . $profile->recruiter . '&ref=members.php">' . $db->titleFromUsername($profile->recruiter) . '</a>' ); ?></td>
								</tr>
								<tr>
									<td><h3>Recruited On: </h3></td>
									<td><?php echo( dateFromTimestamp_Long($profile->joined) ); ?></td>
								</tr>
								<tr>
									<td><h3>Recruits: </h3></td>
									<td>
									<marquee>
										<table width="95%"  cellpadding="2px" cellspacing="0">
									<tr>
										
						<?php 
									$result = $db->getRecruits($profile->username);
									while( $row = mysql_fetch_array( $result ) )
									{
									echo('
									<td style="border: 0;">
										<div class="recruits_username">
										<a class="members" href="index.php?view=profile&user=' . $row['username'] . '&ref=members.php">' . $db->titleFromUsername( $row['username'] ) . '</a>
										</div>
										<div class="recruits_image">
										<a class="members" href="index.php?view=profile&user=' . $row['username'] . '&ref=members.php">');
										$file_management->profileImage( $row['username'], 110, 95 );
										echo('</a>
										</div>
									</td>');
									
									}
									 ?>
									 </tr>
								</table>
								</marquee>
									 </td>
								</tr>
								
							</table>
						<?php
						?>
					</tr>
				</table>
			</div>
			<div class="green_content_bottom">
			</div>
		</div>
		<?php  
			$friends = new Friends();
			$friends->setFriends($_GET['user']);
			if( $friends->num_top_friends > 0 )
			{
			?>
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Friend Space - Top Friends</h3>
			</div>
			<div class="green_content">
				<table width="95%"  cellspacing="0">
					<tr>
						<?php
						if( $friends->num_top_friends < 8 )
						{
							for( $i = 1; $i <= 8; $i++ )
							{
								if( $friends->top_friends[$i] )
								{
								echo('<td>');
								
									echo('<div class="recruits_image" style="margin: auto;"><a href="index.php?view=profile&user=' . $friends->top_friends[$i] . '">');
									$file_management->profileImage( $friends->top_friends[$i], 100, 100 );
									echo('</a></div><div class="profile_username" align="center"><a href="index.php?view=profile&user=' . $friends->top_friends[$i] . '">' . $friends->top_friends[$i] . '</a></div>');
							
								echo('</td>');
								if( $i == 4 )
									echo('</tr><tr>');
								}
							}
						}
						else
							echo('You have too many top friends.  Please remove one.');
							
						
						?>
					</tr>
				</table>
			</div>
			<div class="green_content_bottom">
			</div>
		</div>
		<?php
		}
		?>
		
	<?php  $music = new Music( $profile->username );
		if( $music->hasMusic )
		{
		?>
						
	<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Music Space</h3>
			</div>
			<div class="green_content">
				<?php
						$music->displayMusicPlayer();   ?>
				<span>music player courtesy of <a href="http://www.playlist.com/">Project Playlist</a></span>
				</div>
			<div class="green_content_bottom">
			</div>
		</div>
	<?php }
	?>
	<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Recent Files</h3>
			</div>
			<div class="green_content">
				<table width="95%" class="general_info" cellpadding="15px" cellspacing="0">
						<tr>
							<?php
						$sql = 'select name, path from upload where username = "' . $_GET['user'] . '" order by time desc limit 3;';
						$result = @mysql_query($sql) or die("mysql error");
						for($i = 1; $i <=3; $i++)
						{
							$row = mysql_fetch_array($result);
							echo('<td align="center">
									<a href="index.php?view=file&file='. $row['path'] . '"><h4 class="general">' . truncate($row['name']) . '</h4></a>
								</td>');
						}
							echo('
						</tr>
						<tr>
						');
						$result = mysql_query($sql);
						for($i = 1; $i <=3; $i++)
						{
							
							$row = mysql_fetch_array($result);
							
							if( $row )
							{
							echo('<td align="center"><a href="index.php?view=file&file='. $row['path'] . '"><div class="recruits_image">');
									$file_management->displayThumbnail( $row['path'], 100, 100 );
								echo('</a></div></td>');
							}
							else
							{
								echo('<td><div style="border: solid 1px #303030; width: 100px; height: 80px; margin-top: 5px; margin-bottom: 5px; margin: auto;"></div></td>');
							}

						}
							?>
						</tr>
				</table>
				
			</div>
			<div class="green_content_bottom">
			</div>
		</div>
		
				<?php
				if( $user->rank_num >= 21 )
				{
				?>
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Member-Specific Logs</h3>
			</div>
			<div class="green_content">
				<table width="95%" class="general_info" cellpadding="15px" cellspacing="0">
						<tr>
							<td width="75%">
								<h3 class="content_box_header">Event</h3>
							</td>
							<td width="25%">
								<h3 class="content_box_header">Time</h3>
							</td>
						</tr>
						<?php
						$result = $db->getLogsforMember($profile->username, 25);
				while($row = mysql_fetch_array($result) )
				{
				echo('
				
					<tr>
						<td>
							<p class="event_logs">' . $row['Event'] . '</p>
						</td>
						<td>
							' . $row['datetime'] . '
						</td>
					</tr>
				
				');
				
				}
				?>
				</table>
				
			</div>
			<div class="green_content_bottom">
			</div>
		</div>
		<?php
		}
		?>
