<?php

// >>>>    Begin Class Definition for Groups     <<<//

class Group
{
	var $is_game;
	var $title; // The name of the divisions class  ex..   Corps, Division, Squad
	
	//  Declare the variables to hold the database information  //
	var $id;
	var $corps;
	var $division;
	var $squad;
	var $name;
	var $description;
	var $created;
	var $leader1;
	var $leader2;
	var $gameid;
	var $image;
	var $parentcorps;
	var $parentdivision;
	
	function setInfo($id)
	{
		$sql = 'select * from groups where id = ' . $id . ' limit 1;';
		$result = mysql_query($sql);
		$array = mysql_fetch_array($result);
		$this->id = $array['id'];
		$this->corps = $array['corps'];
		$this->division = $array['division'];
		$this->squad = $array['squad'];
		$this->name = $array['name'];
		$this->description = $array['description'];
		$this->created = $array['created'];
		$this->leader1 = $array['leader1'];
		$this->leader2 = $array['leader2'];
		$this->gameid = $array['gameid'];
		$this->image = $array['image'];
		$this->parentcorps = $array['parentcorps'];
		$this->parentdivision = $array['parentdivision'];
		
		if($gameid == 0)
			$this->is_game = false;
		else
			$this->is_game = true;
			
		if( $array['corps'] == 1 && $array['division'] == 0 && $array['squad'] == 0 )
			$this->title = 'Corps';
		else if( $array['corps'] == 0 && $array['division'] == 1 && $array['squad'] == 0 )
			$this->title = 'Division';
		else if( $array['corps'] == 0 && $array['division'] == 0 && $array['squad'] == 1 )
			$this->title = 'Squadron';
	}
	
