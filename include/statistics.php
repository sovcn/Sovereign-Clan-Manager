<?php
class Statistic
{
	function getMembers()
	{
		$sql = 'select *
				from users
				where disabled = 0;';
		$result = mysql_query($sql);
		$number = mysql_num_rows($result);
		return $number;
	}
	
	function getMembersByRank($rank_num)
	{
		$sql = 'select *
				from users
				where disabled = 0
				and rank = ' . $rank_num . ';';
		$result = mysql_query($sql);
		$number = mysql_num_rows($result);
		return $number;
	}
	
	function getMembersByGame($game)
	{
		$sql = 'select *
				from users
				where disabled = 0
				and maingame = "' . $game . '";';
		$result = mysql_query($sql);
		$number = mysql_num_rows($result);
		return $number;
	}
	
	function getMembersByDsl($dsl)
	{
		$timestamplow = time() - ($dsl * 86400);
		$timestamphigh = time() -  ( ($dsl + 1) * 86400 );
		$sql = 'select *
				from users
				where disabled = 0
				and last_login > ' . $timestamplow . '
				and last_login < ' . $timestamphigh . ';';
		$result = mysql_query($sql);
		$number = mysql_num_rows($result);
		return $number;
	}
	
	function getAverageDsl()
	{
		$sql = 'select last_login
				from users
				where disabled = 0
				and ia = 0;';
		$result = mysql_query($sql);
		$total = 0;
		while( $row = mysql_fetch_array($result) )
		{
			$total = $total + daysSinceTimestamp($row['last_login']);
		}
		$average = $total / ( mysql_num_rows($result) );
		return $average;
	}
	
	function displayMemberSection( $category )
	{
		$sql = 'select name from categories where cid = ' . $category . ' limit 1;';
		$result = mysql_query($sql);
		$array = mysql_fetch_array($result);
		$cat = $array['name'];
		$sql = 'select username from users, ranks where ranks.id = users.rank and ranks.cid = ' . $category . ' and disabled = 0 and ia = 0 order by rank desc, username asc;';
		$result = mysql_query($sql);
		if( mysql_num_rows($result) > 0 )
		{
		echo('
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">' . $cat . '</h3>
			</div>
			<div class="green_content">
			<table class="general_info" cellpadding="0" cellspacing="0">
				<tr>
					<td width="20%"><h3 class="general">Rank</h3></td>
					<td width="25%"><h3 class="general">Name</h3></td>
					<td width="25%"><h3 class="general">AIM</h3></td>
					<td width="10%"><h3 class="general">Game</h3></td>
					<td width="10%"><h3 class="general">Online</h3></td>
					<td width="10%"><h3 class="general">DSL</h3></td>
				</tr>
				');
				
		while( $row = mysql_fetch_array($result) )
			{
				$profile = new Profile($row['username']);
				echo('
				<tr>
					<td>' . $profile->image . '</td>
					<td><a class="members" href="index.php?view=profile&user=' . $profile->username . '&ref=members.php">
						' . $profile->username . '
					</a></td>
					<td><div class="aim">' . truncate($profile->aim, 15) . '</td>
					<td><img src="images/' . $profile->maingame . '.gif" alt="' . $profile->maingame . '" /></td>
					<td>');
							 if($profile->status == 1)
							 {
							 echo('<img width="20px" src="images/online.png" alt="Yes" />');
							 }
							 else
							 echo('<img width="20px" src="images/offline.png" alt="No" />');
							echo('</td>
						<td>
							' . $profile->dsl . '
						</td>
				</tr>
				');
			}
		echo('</table>
		</div>
			<div class="green_content_bottom">
			</div>
		</div>');
		}
	}
	
	function displayIaMembers()
	{
		$sql = 'select username from users, ranks where ranks.id = users.rank and disabled = 0 and ia = 1;';
		$result = mysql_query($sql);
		echo('
			<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Inactive Members</h3>
			</div>
			<div class="green_content">
			<table class="general_info" cellpadding="0" cellspacing="0">
				<tr>
					<td width="20%"><h3 class="general">Rank</h3></td>
					<td width="25%"><h3 class="general">Name</h3></td>
					<td width="25%"><h3 class="general">AIM</h3></td>
					<td width="10%"><h3 class="general">Game</h3></td>
					<td width="10%"><h3 class="general">Online</h3></td>
					<td width="10%"><h3 class="general">DSL</h3></td>
				</tr>
				');
				
		while( $row = mysql_fetch_array($result) )
			{
				$profile = new Profile($row['username']);
				echo('
				<tr>
					<td>' . $profile->image . '</td>
					<td><a class="members" href="index.php?view=profile&user=' . $profile->username . '&ref=members.php">
						' . $profile->username . '
					</a></td>
					<td><div class="aim">' . truncate($profile->aim, 15) . '</td>
					<td><img src="images/' . $profile->maingame . '.gif" alt="' . $profile->maingame . '" /></td>
					<td>');
							 if($profile->status == 1)
							 {
							 echo('<img width="20px" src="images/online.png" alt="Yes" />');
							 }
							 else
							 echo('<img width="20px" src="images/offline.png" alt="No" />');
							echo('</td>
						<td>
							' . $profile->dsl . '
						</td>
				</tr>
				');
			}
		echo('</table>
		</div>
			<div class="green_content_bottom">
			</div>
		</div>');
	}
	
	function getActiveMembers()
	{
		$sql = 'select users.username from users, online_users where users.username = online_users.username order by rank desc;';
		$result = @mysql_query($sql) or die("Mysql Error");
		while( $row = mysql_fetch_array($result) )
		{
			$list[] = $row['username'];	
		}
		return $list;
	}

};

$statistic = new Statistic;
?>