	function nameFromId($id)
	{
		$sql = 'select * from groups where id = ' . $id . ' limit 1;';
		$result = mysql_query($sql);
		$object = mysql_fetch_object($result);
		$name = $object->name;
		return $name;
	}
//>>>> Set Notifications for all Group Members <<<<//
	function notifyGroup($id, $title, $text)
	{
		global $notification, $user;
		$sql = 'select username from group_users where gid = ' . $id . ';';
		$result = mysql_query($sql);
		while( $row = mysql_fetch_array($result) )
		{
			$notification->setNot($row['username'], $user->username, $title, $text);
		}
	}

//>>>> Display Main Page <<<<//
	function displayMainPage($id, $username)
	{
		echo('<div class="groupPage">');
		$this->setInfo($id);
		echo('
				<h3 class="groupTitle">
					');
				if( $this->image == 0 )
				{
				echo('' . $this->name . ' ' . $this->title . ' main page');
				}
				else
				{
				echo('Division Image Goes here');
				}	
					echo('
				</h3>
				<h4 class="groupHeader">
					Basic Information
				</h4>
				');
			$this->displayInfo($id);
		echo('
				<h4 class="groupHeader">
					' . $this->title . ' Members
				</h4>
			');
				
			$this->displayMembers($id);
			
		echo('
				<h4 class="groupHeader">
					' . $this->title . ' Events
				</h4>
			');
			$this->displayEvents($id);
			
			if( $this->isAdmin($id, $username) )
			{
				echo('If the user is a group admin, display a list of options<br/><br/>');
				echo('<div align="center"><a href="index.php?view=group_function&id=' . $id . '&cmd=application">Apply for this squad</a></div>');
			}
			else if( $this->isMember( $id, $username ) )
			{
			
			
			}
			else
			{
				echo('<div align="center"><a href="index.php?view=group_function&id=' . $id . '&cmd=application">Apply for this squad</a></div>');
			}
			echo('</div>');
	}
	
	
	function displayList()
	{	
	global $group_stats;
	echo('<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">List of Corps, Divisions, and Squadrons</h3>
			</div>
			<div class="green_content">
			
			<table class="group_list" cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="3" style="text-align: left;">
						<h3>Name</h3>
					</td>
					<td>
						<h3>Type</h3>
					</td>
					<td>
						<h3>Description</h3>
					</td>
					<td>
						<h3>Num Members</h3>
					</td>');
		$sql = 'select * from groups where corps = 1;';
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result))
		{	
				$this->setInfo($row['id']);
				echo('
				<tr>
					<td colspan="3" class="corps_list_title">
						<a href="index.php?view=group&id=' . $this->id . '">' . $this->name . '</a>
					</td>
					<td class="data">
						<span class="data_small">' . $this->title . '</span>
					</td>
					<td class="data">
						<span class="data_small">' . truncate($this->description, 30) . '</span>
					</td>
					<td class="data" align="center">
						' . $group_stats->numMembers($this->id) . '
					</td>
					
				</tr>
				');
				
				$sql_divisions = 'select * from groups where parentcorps = ' . $row['id'] . ' and division = 1;';
				$result_divisions = mysql_query($sql_divisions);
				while( $row_divisions = mysql_fetch_array($result_divisions) )
				{
					// Display Divisions Console Here
					$this->setInfo($row_divisions['id']);
					echo('
					<tr>
						<td class="spacer">
						</td>
						<td colspan="2" class="division_list_title">
							<a href="index.php?view=group&id=' . $this->id . '">' . $this->name . '</a>
						</td>
						<td class="data">
							<span class="data_small">' . $this->title . '</span>
						</td>
						<td class="data">
							<span class="data_small">' . truncate($this->description, 30) . '</span>
						</td>
						<td class="data" align="center">
							' . $group_stats->numMembers($this->id) . '
						</td>
					</tr>
					');
				
				
					$sql_squads = 'select * from groups where parentcorps = ' . $row['id'] . ' and parentdivision = ' . $row_divisions['id'] . ' and squad = 1;';
					$result_squads = mysql_query($sql_squads);
					while( $row_squads = mysql_fetch_array($result_squads) )
					{
						// Display Squads Console Here
						$this->setInfo($row_squads['id']);
						echo('
							<tr>
								<td class="spacer">
								</td>
								<td class="spacer">
								</td>
								<td class="squadron_list_title">
									<a href="index.php?view=group&id=' . $this->id . '">' . $this->name . '</a>
								</td>
								<td class="data">
									<span class="data_small">' . $this->title . '</span>
								</td>
								<td class="data">
									<span class="data_small">	' . truncate($this->description, 30) . '</span>
								</td>
								<td class="data" align="center">
									' . $group_stats->numMembers($this->id) . '
								</td>
							</tr>
						');
					}
				}
			
		}
	echo('
	</table>
	</div>
			<div class="green_content_bottom">
			</div>
		</div>');
	}
	// End Display List
	//>>>> Display General Info  <<<<//
	function displayInfo($id)
	{
		global $db;
		echo('
			<table class="groupInfo" cellpadding="0" cellspacing="0">
				<tr class="groupInfoRow">
					<td class="groupInfoCol">
						<strong>Name</strong>
					<td>
					<td class="groupInfoCol">
						' . $this->name . ' ' . $this->title . '
					</td>
				</tr>
				<tr class="groupInfoRow">
					<td class="groupInfoCol">
						<strong>Description</strong>
					<td>
					<td class="groupInfoCol">
						' . $this->description . '
					</td>
				</tr>
				<tr class="groupInfoRow">
					<td class="groupInfoCol">
						<strong>Group Type</strong>
					<td>
					<td class="groupInfoCol">
						' . $this->title . '
					</td>
				</tr>
				<tr class="groupInfoRow">
					<td class="groupInfoCol">
						<strong>' . $this->title . ' Since</strong>
					<td>
					<td class="groupInfoCol">
						' . dateFromTimestamp_Long($this->created) . '
					</td>
				</tr>
				<tr class="groupInfoRow">
					<td class="groupInfoCol">
						<strong>Leaders</strong>
					<td>
					<td class="groupInfoCol">
						<a class="members" href="index.php?view=profile&user=' . $this->leader1 . '">' . $db->titleFromUsername($this->leader1) . '</a>
					</td>
				</tr>
				<tr class="groupInfoRow">
					<td class="groupInfoCol">
					
					<td>
					<td class="groupInfoCol">
						<a class="members" href="index.php?view=profile&user=' . $this->leader2 . '">' . $db->titleFromUsername($this->leader2) . '</a>
					</td>
				</tr>
			</table>
		');
	}	

//>>>> Display Members   <<<<//
	function displayMembers($id)
	{
		$sql = 'select * from group_users where gid = ' . $id . ';';
		$result = mysql_query($sql);
		echo('<table class="groupUser" cellpadding="0" cellspacing="0">
				<tr class="groupUserLabelRow">
					<td class="groupUserCol_rank">
						<strong>Rank</strong>
					</td>
					<td class="groupUserCol_username">
						<strong>Username</strong>
					</td>
					<td class="groupUserCol_title">
						<strong>' . $this->title . ' Title</strong>
					</td>
					<td class="groupUserCol_joined">
						<strong>Joined On</strong>
					</td>
				</tr>');
		while( $row = mysql_fetch_array($result) )
		{
			$group_user = new Group_User($row['username'], $id);
			$group_user->displayRow();
		}
		echo('</table>');
	}
//>>>> Display Events   <<<<//
	function displayEvents($id)
	{
		$sql = 'select * from group_events where gid = ' . $id . ';';
		$result = mysql_query($sql);
		echo('<table class="groupEvent" cellpadding="0" cellspacing="0">
				<tr class="groupEventLabelRow">
					<td class="groupEventCol_title">
						<strong>Title</strong>
					</td>
					<td class="groupEventCol_username">
						<strong>Posted By</strong>
					</td>
					<td class="groupEventCol_text">
						<strong>Preview</strong>
					</td>
					<td class="groupEventCol_time">
						<strong>Posted On</strong>
					</td>
				</tr>');
		while( $row = mysql_fetch_array($result) )
		{
			$group_event = new Group_Event($row['id']);
			$group_event->displayRow();
		}
		echo('</table>');
	}
//>>> Find out if the user is the leader of a group <<<<//
	function isLeader($id, $username)
	{
	$sql = 'select * from groups 
			where (leader1 = "' . $username . '" or leader2 = "' . $username . '" ) and id = ' . $id . ';';
	$result = mysql_query($sql);
	if( mysql_num_rows($result) > 0 )
		return true;
	else
		return false;
	}
	
//>>>> Find out whether the user is an administrator to the group ie-> if they are the leader of a higher group. <<<<<//
	function isAdmin($id, $username)
	{
		global $db, $permission;
		$sql = 'select * from groups where id = ' . $id . ' limit 1;';
		$result = mysql_query($sql);
		$array = mysql_fetch_array($result);
		if( !$username )
			return false;
		if( $db->rank_numFromUsername($username) >= 24 )
		{
			return true;
		}
		else
		{
			if( $array['corps'] == 1 )
			{
				if( $this->isLeader($id, $username) )
					return true;
				else
					return false;
			}
			if( $array['division'] == 1 )
			{
				if( $this->isLeader($id, $username) || $this->isLeader($array['parentcorps'], $username) )
					return true;
				else
					return false;
			}
			if( $array['squad'] == 1 )
			{
				if( $this->isLeader($id, $username) || $this->isLeader($array['parentcorps'], $username) || $this->isLeader($array['parentdivision'], $username) )
					return true;
				else
					return false;
			}
		}
	}
	
	function isMember($id, $username)
	{
		$sql = 'select id from group_users where username = "' . $username . '" and gid = ' . $id . ' limit 1;';
		$result = mysql_query($sql);
		if( mysql_num_rows($result) == 1 )
			return true;
		else
			return false;
	}

//>>>> Display Consoles   <<<<//
	function displayConsoles($username)
	{
		global $security, $group_stats;
		
		$sql = 'select * from groups;';
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result))
		{
			if( $row['corps'] == 1 && $this->isAdmin( $row['id'], $username ) )
			{
				// Display Corps Console Here
				$this->setInfo($row['id']);
				echo('
				<div class="green_contentbox">
				<div class="green_content_top">
					<h3 class="content_box_header">' . $this->name . ' ' . $this->title . ' Console</h3>
				</div>
				<div class="green_content">
					<div class="button_79"><a class="console" href="index.php?view=group_function&cmd=add_division&id=1&parentcorps=' . $this->id . '">Add Division</a></div>
					<div class="button_153"><a class="console" href="index.php?view=group_function&cmd=process_applications&id=' . $this->id . '"><font style="font-size: 8px;">Approve/Deny Applications(' . $group_stats->numApplications($this->id) . ')</font></a></div>
					<div class="button_93"><a class="console" href="index.php?view=group_function&cmd=edit_info&id=' . $this->id . '">Edit ' . $this->title . ' Info</a></div>
					<div class="button_108"><a class="console" href="index.php?view=group_function&cmd=edit_member&id=' . $this->id . '">Edit ' . $this->title . ' Members</a></div>
					<div class="button_108"><a class="console" href="index.php?view=group_function&cmd=edit_leaders&id=' . $this->id . '">Modify Leadership</a></div>
					
				</div>
				<div class="green_content_bottom">
				</div>
			</div>
				');
				
				$sql_divisions = 'select * from groups where parentcorps = ' . $row['id'] . ' and division = 1;';
				$result_divisions = mysql_query($sql_divisions);
				while( $row_divisions = mysql_fetch_array($result_divisions) )
				{
					// Display Divisions Console Here
					$this->setInfo($row_divisions['id']);
					echo('
					<div class="green_contentbox">
				<div class="green_content_top">
					<h3 class="content_box_header">' . $this->name . ' ' . $this->title . ' Console</h3>
				</div>
				<div class="green_content">
					<div class="button_79"><a class="console" href="index.php?view=group_function&cmd=add_squadron&id=1&parentdivision=' . $this->id . '">Add Squadron</a></div>
					<div class="button_153"><a class="console" href="index.php?view=group_function&cmd=process_applications&id=' . $this->id . '"><font style="font-size: 8px;">Approve/Deny Applications(' . $group_stats->numApplications($this->id) . ')</font></a></div>
					<div class="button_93"><a class="console" href="index.php?view=group_function&cmd=edit_info&id=' . $this->id . '">Edit ' . $this->title . ' Info</a></div>
					<div class="button_120"><a class="console" href="index.php?view=group_function&cmd=edit_member&id=' . $this->id . '">Edit ' . $this->title . ' Members</a></div>
					<div class="button_108"><a class="console" href="index.php?view=group_function&cmd=edit_leaders&id=' . $this->id . '">Modify Leadership</a></div>
					</div>
				<div class="green_content_bottom">
				</div>
			</div>
					');
				
				
					$sql_squads = 'select * from groups where parentcorps = ' . $row['id'] . ' and parentdivision = ' . $row_divisions['id'] . ' and squad = 1;';
					$result_squads = mysql_query($sql_squads);
					while( $row_squads = mysql_fetch_array($result_squads) )
					{
						// Display Squads Console Here
						$this->setInfo($row_squads['id']);
						echo('
						<div class="green_contentbox">
				<div class="green_content_top">
					<h3 class="content_box_header">' . $this->name . ' ' . $this->title . ' Console</h3>
				</div>
				<div class="green_content">
						<div class="button_153"><a class="console" href="index.php?view=group_function&cmd=process_applications&id=' . $this->id . '"><font style="font-size: 8px;">Approve/Deny Applications(' . $group_stats->numApplications($this->id) . ')</font></a></div>
						<div class="button_108"><a class="console" href="index.php?view=group_function&cmd=edit_info&id=' . $this->id . '">Edit ' . $this->title . ' Info</a></div>
						<div class="button_120"><a class="console" href="index.php?view=group_function&cmd=edit_member&id=' . $this->id . '">Edit ' . $this->title . ' Members</a></div>
						<div class="button_93"><a class="console" href="index.php?view=group_function&cmd=edit_leaders&id=' . $this->id . '">Modify Leadership</a></div>
						</div>
				<div class="green_content_bottom">
				</div>
			</div>
						');
					}
				}
			}
			else if( $row['division'] == 1 && $this->isAdmin($row['id'], $username) )
			{
				if( !$this->isLeader( $row['parentcorps'], $username ) )
				{
				// Echo Division Console
					$this->setInfo($row['id']);
					echo('
					<div class="green_contentbox">
				<div class="green_content_top">
					<h3 class="content_box_header">' . $this->name . ' ' . $this->title . ' Console</h3>
				</div>
				<div class="green_content">
					<div class="button_79"><a class="console" href="index.php?view=group_function&cmd=add_squadron&id=1&parentdivision=' . $this->id . '">Add Squadron</a></div>
					<div class="button_153"><a class="console" href="index.php?view=group_function&cmd=process_applications&id=' . $this->id . '"><font style="font-size: 8px;">Approve/Deny Applications(' . $group_stats->numApplications($this->id) . ')</font></a></div>
					<div class="button_93"><a class="console" href="index.php?view=group_function&cmd=edit_info&id=' . $this->id . '">Edit ' . $this->title . ' Info</a></div>
					<div class="button_120"><a class="console" href="index.php?view=group_function&cmd=edit_member&id=' . $this->id . '">Edit ' . $this->title . ' Members</a></div>
				 	<div class="button_108"><a class="console" href="index.php?view=group_function&cmd=edit_leaders&id=' . $this->id . '">Modify Leadership</a></div>
					</div>
				<div class="green_content_bottom">
				</div>
			</div>
					');
				
					$sql_squads = 'select * from groups where parentdivision = ' . $row['id'] . ' and squad = 1;';
					$result_squads = mysql_query($sql_squads);
					while( $row_squads = mysql_fetch_array($result_squads) )
					{
						// Display Squads Console Here
						$this->setInfo($row_squads['id']);
						echo('
						<div class="green_contentbox">
				<div class="green_content_top">
					<h3 class="content_box_header">' . $this->name . ' ' . $this->title . ' Console</h3>
				</div>
				<div class="green_content">
						<div class="button_153"><a class="console" href="index.php?view=group_function&cmd=process_applications&id=' . $this->id . '"><font style="font-size: 8px;">Approve/Deny Applications(' . $group_stats->numApplications($this->id) . ')</font></a></div>
						<div class="button_108"><a class="console" href="index.php?view=group_function&cmd=edit_info&id=' . $this->id . '">Edit ' . $this->title . ' Info</a></div>
						<div class="button_120"><a class="console" href="index.php?view=group_function&cmd=edit_member&id=' . $this->id . '">Edit ' . $this->title . ' Members</a></div>
						<div class="button_93"><a class="console" href="index.php?view=group_function&cmd=edit_leaders&id=' . $this->id . '">Modify Leadership</a></div>
						</div>
				<div class="green_content_bottom">
				</div>
			</div>
					');
					}
				}
			}
			else if( $row['squad'] == 1 && $this->isAdmin($row['id'], $username) )
			{
				if( !$this->isLeader( $row['parentcorps'], $username ) && !$this->isLeader( $row['parentdivision'], $username ) )
				{
				// Echo Squad Console
					$this->setInfo($row['id']);
					echo('
					<div class="green_contentbox">
				<div class="green_content_top">
					<h3 class="content_box_header">' . $this->name . ' ' . $this->title . ' Console</h3>
				</div>
				<div class="green_content">
					<div class="button_153"><a class="console" href="index.php?view=group_function&cmd=process_applications&id=' . $this->id . '"><small>Approve/Deny Applications(' . $group_stats->numApplications($this->id) . ')</small></a></div>
					<div class="button_108"><a class="console" href="index.php?view=group_function&cmd=edit_info&id=' . $this->id . '">Edit ' . $this->title . ' Info</a></div>
					<div class="button_120"><a class="console" href="index.php?view=group_function&cmd=edit_member&id=' . $this->id . '">Edit ' . $this->title . ' Members</a></div>
					<div class="button_93"><a class="console" href="index.php?view=group_function&cmd=edit_leaders&id=' . $this->id . '">Modify Leadership</a></div>
					</div>
				<div class="green_content_bottom">
				</div>
			</div>
					');
				}
			}
		}
		
	} // End Function
	
//  Determines if a user is in a squad or not.
	function isIn($id, $username)
	{
		$sql = 'select * from group_users where username = "' . $username . '" and gid = ' . $id . ';';
		$result = mysql_query($sql);
		if( mysql_num_rows($result) >=1 )
			return true;
		else
			return false;
	
	}


};

$group = new Group;

// >>>>    Begin Class Definition for Corps     <<<//

class Corps extends Group
{

};

// >>>>    Begin Class Definition for Divisions     <<<//

class Division extends Group
{
	function createDivision( $name, $description, $leader1, $leader2, $gameid, $image, $parentcorps )
	{
				global $db, $form, $notification, $config, $user;
	
		 /* Username error checking */
		$field = "name";
		 if( !$name || strlen($name) == 0 )
			{$form->setError($field, " * Name not entered");}
			
		 /* Password error checking */
		 $field = "leader1";
		 if( !$leader1 || strlen($leader1) == 0 )
			{$form->setError($field, " * No leader selected");}
		
		if( $db->rank_numFromUsername($leader1) < $config->getSetting('minRankDivision') )
			{$form->setError($field, " * User must be at least a " . $db->rankFromRank_Num($config->minRankDisivion) . "");}
			
		// Disablement Error Checking
		$field = "leader2";
		 if( $db->rank_numFromUsername($leader2) < $config->getSetting('minRankCorps') && $leader2 != 0 )
			{$form->setError($field, " * User must be at least a " . $db->rankFromRank_Num($config->minRankDisivion) . "");}
		
		// Parent Error Checking - Security //
		$field = "security";
			if( !$this->isAdmin( $parentcorps, $user->username ) )
			{$form->setError($field, " * You must be an admin of the Parent Corps to add that division.");}

			
			
		if( $form->num_errors == 0 )
			{
			// The information the user submited is correct
			$db->createDivision( $name, $description, $leader1, $leader2, $gameid, $image, $parentcorps );
			$db->setLoginInfo($user->username, time(), $_SERVER['REMOTE_ADDR']);
			$event = 'The ' . $name . ' Division was created by ' . $user->username . '.';
			$db->addToLogs($event, $user->username, $username);
			return $event;
			}
		else
			{
			// The information the user submited is incorrect
			return 'error';
			}
	}
};

// >>>>    Begin Class Definition for Squadrons     <<<//

class Squadron extends Group
{
	function createSquadron( $name, $description, $leader1, $leader2, $gameid, $image, $parentcorps, $parentdivision )
	{
		global $db, $form, $notification, $config, $user;
	
		 /* Username error checking */
		$field = "name";
		 if( !$name || strlen($name) == 0 )
			{$form->setError($field, " * Name not entered");}
			
		 /* Password error checking */
		 $field = "leader1";
		 if( !$leader1 || strlen($leader1) == 0 )
			{$form->setError($field, " * No leader selected");}
		
		if( $db->rank_numFromUsername($leader1) < $config->getSetting('minRankSquadron') )
			{$form->setError($field, " * User must be at least a " . $db->rankFromRank_Num($config->getSetting('minRankSquadron')) . "");}
			
		// Disablement Error Checking
		$field = "leader2";
		 if( $db->rank_numFromUsername($leader2) < $config->getSetting('minRankCorps') && $leader2 != 0 )
			{$form->setError($field, " * User must be at least a " . $db->rankFromRank_Num($config->getSetting('minRankSquadron')) . "");}
		
		
		// Parent Error Checking - Security //
		$field = "security";
			if( !$this->isAdmin( $parentcorps, $user->username ) && !$this->isAdmin( $parentdivision, $user->username ) )
			{$form->setError($field, " * You must be an admin of the either the Parent Corps or the Parent Division to add that squadron.");}

			
			
		if( $form->num_errors == 0 )
			{
			// The information the user submited is correct
			$db->createSquadron( $name, $description, $leader1, $leader2, $gameid, $image, $parentcorps, $parentdivision );
			$db->setLoginInfo($user->username, time(), $_SERVER['REMOTE_ADDR']);
			$event = 'The ' . $name . ' Squadron was created by ' . $user->username . '.';
			$db->addToLogs($event, $user->username, $username);
			return $event;
			}
		else
			{
			// The information the user submited is incorrect
			return 'error';
			}
	}
	
};

// Class that takes a user and gets their infomation for display on a goup page. //

class Group_User
{
var $username;
var $title;
var $joined;
var $gid;

function Group_User($username, $id)
{
	$sql = 'select * from group_users where username = "' . $username . '" and gid = ' . $id . ' limit 1;';
	$result = mysql_query($sql);
	$object = mysql_fetch_object($result);
	$this->username = $username;
	$this->title = $object->title;
	$this->joined = $object->joined;
	$this->gid = $id;
}

function displayRow()
{
	global $db;
	echo('
	<tr class="groupUserRow">
		<td class="groupUserCol_rank">
			' . $db->rankFromUsername($this->username) . '
		</td>
		<td class="groupUserCol_username">
			' . $this->username . '
		</td>
		<td class="groupUserCol_title">
			' . $this->title . '
		</td>
		<td class="groupUserCol_joined">
			' . dateFromTimestamp_Med($this->joined) . '
		</td>
	</tr>
	');
}

};

// Class that takes an event and gets its infomation for display on a goup page. //
class Group_Event
{
var $title;
var $gid;
var $username;
var $text;
var $time;

function Group_Event($id)
{
	$sql = 'select * from group_events where id = ' . $id . ' limit 1;';
	$result = mysql_query($sql);
	$array = mysql_fetch_array($result);
	$this->title = $array['title'];
	$this->gid = $array['gid'];
	$this->username = $array['username'];
	$this->text = $array['text'];
	$this->time = $array['time'];
}

function displayRow()
{
	global $db;
	echo('
	<tr class="groupEventRow">
		<td class="groupEventCol_title">
			' . $this->title . '
		</td>
		<td class="groupEventCol_username">
			' . $this->username . '
		</td>
		<td class="groupEventCol_text">
			' . $this->text . '
		</td>
		<td class="groupEventCol_time">
			' . dateFromTimestamp_Med($this->time) . '
		</td>
	</tr>
	');
}

};

class Group_Statistics
{
	function numMembers($id)
	{
		$sql = 'select * from group_users where gid = ' . $id . ';';
		$result = mysql_query($sql);
		$num = mysql_num_rows($result);
		return $num;
	}
	
	function numApplications($id)
	{
		$sql = 'select * from group_applications where gid = ' . $id . ';';
		$result = mysql_query($sql);
		return mysql_num_rows($result);
	}
};
$group_stats = new Group_Statistics;


// Build the class that displays all of the forms used by groups //
class Group_Form
{
	function selectUsers($minrank)
	{
		global $db;
		$sql = 'select username from users where rank >= ' . $minrank . ' and disabled = 0 order by rank desc;';
		$result = mysql_query($sql);
		while( $object = mysql_fetch_object($result) )
		{
			echo('<option value="' . $object->username . '">' . $db->titleFromUsername($object->username) . '</option>');
		}
	}
	
	function selectCorps($username)
	{
		$sql = 'select * from groups where corps = 1;';
		$result = mysql_query($sql);
		while( $object = mysql_fetch_object($result) )
		{
			$group_local = new Group;
			if( $group_local->isAdmin($object->id, $username) )
			{
				echo('<option value="' . $object->id . '">' . $object->name . ' ' . $object->title . '</option>');
			}
			
		}
	
	}
	
	
	function selectDivisions($username)
	{
		$sql = 'select * from groups where division = 1;';
		$result = mysql_query($sql);
		while( $object = mysql_fetch_object($result) )
		{
			$group_local = new Group;
			if( $group_local->isAdmin($object->id, $username) )
			{
				echo('<option value="' . $object->id . '">' . $object->name . ' ' . $object->title . '</option>');
			}
			
		}
	
	}
	
	function selectSquadrons($username)
	{
		$sql = 'select * from groups where squad = 1;';
		$result = mysql_query($sql);
		while( $object = mysql_fetch_object($result) )
		{
			$group_local = new Group;
			if( $group_local->isAdmin($object->id, $username) )
			{
				echo('<option value="' . $object->id . '">' . $object->name . ' ' . $object->title . '</option>');
			}
			
		}
	
	
	}
	
	
	function listMembers($id)
	{
		global $user;
		$group_local = new Group;
		if( $group_local->isAdmin( $id, $user->username ) )
		{
			$sql = 'select * from group_users where gid = ' . $id . ' order by joined asc;';
			$result = mysql_query($sql);
			while( $row = mysql_fetch_object($result) )
			{
				echo('
				<tr>
					<td>
					' . $row->username . '
					</td>
					<td>
					' . $row->title . '
					</td>
					<td>
						<form action="' . $_SERVER['PHP_SELF'] . '" method="get">
							<input type="hidden" name="view" value="group_function" />
							<input type="hidden" name="do" value="edit" />
							<input type="hidden" name="cmd" value="edit_member" />
							<input type="hidden" name="id" value="' . $id . '" />
							<input type="hidden" name="username" value="' . $row->username . '" />
							<input type="hidden" name="userid" value="' . $row->id . '" />
							<input type="submit" value="Edit" class="button" />
						</form>
					</td>
					<td>
						<form action="' . $_SERVER['PHP_SELF'] . '" method="get">
							<input type="hidden" name="view" value="group_function" />
							<input type="hidden" name="do" value="remove" />
							<input type="hidden" name="cmd" value="edit_member" />
							<input type="hidden" name="id" value="' . $id . '" />
							<input type="hidden" name="username" value="' . $row->username . '" />
							<input type="hidden" name="userid" value="' . $row->id . '" />
							<input type="submit" value="Remove" class="button" />
						</form>
					</td>
				</tr>
				');
			}
		}
	}
	
	function addCorps()
	{
	global $db, $user, $form, $group, $games, $config;
		echo('
		<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">Add a New Corps</h3>
								</div>
							</div>
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '
								</h4>
							</div>
		<form action="process.php" method="POST">
		<table class="form" border="0" cellspacing="5" cellpadding="3">
			<tr>
				<td>
				<h4 class="form_label_cb">Name:</h4>
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
				<h4 class="form_label_cb">Description</h4>
				</td>
				<td>
					<textarea class="textarea" name="description">' . $array['description'] . '</textarea>
				</td>
				<td>
					<font class="error">' . $form->error("description") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">Leader 1:</h4>
				</td>
				<td>
					<select name="leader1"  class="select">
					<option value="' . $array['leader1'] . '">' . $array['leader1'] . '</option>
					');
					$this->selectUsers($config->getSetting('minRankCorps'));
					echo('
					</select>
				</td>
				<td>
					<font class="error">' . $form->error("leader1") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">Leader 2:</h4>
				</td>
				<td>
					<select name="leader2" class="select">
					<option value="' . $array['leader2'] . '">' . $array['leader2'] . '</option>
					');
					$this->selectUsers($config->getSetting('minRankCorps'));
					echo('
					</select>
				</td>
				<td>
					<font class="error">' . $form->error("leader2") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">Corps Image: </h4>
				</td>
				<td>
					<input type="file" class="text" name="image" />
				</td>
				<td>
					<font class="error">' . $form->error("image") . '</font>
				</td>
			</tr>
				<tr>
				<td colspan="0">
					<input type="submit" value="Add Corps" />
				</td>
			</tr>
		</table>
		<input type="hidden" name="cmd" value="new_corps" />
		</form>
		<br />
		<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
					</div>
					<div align="center"><div class="content_box_footer"></div></div>
				</div>');
	
	}
	
	
	function listApplications($id)
	{
		$sql = 'select * from group_applications where gid = ' . $id . ';';
		$result = mysql_query($sql);
		while( $row = mysql_fetch_object($result) )
		{
			echo('
			<tr>
			<td colspan="3">
			<hr class="cb" />
			<h3>Username: ' . $row->username . '</h3>
			<h4>Reason for Applying</h4>
			<p>' . $row->reason . '</p>
			<h4>Additional Comments</h4>
			<p>' . $row->comments . '</p>
			<br />
			<form id="approve" action="process.php" method="post">
				<input type="hidden" name="cmd" value="approve_application" />
				<input type="hidden" name="appid" value="' . $row->id . '" />
				<input type="submit" value="Approve" />
			</form>
			<form id="deny" action="process.php" method="post">
				<input type="hidden" name="cmd" value="deny_application" />
				<input type="hidden" name="appid" value="' . $row->id . '" />
				<input type="submit" value="Deny" />
			</form>
			</td>
			</tr>
			');	
		}
	}
	
	function addDivision($username)
	{
		global $db, $user, $form, $group, $games, $config, $array;
		echo('
		<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">Add a New Division</h3>
								</div>
							</div>
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '

									' . $form->error("security") . '
								</h4>
							</div>
		<form action="process.php" method="POST">
		<table class="form" border="0" cellspacing="5" cellpadding="3">
			<tr>
				<td>
				<h4 class="form_label_cb">Name:</h4>
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
				<td>
					<textarea class="textarea" name="description">' . $array['description'] . '</textarea>
				</td>
				<td>
					<font class="error">' . $form->error("description") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">Leader 1:</h4>
				</td>
				<td>
					<select name="leader1"  class="select">
					<option value="' . $array['leader1'] . '">' . $array['leader1'] . '</option>
					');
					$this->selectUsers($config->getSetting('minRankDivision'));
					echo('
					</select>
				</td>
				<td>
					<font class="error">' . $form->error("leader1") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">Leader 2:</h4>
				</td>
				<td>
					<select name="leader2" class="select">
					<option value="' . $array['leader2'] . '">' . $array['leader2'] . '</option>
					');
					$this->selectUsers($config->getSetting('minRankDivision'));
					echo('
					</select>
				</td>
				<td>
					<font class="error">' . $form->error("leader2") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">Division Image: </h4>
				</td>
				<td>
					<input type="file" class="text" name="image" />
				</td>
				<td>
					<font class="error">' . $form->error("image") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">Game:</h4>
				<p class="form_description">leave blank for no game</p>
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
				<td>
				<h4 class="form_label_cb">Parent Corps</h4>
				</td>
				<td>
					');
					if( $_GET['parentcorps'] )
					{
						$parentcorps = new Corps;
						$parentcorps->setInfo($_GET['parentcorps']);
						if( $parentcorps->isAdmin($_GET['parentcorps'], $user->username) )
						{
							echo($parentcorps->name . ' Corps');
							echo('<input type="hidden" name="parentcorps" value="' . $_GET['parentcorps'] . '" />');
						}
						else
						{
							echo('<select name="parentcorps" class="select">
							');
							
							$sql = 'select * from groups where corps = 1;';
							$result = mysql_query($sql);
							while( $object = mysql_fetch_object($result) )
							{
								$group_local = new Group;
								if( $group_local->isAdmin( $object->id, $username ) )
								{
									echo('<option value="' . $object->id . '">
											' . $object->name . ' ' . $object->title . '
									    	</option>');
								}
							}
							echo('</select>');
						}
					}
					else
					{
						echo('<select name="parentcorps" class="select">
							');
							
							$sql = 'select * from groups where corps = 1;';
							$result = mysql_query($sql);
							while( $object = mysql_fetch_object($result) )
							{
								$group_local = new Group;
								if( $group_local->isAdmin( $object->id, $username ) )
								{
									echo('<option value="' . $object->id . '">
											' . $object->name . ' ' . $object->title . '
									    	</option>');
								}
							}
							echo('</select>');
					}
					echo('
				</td>
				<td>
					<font class="error">' . $form->error("parentcorps") . '</font>
				</td>
			</tr>
				<tr>
				<td colspan="0">
					<input type="submit" value="Add Division" />
					<input type="reset" value="Reset Values" />
				</td>
			</tr>
		</table>
		<input type="hidden" name="cmd" value="new_division" />
		</form>
		<br />
		<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
					</div>
					<div align="center"><div class="content_box_footer"></div></div>
				</div>');
	
	}
	
	function addSquadron($username)
	{
				global $db, $user, $form, $group, $games, $config, $array;
		echo('
		<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">Add a New Squadron</h3>
								</div>
							</div>
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '
									' . $form->error("security") . '
								</h4>
							</div>
		<form action="process.php" method="POST">
		<table class="form" border="0" cellspacing="5" cellpadding="3">
			<tr>
				<td>
				<h4 class="form_label_cb">Name:</h4>
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
				<td>
					<textarea class="textarea" name="description">' . $array['description'] . '</textarea>
				</td>
				<td>
					<font class="error">' . $form->error("description") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">Leader 1:</h4>
				</td>
				<td>
					<select name="leader1"  class="select">
					<option value="' . $array['leader1'] . '">' . $array['leader1'] . '</option>
					');
					$this->selectUsers($config->getSetting('minRankSquad'));
					echo('
					</select>
				</td>
				<td>
					<font class="error">' . $form->error("leader1") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">Leader 2:</h4>
				</td>
				<td>
					<select name="leader2" class="select">
					<option value="' . $array['leader2'] . '">' . $array['leader2'] . '</option>
					');
					$this->selectUsers($config->getSetting('minRankSquad'));
					echo('
					</select>
				</td>
				<td>
					<font class="error">' . $form->error("leader2") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">Squadron Image: </h4>
				</td>
				<td>
					<input type="file" class="text" name="image" />
				</td>
				<td>
					<font class="error">' . $form->error("image") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">Parent Division:</h4>
				</td>
				<td>
					');
					if( $_GET['parentdivision'] )
					{
						$parentdivision = new Corps;
						$parentdivision->setInfo($_GET['parentdivision']);
						if( $parentdivision->isAdmin($_GET['parentdivision'], $user->username) )
						{
							echo($parentdivision->name . ' Division');
							echo('<input type="hidden" name="parentdivision" value="' . $_GET['parentdivision'] . '" />');
						}
						else
						{
							echo('<select name="parentdivision" class="select">
							');
							
							$sql = 'select * from groups where division = 1;';
							$result = mysql_query($sql);
							while( $object = mysql_fetch_object($result) )
							{
								$group_local = new Group;
								if( $group_local->isAdmin( $object->id, $username ) )
								{
									echo('<option value="' . $object->id . '">
											' . $object->name . ' ' . $object->title . '
									    	</option>');
								}
							}
							echo('</select>');
						}
					}
					else
					{
						echo('<select name="parentdivision" class="select">
							');
							
							$sql = 'select * from groups where division = 1;';
							$result = mysql_query($sql);
							while( $object = mysql_fetch_object($result) )
							{
								$group_local = new Group;
								if( $group_local->isAdmin( $object->id, $username ) )
								{
									echo('<option value="' . $object->id . '">
											' . $object->name . ' ' . $object->title . '
									    	</option>');
								}
							}
							echo('</select>');
					}
					echo('
				</td>
				<td>
					<font class="error">' . $form->error("parentdivision") . '</font>
				</td>
			</tr>
				<tr>
				<td colspan="0">
					<input type="submit" value="Add Squadron" />
				</td>
			</tr>
		</table>
		<input type="hidden" name="cmd" value="new_squadron" />
		</form>
		<br />
		<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
					</div>
					<div align="center"><div class="content_box_footer"></div></div>
				</div>');
	
	}
	
	function application($id)
	{
		global $db, $user, $form, $group, $games, $config, $array;
		$group_local = new Group;
		$group_local->setInfo($id);
		echo('
		<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">Submit an Application</h3>
								</div>
							</div>
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									<p>' . $_SESSION['event'] . '</p>
									<p>'. $form->error("security") . '</p>
									<p>You are applying for the ' . $group_local->name . ' ' . $group_local->title . '</p>
								</h4>
							</div>
		<form action="process.php" method="POST">
		<table class="form" border="0" cellspacing="5" cellpadding="3">
			<th colspan="3">
			Please in the spaces below tell us why you should be in this ' . $group_local->title . ' and in the comments box, tell us any other things we should consider.
			</th>
			<tr>
				<td>
				<h4 class="form_label_cb">Reason: </h4>
				</td>
				<td>
					<textarea class="textarea" name="reason" cols="40" rows="7">' . $array['reason'] . '</textarea>
				</td>
				<td>
					<font class="error">' . $form->error("reason") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">Comments: </h4>
				</td>
				<td>
					<textarea class="textarea" name="comments" cols="40" rows="7">' . $array['comments'] . '</textarea>
				</td>
				<td>
					<font class="error">' . $form->error("comments") . '</font>
				</td>
			</tr>
				<tr>
				<td colspan="3">
					<input type="submit" value="Submit" />
				</td>
			</tr>
		</table>
		<input type="hidden" name="cmd" value="group_application" />
		<input type="hidden" name="groupid" value="' . $_GET['id'] . '" />
		</form>
		<br />
		<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
					</div>
					<div align="center"><div class="content_box_footer"></div></div>
				</div>');
	}
	
	function processApplication($id)
	{
		global $db, $user, $form, $group, $games, $config, $array;
		$group = new Group;
		$group->setInfo($id);
		echo('
		<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">Process ' . $group->title . ' Applications</h3>
								</div>
							</div>
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '
									' . $form->error("security") . '
									<p>Processing Applications for the ' . $group->name . ' ' . $group->title . '.</p>
								</h4>
							</div>
		<table class="form" border="0" cellspacing="5" cellpadding="3">
			<th colspan="3">This is the form that will allow you to either approve or deny applications into your group.</th>
			');
				$this->listApplications($id);
			echo('
		</table>
		<br />
		<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
					</div>
					<div align="center"><div class="content_box_footer"></div></div>
				</div>');
	}
	
	function editInfo($username, $id)
	{
		global $db, $user, $form, $group, $games, $config, $array;
		$group_local = new Group;
		$group_local->setInfo($id);
		if( !$_GET['id'] )
		{
		}
		else
		{
			echo('
			<div class="contentbox">
						<div align="center">
							<div class="content_back">
								<div align="center">
									<div class="content_header">
										<h3 class="content_box_header">Modify ' . $group_local->title . ' Info</h3>
									</div>
								</div>
								<div class="content_box_minor_header">
									<h4 class="content_box_minor_header">
										<p>' . $_SESSION['event'] . '</p>
										<p>' . $form->error("security") . '</p>
									</h4>
								</div>
			<form action="process.php" method="POST">
			<table class="form" border="0" cellspacing="5" cellpadding="3">
			');
			$group_local = new Group;
			if( $group_local->isAdmin( $_GET['id'], $username )  )
			{
				$group_local->setInfo( $_GET['id'] );
				echo('
				<th colspan="3">
					You have chosen to modify the ' . $group_local->name . ' ' . $group_local->title . '.
				</th>
				<tr>
					<td>
						<h4 class="form_label_cb">' . $group_local->title . ' Name: </h4>
					</td>
					<td>
						<input type="text" class="text" name="name" value="' . $group_local->name . '" />
					</td>
					<td>
						<font class="error">' . $form->error("name") . '</font>
					</td>
				</tr>
				<tr>
					<td valign="top" rowspan="2">
						<h4 class="form_label_cb">Description:</h4>
					</td>
					<td colspan="2">
						<textarea cols="35" rows="10" class="textarea" name="description">' . $group_local->description . '</textarea>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<font class="error">' . $form->error("description") . '</font>
					</td>
				</tr>
				<tr>
					<td>
						<h4 class="form_label_cb">Image: </h4>
					</td>
					<td>
						');
						if( !$group_local->image )
						{
							echo('There is no image set for this ' . $group_local->title . ' yet.<br />');
							echo('<input type="file" class="text" name="image" />');
						}
						else
						{
							echo('<img src="upload/' . $group_local->image . '" alt="" /><br />');
							echo('<input type="file" class="text" name="image" />');
						}
						
						echo('
					</td>
					<td>
						<font class="error">' . $form->error("image") . '</font>
					</td>
				</tr>
				');

				if( $group_local->title == 'Division' )
				{
					echo('
					<tr>
						<td>
							<h4 class="form_label_cb">Parent Corps:</h4>
						</td>
						<td>
							<select name="parentcorps">
								<option value="' . $group_local->parentcorps . '">' . $group_local->nameFromId($group_local->parentcorps) . '</option>
								');
								$this->selectCorps($username);
								echo('
							</select>
						</td>
						<td>
							<font class="error">' . $form->error("parentcorps") . '</font>
						</td>
					</tr>
					<tr>');
				}
				else if( $group_local->title == 'Squadron' )
				{
					echo('
					<tr>
						<td>
							<h4 class="form_label_cb">Parent Division:</h4>
						</td>
						<td>
							<select name="parentdivision">
								<option value="' . $group_local->parentdivision . '">' . $group_local->nameFromId($group_local->parentdivision) . '</option>
								');
								$this->selectDivisions($username);
								echo('
							</select>
						</td>
						<td>
							<font class="error">' . $form->error("parentdivision") . '</font>
						</td>
					</tr>
					<tr>');
				}
				echo('
					<td colspan="3">
						<input type="submit" value="Submit" />
						<input type="reset" value="Reset Form" />
					</td>
				</tr>
			</table>
			<input type="hidden" name="cmd" value="group_edit_info" />
			<input type="hidden" name="groupid" value="' . $group_local->id . '" />
			</form>
			<br />
			<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
							</div>
						</div>
						<div align="center"><div class="content_box_footer"></div></div>
					</div>');
			}	
			else
			{
			die('You do not have permission to modify that group... Stop Snooping, logging event... logging ip address...');
			}
		}
	
	}
	
	function editMember($id)
	{
		global $db, $user, $form, $group, $games, $config, $array, $notification;
		$group_local = new Group;
		$group_local->setInfo($id);
		if( $group_local->isAdmin( $id, $user->username ) )
		{
			echo('
			<div class="contentbox">
						<div align="center">
							<div class="content_back">
								<div align="center">
									<div class="content_header">
										<h3 class="content_box_header">
											Edit / Remove member - ' . $group_local->name . ' ' . $group_local->title . ' division
										</h3>
									</div>
								</div>
								<div class="content_box_minor_header">
									<h4 class="content_box_minor_header">
										<p>' . $_SESSION['event'] . '</p>
									</h4>
								</div>
					');
					
			if( !$_GET['username'] )
			{
				echo('
				<table class="form" border="0" cellspacing="5" cellpadding="3">
					<th colspan="4">
					Step 1 - Choose a member to edit or remove and then on the following page follow the instructions
					</th>
					');
					$this->listMembers($id);
					echo('
				</table>
				');
			}
			else
			{
				switch( $_GET['do'] )
				{
					case 'edit':
					$group_user_local = new Group_User( $_GET['username'], $id );
						echo('
						<form action="process.php" method="POST">
						<table class="form" border="0" cellspacing="5" cellpadding="3">
							<th colspan="3">
							Step 2 - Fill out the required information hit submit.
							</th>
							<tr>
								<td>
								<h4 class="form_label_cb">' . $group_local->title . ' Title: </h4>
								</td>
								<td>
									<input type="text" class="text" name="title" value="' . $group_user_local->title . '" />
								</td>
								<td>
									<font class="error">' . $form->error("title") . '</font>
								</td>
							</tr>
							<tr>
								<td colspan="3">
									<input type="submit" value="Submit" />
								</td>
							</tr>
						</table>
						<input type="hidden" name="username" value="' . $_GET['username'] . '" />
						<input type="hidden" name="groupid" value="' . $id . '" />
						<input type="hidden" name="userid" value="' . $_GET['userid'] . '" />
						<input type="hidden" name="cmd" value="group_edit_member" />
						</form>
						');
					break;
					
					case 'remove':
						echo('
						<form action="process.php" method="POST">
						<table class="form" border="0" cellspacing="5" cellpadding="3">
							<th colspan="1">
								Step 2 - Are you sure you want to remove ' . $db->titleFromUsername($_GET['username']) . ' from the ' . $group_local->name . ' ' . $group_local->title . '?
							</th>
							<tr>
								<td colspan="1" align="center">
									<input type="submit" value="Remove User" />
								</td>
							</tr>
						</table>
						<input type="hidden" name="username" value="' . $_GET['username'] . '" />
						<input type="hidden" name="groupid" value="' . $id . '" />
						<input type="hidden" name="userid" value="' . $_GET['userid'] . '" />
						<input type="hidden" name="cmd" value="group_remove_member" />
						</form>');
					break;
				
				
				}
			
			}
				echo('
				<br />
				<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
								</div>
							</div>
							<div align="center"><div class="content_box_footer"></div></div>
						</div>');
		}
		else
		{
			echo('Stop snooping... Logging Event... Notifying Admin...');
			$event = 'An attempt to attack our website was made by ' . $_SERVER['REMOTE_ADDR'] . '.';
			$db->addToLogs($event, 0, 0);
			$notification->notifyAdmin('Security Notification', $event);
		}
	}
	
	function editLeaders($id)
	{
		global $db, $user, $form, $group, $games, $config, $array;
		$group_local = new Group;
		$group_local->setInfo($id);
		echo('
		<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">Modify ' . $group_local->title . ' Leaders</h3>
								</div>
							</div>
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									' . $_SESSION['event'] . '
								</h4>
							</div>
		<form action="process.php" method="POST">
		<table class="form" border="0" cellspacing="5" cellpadding="3">
			<th colspan="3">
				This is the form that allows you to edit who will lead the ' . $group_local->name . ' ' . $group_local->title . '.
			</th>
			<tr>
				<td>
				<h4 class="form_label_cb">Leader 1:</h4>
				</td>
				<td>
					<select name="leader1"  class="select">
					<option value="' . $group_local->leader1 . '">' . $db->titleFromUsername($group_local->leader1) . '</option>
					');
					$this->selectUsers($config->getSetting('minRankSquad'));
					echo('
					</select>
				</td>
				<td>
					<font class="error">' . $form->error("leader1") . '</font>
				</td>
			</tr>
			<tr>
				<td>
				<h4 class="form_label_cb">Leader 2:</h4>
				</td>
				<td>
					<select name="leader2" class="select">
					<option value="' . $group_local->leader2 . '">' . $db->titleFromUsername($group_local->leader2) . '</option>
					');
					$this->selectUsers($config->getSetting('minRankSquad'));
					echo('
					</select>
				</td>
				<td>
					<font class="error">' . $form->error("leader2") . '</font>
				</td>
			</tr>
				<tr>
				<td colspan="0">
					<input type="submit" value="Modify Leadership" />
				</td>
			</tr>
		</table>
		<input type="hidden" name="cmd" value="edit_leaders" />
		<input type="hidden" name="groupid" value="' . $id . '" />
		</form>
		<br />
		<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
					</div>
					<div align="center"><div class="content_box_footer"></div></div>
				</div>');
	}
	
	function postEvent($id)
	{
		global $db, $user, $form, $group, $games, $config, $array;
		$group_local = new Group;
		$group_local->setInfo($id);
		echo('
		<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">Post a ' . $group_local->title . ' event</h3>
								</div>
							</div>
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									<p>' . $_SESSION['event'] . '</p>
								</h4>
							</div>
		<form action="process.php" method="POST">
		<table class="form" border="0" cellspacing="5" cellpadding="3">
			<th colspan="3">
			This is the form that will allow you to post a new event that will display on the ' . $group_local->title . '\'s main page.
			</th>
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
				<h4 class="form_label_cb">Text: </h4>
				</td>
				<td colspan="2">
					<textarea class="textarea" name="text" cols="40" rows="15">' . $array['text'] . '</textarea>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<font class="error">' . $form->error("text") . '</font>
				</td>
			</tr>
				<tr>
				<td colspan="3">
					<input type="submit" value="Submit" />
				</td>
			</tr>
		</table>
		<input type="hidden" name="cmd" value="post_event" />
		</form>
		<br />
		<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
					</div>
					<div align="center"><div class="content_box_footer"></div></div>
				</div>');
	}
	
	function notifyMembers($id)
	{
		global $db, $user, $form, $group, $games, $config, $array;
		$group_local = new Group;
		$group_local->setInfo($id);
		echo('
		<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">Notify the members of the ' . $group_local->name . ' ' . $group_local->title . '</h3>
								</div>
							</div>
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									<p>' . $_SESSION['event'] . '</p>
								</h4>
							</div>
		<form action="process.php" method="POST">
		<table class="form" border="0" cellspacing="5" cellpadding="3">
			<th colspan="3">
				This form allows you to send a notification message to everyone in your group which will appear when they log in as a pop-up box.
			</th>
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
				<h4 class="form_label_cb">Text: </h4>
				</td>
				<td colspan="2">
					<textarea class="textarea" name="text" cols="40" rows="15">' . $array['text'] . '</textarea>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<font class="error">' . $form->error("text") . '</font>
				</td>
			</tr>
				<tr>
				<td colspan="3">
					<input type="submit" value="Submit" />
				</td>
			</tr>
		</table>
		<input type="hidden" name="cmd" value="notify_group" />
		</form>
		<br />
		<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
					</div>
					<div align="center"><div class="content_box_footer"></div></div>
				</div>');
	}
	
	function messageMembers($id)
	{
		global $db, $user, $form, $group, $games, $config, $array;
		$group_local = new Group;
		$group_local->setInfo($id);
		echo('
		<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">Message the members of the ' . $group_local->name . ' ' . $group_local->title . '</h3>
								</div>
							</div>
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									<p>' . $_SESSION['event'] . '</p>
								</h4>
							</div>
		<form action="process.php" method="POST">
		<table class="form" border="0" cellspacing="5" cellpadding="3">
			<th colspan="3">
				This form allows you to send a personal message to everyone in your group which will appear in their inbox.
			</th>
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
				<h4 class="form_label_cb">Text: </h4>
				</td>
				<td colspan="2">
					<textarea class="textarea" name="text" cols="40" rows="15">' . $array['text'] . '</textarea>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<font class="error">' . $form->error("text") . '</font>
				</td>
			</tr>
				<tr>
				<td colspan="3">
					<input type="submit" value="Submit" />
				</td>
			</tr>
		</table>
		<input type="hidden" name="cmd" value="message_group" />
		</form>
		<br />
		<div><a class="return" href="index.php?view=console">Click Here to return to your console</a></div>
						</div>
					</div>
					<div align="center"><div class="content_box_footer"></div></div>
				</div>');
	
	}
	
};
$group_form = new Group_Form;

class Group_Validation
{

	function createCorps( $name, $description, $leader1, $leader2, $image )
		{
			global $db, $form, $notification, $config, $user;
		
			 /* Username error checking */
			$field = "name";
			 if( !$name || strlen($name) == 0 )
				{$form->setError($field, " * Name not entered");}
				
			 /* Password error checking */
			 $field = "leader1";
			 if( !$leader1 || strlen($leader1) == 0 )
				{$form->setError($field, " * No leader selected");}
			
			if( $db->rank_numFromUsername($leader1) < $config->getSetting('minRankCorps') )
				{$form->setError($field, " * User must be at least a " . $db->rankFromRank_Num($config->getSetting('minRankCorps') ) . "");}
				
			// Disablement Error Checking
			$field = "leader2";
			 if( $db->rank_numFromUsername($leader2) < $config->getSetting('minRankCorps') && $leader2 != 0 )
				{$form->setError($field, " * User must be at least a " . $db->rankFromRank_Num($config->getSetting('minRankCorps')) . "");}
					
			$field = "security";
				if( $leader1 == $leader2 )
				{$form->setError($field, " * You cannot have the same person for both leader spots *");}		
				
			if( $form->num_errors == 0 )
				{
				// The information the user submited is correct
				$db->createCorps( $name, $description, $leader1, $leader2, $image );
				$sql = 'SELECT *FROM `groups` ORDER BY `groups`.`id` DESC Limit 1';
				$result = mysql_query($sql);
				$object = mysql_fetch_object($result);
				$gid = $object->id;
				$db->addGroupMember( $leader1, $gid, 'Leader' );
				if( strlen( $leader2 ) > 0 )
				{
				$db->addGroupMember( $leader2, $gid, 'Leader' );
				}
				$db->setLoginInfo($user->username, time(), $_SERVER['REMOTE_ADDR']);
				$event = 'The ' . $name . ' Corps was created by ' . $user->username . '.';
				$db->addToLogs($event, $user->username, $username);
				return $event;
				}
			else
				{
				// The information the user submited is incorrect
				return false;
				}
		}
		
	function createDivision( $name, $description, $leader1, $leader2, $gameid, $image, $parentcorps )
		{
		global $db, $form, $notification, $config, $user;
		
		$group_local = new Group;
			 /* Username error checking */
			$field = "name";
			 if( !$name || strlen($name) == 0 )
				{$form->setError($field, " * Name not entered");}
				
			 /* Password error checking */
			 $field = "leader1";
			 if( !$leader1 || strlen($leader1) == 0 )
				{$form->setError($field, " * No leader selected");}
			
			if( $db->rank_numFromUsername($leader1) < $config->getSetting('minRankDivision') )
				{$form->setError($field, " * User must be at least a " . $db->rankFromRank_Num($config->getSetting('minRankDivision')) . "");}
				
			// Disablement Error Checking
			$field = "leader2";
			 if( $db->rank_numFromUsername($leader2) < $config->getSetting('minRankCorps') && $leader2 != 0 )
				{$form->setError($field, " * User must be at least a " . $db->rankFromRank_Num($config->getSetting('minRankDivision')) . "");}
			
			// Parent Error Checking - Security //
			$field = "security";
				if( !$group_local->isAdmin( $parentcorps, $user->username ) )
				{$form->setError($field, " * You must be an admin of the Parent Corps to add that division.");}
				
				if( $leader1 == $leader2 )
				{$form->setError($field, " * You cannot have the same person for both leader spots *");}
				
				
			if( $form->num_errors == 0 )
				{
				// The information the user submited is correct
				$db->createDivision( $name, $description, $leader1, $leader2, $gameid, $image, $parentcorps );
				$sql = 'SELECT *FROM `groups` ORDER BY `groups`.`id` DESC Limit 1';
				$result = mysql_query($sql);
				$object = mysql_fetch_object($result);
				$gid = $object->id;
				$db->addGroupMember( $leader1, $gid, 'Leader' );
				if( strlen( $leader2 ) > 0 )
				{
				$db->addGroupMember( $leader2, $gid, 'Leader' );
				}
				$db->setLoginInfo($user->username, time(), $_SERVER['REMOTE_ADDR']);
				$event = 'The ' . $name . ' Division was created by ' . $user->username . '.';
				$db->addToLogs($event, $user->username, $username);
				return $event;
				}
			else
				{
				// The information the user submited is incorrect
				return false;
				}
		}
	
	function createSquadron( $name, $description, $leader1, $leader2, $gameid, $image, $parentcorps, $parentdivision )
		{
			global $db, $form, $notification, $config, $user;
			$group_local = new Group;
			 /* Username error checking */
			$field = "name";
			 if( !$name || strlen($name) == 0 )
				{$form->setError($field, " * Name not entered");}
				
			 /* Password error checking */
			 $field = "leader1";
			 if( !$leader1 || strlen($leader1) == 0 )
				{$form->setError($field, " * No leader selected");}
			
			if( $db->rank_numFromUsername($leader1) < $config->getSetting('minRankSquadron') )
				{$form->setError($field, " * User must be at least a " . $db->rankFromRank_Num($config->getSetting('minRankSquadron')) . "");}
				
			// Disablement Error Checking
			$field = "leader2";
			 if( $db->rank_numFromUsername($leader2) < $config->getSetting('minRankCorps') && $leader2 != 0 )
				{$form->setError($field, " * User must be at least a " . $db->rankFromRank_Num($config->getSetting('minRankSquadron')) . "");}
			
			
			// Parent Error Checking - Security //
			$field = "security";
				if( !$group_local->isAdmin( $parentcorps, $user->username ) && !$group_local->isAdmin( $parentdivision, $user->username ) )
				{$form->setError($field, " * You must be an admin of the either the Parent Corps or the Parent Division to add that squadron.");}
				
				if( $leader1 == $leader2 )
				{$form->setError($field, " * You cannot have the same person for both leader spots *");}
				
			if( $form->num_errors == 0 )
				{
				// The information the user submited is correct
				$db->createSquadron( $name, $description, $leader1, $leader2, $gameid, $image, $parentcorps, $parentdivision );
				$sql = 'SELECT *FROM `groups` ORDER BY `groups`.`id` DESC Limit 1';
				$result = mysql_query($sql);
				$object = mysql_fetch_object($result);
				$gid = $object->id;
				$db->addGroupMember( $leader1, $gid, 'Leader' );
				if( strlen( $leader2 ) > 0 )
				{
				$db->addGroupMember( $leader2, $gid, 'Leader' );
				}
				$db->setLoginInfo($user->username, time(), $_SERVER['REMOTE_ADDR-
				']);
				$event = 'The ' . $name . ' Squadron was created by ' . $user->username . '.';
				$db->addToLogs($event, $user->username, $username);
				return $event;
				}
			else
				{
				// The information the user submited is incorrect
				return false;
				}
		}
		
	function application( $reason, $comments, $groupid)
		{
			global $db, $form, $notification, $config, $user;
			$group_local = new Group;
			 /* Username error checking */
			$field = "reason";
			 if( !$reason || strlen($reason) == 0 )
				{$form->setError($field, " * Reason not entered");}
			
			// Security //
			$field = "security";
			 if( $group_local->isIn( $groupid, $user->username ) )
				{$form->setError($field, " * You are already in that group");}
				
				
			if( $form->num_errors == 0 )
				{
				// The information the user submited is correct
				$group_local->setInfo($groupid);
				$db->application( $user->username, $reason, $comments, $groupid );
				$db->setLoginInfo($user->username, time(), $_SERVER['REMOTE_ADDR']);
				$event = $user->title . ' has just submit an application for the ' . $group_local->name . ' ' . $group_local->title . '.';
				$db->addToLogs($event, $user->username, $username);
				return $event;
				}
			else
				{
				// The information the user submited is incorrect
				return false;
				}
		}
		
	function approveApplication( $appid, $username, $groupid )
		{
			global $db, $form, $notification, $config, $user;
			$group_local = new Group;
			$group_local->setInfo($groupid);
			
			// Security //
			$field = "security";
			 if( $group_local->isMember( $groupid, $username ) )
				{$form->setError($field, " * That user is already in that group");}
				
			if( !$group_local->isAdmin( $groupid, $user->username ) )
				{$form->setError($field, " You you must be an administrator of that group to approve an application ");}
				
				
			if( $form->num_errors == 0 )
				{
				// The information the user submited is correct
				$db->deleteApplication($appid);
				
				$db->addGroupMember($username, $groupid, 'Member');
				
				if( !$group_local->isIn( $group_local->parentcorps, $username ) && $group_local->parentcorps > 0 )
					{ $db->addGroupMember($username, $group_local->parentcorps, 'Member'); }
					
				if( !$group_local->isIn( $group_local->parentdivision, $username ) && $group_local->parentdivision > 0 )
					{ $db->addGroupMember($username, $group_local->parentdivision, 'Member'); }
					
				$db->setLoginInfo($user->username, time(), $_SERVER['REMOTE_ADDR']);
				$event = $db->titleFromUsername($username) . ' has been accepted into the ' . $group_local->name . ' ' . $group_local->title . ' by ' . $user->title . '.';
				$db->addToLogs($event, $user->username, $username);
				return $event;
				}
			else
				{
				// The information the user submited is incorrect
				return false;
				}
		}
		
	function denyApplication( $appid, $username, $groupid )
		{
			global $db, $form, $notification, $config, $user;
			$group_local = new Group;
			$group_local->setInfo($groupid);
			// Security //
			$field = "security";
			 if( $group_local->isMember( $groupid, $username ) )
				{$form->setError($field, " * That user is already in that group");}
				
			if( !$group_local->isAdmin( $groupid, $user->username ) )
				{$form->setError($field, " You you must be an administrator of that group to approve an application ");}
				
				
			if( $form->num_errors == 0 )
				{
				// The information the user submited is correct
				$db->deleteApplication($appid);
				$db->setLoginInfo($user->username, time(), $_SERVER['REMOTE_ADDR']);
				$event = $db->titleFromUsername($username) . ' has been denied acceptance into the ' . $group_local->name . ' ' . $group_local->title . ' by ' . $user->title . '.';
				$db->addToLogs($event, $user->username, $username);
				return $event;
				}
			else
				{
				// The information the user submited is incorrect
				return false;
				}
		}
		
	function editInfo( $groupid, $name, $description, $game, $image, $parentcorps, $parentdivision )
		{
			global $db, $form, $notification, $config, $user, $games;
			
			$group_local = new Group;
			$group_local->setInfo($groupid);
			 /* Username error checking */
			$field = "name";
			 if( !$name || strlen($name) == 0 )
				{$form->setError($field, " * No Name has been entered");}
			
			/* Game Error Checking */
			$field = "game";
			if( $games->validateId($game) )
				{$form->setError($field, " * That is not a valid Game");}
			
			// Security //
			$field = "security";
			 if( !$group_local->isAdmin( $groupid, $user->username ) )
				{$form->setError($field, " * You must be an administrator of that group to edit it");}
				
				
			if( $form->num_errors == 0 )
				{
				// The information the user submited is correct
				$db->editInfo( $groupid, $name, $description, $game, $image, $parentcorps, $parentdivision );
				$db->setLoginInfo($user->username, time(), $_SERVER['REMOTE_ADDR']);
				$event = $user->title . ' has just modified the basic information for the ' . $group_local->name . ' ' . $group_local->title . '.';
				$db->addToLogs($event, $user->username, $username);
				return $event;
				}
			else
				{
				// The information the user submited is incorrect
				return false;
				}
		}
		
		
	function editMember( $groupid, $username, $userid, $title )
		{
			global $db, $form, $notification, $config, $user, $games;
			$group_local = new Group;
			$group_local->setInfo($groupid);
			
			 /* Username error checking */
			$field = "username";
			 if( !$username || strlen($username) == 0 )
				{$form->setError($field, " * No Username Selected");}
				
			$field = "userid";
				 if( !$userid || strlen($userid) == 0 )
				{$form->setError($field, " * No UsernId Selected");}
				
			 /* Username error checking */
			$field = "title";
			 if( !$title || strlen($title) == 0 )
				{$form->setError($field, " * No Group Title Entered");}
			

			// Security //
			$field = "security";
			 if( !$group_local->isAdmin( $groupid, $user->username ) )
				{$form->setError($field, " * You must be an administrator of that group to modify it.");}
				
				
			if( $form->num_errors == 0 )
				{
				// The information the user submited is correct
				$db->editMember( $groupid, $userid, $title );
				$db->setLoginInfo($user->username, time(), $_SERVER['REMOTE_ADDR']);
				$event = $user->title . ' has modified the information for ' . $username . ', a member of the ' . $group_local->name . ' ' . $group_local->title . '.';
				$db->addToLogs($event, $user->username, $username);
				return $event;
				}
			else
				{
				// The information the user submited is incorrect
				return false;
				}
		}
		
	function removeMember( $groupid, $username, $userid )
		{
			global $db, $form, $notification, $config, $user, $games;
			$group_local = new Group;
			$group_local->setInfo($groupid);
			
			 /* Username error checking */
			$field = "username";
			 if( !$username || strlen($username) == 0 )
				{$form->setError($field, " * No Username Selected");}
				
			$field = "userid";
				 if( !$userid || strlen($userid) == 0 )
				{$form->setError($field, " * No UsernId Selected");}
			
			// Security //
			$field = "security";
			 if( !$group_local->isAdmin( $groupid, $user->username ) )
				{$form->setError($field, " * You must be an administrator of that group to modify it.");}
				
				
			if( $form->num_errors == 0 )
				{
				// The information the user submited is correct
				$db->removeGroupMember( $userid );
				$db->setLoginInfo($user->username, time(), $_SERVER['REMOTE_ADDR']);
				$event = $user->title . ' removed ' . $username . ' from the ' . $group_local->name . ' ' . $group_local->title . '.';
				$db->addToLogs($event, $user->username, $username);
				return $event;
				}
			else
				{
				// The information the user submited is incorrect
				return false;
				}
		}

	function editLeaders( $groupid, $leader1, $leader2 )
		{
			global $db, $form, $notification, $config, $user, $games;
			$group_local = new Group;
			$group_local->setInfo($groupid);
			
			 /* Username error checking */
			$field = "security";
			 if( !$groupid || strlen($groupid) <= 0 )
				{$form->setError($field, " * No Group Selected");}
			
			// Security //
			$field = "security";
			 if( !$group_local->isAdmin( $groupid, $user->username ) )
				{$form->setError($field, " * You must be an administrator of that group to modify it.");}
			
			 if( $leader1 == $leader2 )
				{$form->setError($field, " * The Leader 1 and Leader 2 must be different.");}
				
			if( $form->num_errors == 0 )
				{
				// The information the user submited is correct
				
				// REMOVE CURRENT LEADERS //
				$sql = 'select id from group_users where gid = ' . $groupid . ' and username = "' . $leader1 . '" limit 1;';
				$result = mysql_query($sql);
				$object = mysql_fetch_object($result);
				$db->removeGroupMember( $object->id );
				
				$sql = 'select id from group_users where gid = ' . $groupid . ' and username = "' . $leader2 . '" limit 1;';
				$result = mysql_query($sql);
				$object = mysql_fetch_object($result);
				$db->removeGroupMember( $object->id );
				
				// ADD NEW LEADERS AS LONG AS THEY ARNT ALREADY THERE // <- extra security
				if( !$group_local->isMember( $groupid, $leader1 ) )
					$db->addGroupMember( $leader1, $groupid, 'Leader' );
				if( !$group_local->isMember( $groupid, $leader2 ) )
					$db->addGroupMember( $leader2, $groupid, 'Leader' );
				
				// UPDATE GROUP BASIC INFO TO ACCOUNT FOR THE NEW LEADERS //
				$db->updateLeaders( $groupid, $leader1, $leader2 );
				
				$db->setLoginInfo($user->username, time(), $_SERVER['REMOTE_ADDR']);
				$event = $user->title . ' removed ' . $username . ' from the ' . $group_local->name . ' ' . $group_local->title . '.';
				$db->addToLogs($event, $user->username, $username);
				return $event;
				}
			else
				{
				// The information the user submited is incorrect
				return false;
				}
		}

};

$group_validation = new Group_Validation;
?>
