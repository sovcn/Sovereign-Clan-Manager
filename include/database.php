<?php
include('constants.php');
class Database
{

var $connection;

function connect()  // Connects to the database
{
	$this->connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die(mysql_error());
	mysql_select_db(DB_NAME, $this->connection) or die(mysql_error());
}

function updateUserId( $username, $userid )
{
	$sql = 'UPDATE `users` SET `userid` = \'' . $userid . '\' WHERE CONVERT(`users`.`username` USING utf8) = \'' . $username . '\' LIMIT 1;';
	$result = mysql_query($sql);
}

function validateUserId($username, $userid)
{
	$sql = 'select userid from users where username = "' . $username . '" limit 1;';
	$result = mysql_query($sql);
	$array = mysql_fetch_array($result);
	if( mysql_num_rows($result) == 1 && $userid == $array['userid'] )
	 return true;
	else
	 return false;
}

function rankFromUsername($username)  // Gets the name of the rank asociated with a username
{
$sql = 'select name
		from ranks, users
		where ranks.id = users.rank
		and username = "' . $username . '";';
$result = mysql_query($sql);
$array = mysql_fetch_array($result);
$rank = $array['name'];
return $rank;
}

function rankFromRank_Num($rank_num)
{
$sql = 'select name
		from ranks
		where ranks.id = ' . $rank_num . '
		LIMIT 1;';
$result = mysql_query($sql);
$array = mysql_fetch_array($result);
$rank = $array['name'];
return $rank;
}

function rank_numFromUsername($username)  // Gets the number of the rank asociated with a username
{
$sql = 'select ranks.id
		from ranks, users
		where ranks.id = users.rank
		and username = "' . $username . '";';
$result = mysql_query($sql);
$array = mysql_fetch_array($result);
$rank = $array['id'];
return $rank;
}

function displaynameFromUsername($username)  // Gets the display name from the database for the specific username
{
$sql = 'select displayname
		from users
		where username = "' . $username . '";';
$result = mysql_query($sql);
$array = mysql_fetch_array($result);
$displayname = $array['displayname'];
return $displayname;
}

function titleFromUsername($username)
{
$rank = $this->rankFromUsername($username);
$name = $this->displaynameFromUsername($username);
return '' . $rank . ' ' . $name . '';
}

function catIDFromUsername($username)
{
	$sql = 'select cid from users, ranks where users.rank = ranks.id and username="' . $username . '" limit 1;';
	$result = mysql_query($sql);
	$array = mysql_fetch_array($result);
	return $array['cid'];
}

function addMember($username, $displayname, $password, $email, $aim, $game) // Adds the username, displayname, password, email to the database
{
if( $game == 'cs' )
	$cs = 1;
else
	$cs = 0;
if( $game == 'css' )
	$css = 1;
else
	$css = 0;
if( $game == 'bw' )
	$bw = 1;
else
	$bw = 0;
if( $game == 'd2' )
	$d2 = 1;
else
	$d2 = 0;
if( $game == 'wow' )
	$wow = 1;
else
	$wow = 0;
if( $game == 'h3' )
	$h3 = 1;
else
	$h3 = 0;
if( $game == 'gw' )
	$gw = 1;
else
	$gw = 0;
if( $game == 'war3' )
	$war3 = 1;
else
	$war3 = 0;
$sql = 'INSERT INTO `users` 
		(`username`, `displayname`, `password`, `email`, `rank`, `disabled`, `aim`, `ip`, `recruiter`, `joined`, `recruits`, `last_login`, `status`, `cs`, `css`, `bw`, `d2`, `wow`, `h3`, `gw`, `war3`, `maingame`)
		 VALUES 
		 	(\'' . $username . '\', \'' . $displayname . '\', \'' . $password . '\', \'' . $email . '\', \'1\', \'0\', \'' . $aim . '\', \'0\', \'NULL\', \'' . time() . '\', \'0\', \'' . time() . '\', \'0\', \'' . $cs . '\', \'' . $css . '\', \'' . $bw . '\', \'' . $d2 . '\', \'' . $wow . '\', \'' . $h3 . '\', \'' . $gw . '\', \'' . $war3 . '\', \'' . $game . '\');';
		$result = @mysql_query($sql);

}

function disableMember($username)  // Sets a username as disabled
{
$sql = 'UPDATE `users` 
		SET `disabled` = 1 
		WHERE CONVERT(`users`.`username` USING utf8) = \'' . $username . '\' 
		LIMIT 1;';
$result = mysql_query($sql);
}

function enableMember($username) // Sets a username as enabled
{
$sql = 'UPDATE `users` 
		SET `disabled` = 0
		WHERE CONVERT(`users`.`username` USING utf8) = \'' . $username . '\' 
		LIMIT 1;';
$result = mysql_query($sql);
}

function setRank($username, $rank) // Modifies the rank of the username to the rank of the argument
{
	$sql = 'UPDATE users
			SET last_demotion = ' . time() . '
			WHERE username = "' . $username . '"
			LIMIT 1;';
	$result = mysql_query($sql);		
	
	$sql = 'UPDATE users
			SET rank = ' . $rank . '
			WHERE username = "' . $username . '"
			LIMIT 1;';
	$result = mysql_query($sql);
}

function getPassword($username)  // Gets the password for a username
{
	$sql = 'select password
			from users
			where username = "' . $username . '";';
	$result = mysql_query($sql, $this->connection);
	$array = mysql_fetch_array($result);
	$password = $array['password'];
	return $password;
}

function getInfo($username)  // Gets the account information asociated with a username
{
$sql = 'select *
		from users
		where username = "' . $username . '";';
$result = mysql_query($sql);
$array = mysql_fetch_array($result);
return $array;
}

function getMessages($username)
{
$sql = 'select *
		from messages
		where username = "' . $username . '";';
$result = mysql_query($sql);
$array = mysql_fetch_array($result);
return $array;

}

function getMessageInfo($id)
{
$sql = 'select *
		from messages
		where id = ' . $id . ';';
$result = mysql_query($sql);
$array = mysql_fetch_array($result);
return $array;
}

function getNewsInfo($id)
{
	$sql = 'select *
			from news
			where id = ' . $id . ';';
	$result = mysql_query($sql);
	$array = mysql_fetch_array($result);
	return $array;

}
function setLoginInfo($username, $time, $ip)
{
$sql = 'UPDATE `users` SET `ip` = \'' . $ip . '\', `last_login` = \'' . $time . '\' WHERE CONVERT(`users`.`username` USING utf8) = \'' . $username . '\' LIMIT 1;';
$result = mysql_query($sql);

$sql = 'select *
		from online_users
		where username = "' . $username . '"
		limit 1;';
$result = mysql_query($sql);
$array = mysql_fetch_array($result);
if($array['username'])
{
$sql = 'UPDATE `online_users` 
		SET `ip` = \'' . $ip . '\', `timestamp` = \'' . $time . '\' 
		WHERE username = "' . $username . '"
		LIMIT 1;';
$result = mysql_query($sql);
}
else
{
$sql = 'INSERT INTO `online_users` 
			(`username`, `ip`, `timestamp`) 
		VALUES 
			(\'' . $username . '\', \'' . $ip . '\', \'' . $time . '\');';
$result = mysql_query($sql);
}
}

function setLogoutInfo($username)
{
$sql = 'DELETE FROM `online_users` WHERE username = "' . $username . '" LIMIT 1;;';
$result = mysql_query($sql);

}

function getRankName($username) //  Gets the name of the rank asociated with a username.
{
$sql = 'select ranks.name
		from ranks, users
		where users.rank = ranks.id
		and users.username = "' . $username . '";';
$result = mysql_query($sql);
$array = mysql_fetch_array($result);
$rankName = $array['name'];
return $rankName;
}

function addToLogs($event, $username1, $username2)  // Adds an event to the website logs
{
$sql = 'INSERT INTO `logs` 
		(`ID`, `Event`, `datetime`, `username1`, `username2`) 
		VALUES 
		(NULL, \'' . $event . '\', NOW(), \'' . $username1 . '\', \'' . $username2 . '\');';
$result = mysql_query($sql);
}

function getLogsforMember($username, $limit = 0)
{
$sql = 'select * 
		from logs 
		where username1 = "' . $username . '" || username2 = "' . $username . '"
		order by id desc
		limit 0, ' . $limit . ';';
$result = mysql_query($sql);
return $result;
}

function usernameTaken($username)  // Finds out if the specified username is already in the database
{
	$sql = 'select username
			from users
			where username = "' . $username . '";';
	$result = mysql_query($sql);
	$array = mysql_fetch_array($result);
	$dbUsername = $array['username'];
	if( strtolower($dbUsername) == strtolower($username) ) // Takes the lowercase of both strings and compares them
	{return true;}
	else
	{return false;}
}

function emailTaken($email)  // Finds out if the specified email address is already in the database
{
	$sql = 'select email
			from users
			where email = "' . $email . '";';
	$result = mysql_query($sql);
	$array = mysql_fetch_array($result);
	$dbEmail = $array['email'];
	if( strtolower($dbEmail) == strtolower($email) )
	{return true;}
	else
	{return false;}
}

function isDisabled($username)  // Finds out if the user is didsabled
{
	$sql = 'select disabled
			from users
			where username = "' . $username . '";';
	$result = mysql_query($sql);
	$array = mysql_fetch_array($result);
	$disabled = $array['disabled'];
	if( $disabled == 1 )
	{return true;}
	else
	{return false;}
}

function setRecruiter($recruited, $recruiter)
{
$sql = 'UPDATE users
		SET recruiter = "' . $recruiter . '"
		WHERE username = "' . $recruited . '"
		LIMIT 1;';
$result = mysql_query($sql);
}

function getRecruits($username)
{
$sql = 'select username
		from users
		where recruiter = "' . $username . '"
		and disabled = 0;';
$result = mysql_query($sql);
return $result;

}

function updateProfile($username, $displayname, $aim, $email, $location, $quote, $game, $cs, $css, $bw, $d2, $wow, $h3, $gw, $eaw, $war3)
{
$sql = 'UPDATE users
		SET aim = "' . $aim . '", email = "' . $email . '", location = "' . $location . '", quote = "' . $quote . '", maingame = "' . $game . '", cs = "' . $cs . '", css = "' . $css . '", bw = "' . $bw . '", d2 = "' . $d2 . '", wow = "' . $wow . '", h3 = "' . $h3 . '", gw = "' . $gw . '", eaw = "' . $eaw . '", war3 = "' . $war3 . '"
		WHERE username = "' . $username . '"
		LIMIT 1;';
$result = mysql_query($sql);

}

function updatePassword($username, $password)
{
$sql = 'UPDATE users
		SET password = "' . $password . '"
		WHERE username = "' . $username . '"
		LIMIT 1;';
$result = mysql_query($sql);
}

function insertNews($username, $title, $description, $news, $date)
{
$sql = 'INSERT INTO `news` 
			(id, `title`, `description`, `text`, `poster`, `date`) 
		VALUES 
			(NULL, \'' . $title . '\', \'' . $description . '\', \'' . $news . '\', \'' . $username . '\', \'' . $date . '\');';
$result = mysql_query($sql);

}


function getActiveUsers()
{
	$sql = 'select ranks.name, users.displayname, username
			from users, ranks
			where users.rank = ranks.id
			and users.status = 1
			order by users.rank desc;';
	$result = mysql_query($sql);
	return $result;

}

function removeInactiveUsers($seconds)
{
$timeout = time() - $seconds;
$sql = 'update users
		set status = 0
		where last_login < ' . $timeout . ';';
$result = @mysql_query($sql);
return $timeout;
}

function unreadMessages($username)
{
$sql = 'SELECT * FROM `messages` where messages.to = "' . $username . '" and `read` = 0;';
$result = mysql_query($sql);
$num = mysql_num_rows($result);
return $num;

}

function messages($username)
{
$sql = 'SELECT * FROM `messages` where messages.to = "' . $username . '";';
$result = mysql_query($sql);
$num = mysql_num_rows($result);
return $num;

}

function send($title, $to, $from, $time, $text)
{

$sql = 'INSERT INTO `messages` 
			(`id`, `title`, `text`, `from`, `to`, `date`, `read`) 
		VALUES 
			(NULL, \'' . $title . '\', \'' . $text . '\', \'' . $from . '\', \'' . $to . '\', \'' . $time . '\', \'0\');';
$result = mysql_query($sql);

}
function getCurrentTemplateInfo()
{
	$sql = 'select *
			from templates
			where current = 1
			limit 1;';
	$result = mysql_query($sql);
	return $result;
}
function getTemplateInfo($id)
{
	$sql = 'select *
			from templates
			where id = ' . $id . ';';
	$result = mysql_query($sql);
	return $result;
}

function changeTemplate($template, $current)
{
$sql = 'update templates
		set current = 0
		where id = ' . $current . '
		limit 1;';
$result = mysql_query($sql);

$sql = 'update templates
		set current = 1
		where id = ' . $template . '
		limit 1;';
$result = mysql_query($sql);


}


function messageIsTo($id, $username)
{
	$sql = 'select *
			from messages
			where messages.to = "' . $username . '"
			and id = ' . $id . '
			limit 1;';
	$result = mysql_query($sql);
	if( mysql_num_rows($result) == 1 )
		return true;
	else
		return false;

}

function deleteMessage($id)
{
$sql = 'DELETE FROM `messages` 
		WHERE `messages`.`id` = ' . $id . ' 
		LIMIT 1;';
$result = mysql_query($sql);

}


function selectPermissions()
{
	$sql = 'select *
			from permissions;';
	$result = mysql_query($sql);
	return $result;
}


function getDivisionInfo($id)
{
$sql = 'select *
		from divisions
		where id = ' . $id . ';';
$result = mysql_query($sql);
$array = mysql_fetch_array($result);
return $array;
}

function divisionNumMembers($id)
{
$sql = 'select id
		from divisions_users, users
		where did = ' . $id . '
		and divisions_users.username = users.username
		and disabled = 0;';
$result = mysql_query($sql);
$number = mysql_num_rows($result);
return $number;
}

function getDivisionUsersInfo($id, $username)
{
$sql = 'select ranks.id, users.username, users.displayname, ranks.name, divisions_users.joined, divisions_users.title
		from users, ranks, divisions_users
		where divisions_users.did = ' . $id . '
		and divisions_users.username = users.username
		and divisions_users.username = "' . $username . '"
		and users.rank = ranks.id
		and disabled = 0;';
$result = mysql_query($sql);
$array = mysql_fetch_array($result);
return $array;
}

function getDivisionEventsInfo($id)
{
$sql = 'select *
		from divisions_events
		where did = ' . $id . '
		order by id desc;';
$result = mysql_query($sql);
return $result;

}

function getCommanderInfo($id)
{
$sql = 'select users.username, users.displayname, ranks.name, ranks.id, divisions_users.joined, divisions_users.title
		from users, ranks, divisions, divisions_users
		where users.username = commander
		and users.rank = ranks.id
		and divisions_users.username = users.username
		and divisions.id = ' . $id . '
		and disabled = 0;';
$result = mysql_query($sql);
$array = mysql_fetch_array($result);
return $array;
}

function getCoCommanderInfo($id)
{
$sql = 'select users.username, users.displayname, ranks.name, ranks.id, divisions_users.joined, divisions_users.title
		from users, ranks, divisions, divisions_users
		where users.username = cocommander
		and users.rank = ranks.id
		and divisions_users.username = users.username
		and divisions.id = ' . $id . '
		and disabled = 0;';
$result = mysql_query($sql);
$array = mysql_fetch_array($result);
if(!$array['username'])
return false;
else
return $array;
}

function setNotification($username, $notification, $time)
{
$sql = 'INSERT INTO `sovereign_login`.`notifications` 
		(`id`, `username`, `text`, `time`, `done`) 
		VALUES 
		(NULL, \'' . $username . '\', \'' . $notification . '\', \'' . $time . '\', \'0\');';
$result = mysql_query($sql);
}


function getMaxDivisionId()
{
$sql = 'select id
		from divisions';
$result = mysql_query($sql);
while($row = mysql_fetch_array($result))
{
$id = $row['id'];
}
return $id;
}

function getActivityInfo()
{
$sql = 'select * from users;';
$result = mysql_query($sql);
return $result;

}

function isActive($username, $timeout)
{
$timestamp = time() - $timeout;
$sql = 'select *
		from online_users
		where username = "' . $username . '"
		limit 1;';
$result = mysql_query($sql);
$array = mysql_fetch_array($result);
if($array['timestamp'] >= $timestamp)
	return true;
else
	return false;

}

function postIa($username, $reason)
{
$sql = 'INSERT INTO `ia_request` 
			(`id`, `username`, `reason`, `time`) 
		VALUES 
			(NULL, \'' . $username . '\', \'' . $reason . '\', \'' . time() . '\');';
$result = mysql_query($sql);
}

function approveIa( $id )
{
$sql = 'select username
		from ia_request
		where id = ' . $id . '
		limit 1;';
$result = mysql_query($sql);
$array = mysql_fetch_array($result);
$username = $array['username'];

$sql = 'update users
		set ia = 1
		where username = "' . $username . '"
		limit 1;';
$query = mysql_query($sql);

$sql = 'DELETE FROM `ia_request` WHERE `ia_request`.`id` = ' . $id . ' LIMIT 1;';
$query = mysql_query($sql);

}

function isIa($username)
{
$sql = 'select *
		from users
		where ia = 1
		and username = "' . $username . '"
		limit 1;';
$result = mysql_query($sql);
$array = mysql_fetch_array($result);
if($array['ia'])
	return true;
else
	return false;

}

function removeIa($username)
{
$sql = 'update users
		set ia = 0
		where username = "' . $username . '"
		limit 1;';
$result = mysql_query($sql);

}

function getLastDemotion($username)
{
$sql = 'select last_demotion
		from users
		where username = "' . $username . '";';
$result = mysql_query($sql);
$array = mysql_fetch_array($result);
$time = $array['last_demotion'];
return $time;
}

function changeName($username, $newname)
{
$sql = 'update users
		set username = "' . $newname . '", displayname = "' . $newname . '"
		where username = "' . $username . '"
		limit 1;';
$query = mysql_query($sql);

}

function createCorps( $name, $description, $leader1, $leader2, $image )
	{
		$sql = 'INSERT INTO `groups` 
		(`id`, `corps`, `division`, `squad`, `name`, `description`, `created`, `leader1`, `leader2`, `gameid`, `image`, parentcorps, parentdivision) 
		VALUES 
		( NULL , 1, 0, 0, "' . $name . '", "' . $description . '", ' . time() . ', "' . $leader1 . '", "' . $leader2 . '", 0, "' . $image . '", 0, 0);';
	$result = mysql_query($sql);
	}

function createDivision( $name, $description, $leader1, $leader2, $gameid, $image, $parentcorps )
	{
		$sql = 'INSERT INTO `groups`'
        . ' (`id`, `corps`, `division`, `squad`, `name`, `description`, `created`, `leader1`, `leader2`, `gameid`, `image`, parentcorps, parentdivision) '
        . ' VALUES '
        . ' ( NULL , 0, 1, 0, "' . $name . '", "' . $description . '", ' . time() . ', "' . $leader1 . '", "' . $leader2 . '", \' ' . $gameid . ' \', "' . $image . '", ' . $parentcorps . ' , 0 );';
		$result = mysql_query($sql);
	}
	
function createSquadron( $name, $description, $leader1, $leader2, $gameid, $image, $parentcorps, $parentdivision )
	{
		$sql = 'INSERT INTO `groups`'
        . ' (`id`, `corps`, `division`, `squad`, `name`, `description`, `created`, `leader1`, `leader2`, `gameid`, `image`, parentcorps, parentdivision) '
        . ' VALUES '
        . ' ( NULL , 0, 0, 1, "' . $name . '", "' . $description . '", ' . time() . ', "' . $leader1 . '", "' . $leader2 . '", \' ' . $gameid . ' \', "' . $image . '", ' . $parentcorps . ' , ' . $parentdivision . ' );';
		$result = mysql_query($sql);
	}
	
function addGroupMember($username, $gid, $title)
{
	$sql = 'INSERT INTO `group_users` 
				(`id`, `username`, `gid`, `joined`, `title`) 
			VALUES 
				(NULL, \'' . $username . '\', \'' . $gid . '\', \'' . time() . '\', \'' . $title . '\');';
	$result = mysql_query($sql);
}

function divisionParentCorpsFromId($id)
{
$sql = 'select parentcorps from groups where id = ' . $id . ' limit 1;';
$result = mysql_query($sql);
$object = mysql_fetch_object($result);
return $object->id;
}

function application( $username, $reason, $comments, $groupid )
{
$sql = 'INSERT INTO `group_applications` 
				(`id`, `username`, `reason`, `comments`, `time`, `gid`) 
			VALUES 
				(NULL, \'' . $username . '\', \'' . $reason . '\', \'' . $comments . '\', \'' . time() . '\', \'' . $groupid . '\');';
$result = mysql_query($sql);

}

function deleteApplication($appid)
{
$sql = 'DELETE FROM `group_applications` WHERE `group_applications`.`id` = ' . $appid . ' LIMIT 1;';
$result = mysql_query($sql);
}

function editInfo( $groupid, $name, $description, $game, $image, $parentcorps, $parentdivision )
{
$sql = 'UPDATE `groups` SET `name` = \'' . $name . '\', `description` = \'' . $description . '\', `gameid` = \'' . $game . '\', `image` = \'' . $image . '\', `parentcorps` = \'' . $parentcorps . '\', `parentdivision` = \'' . $parentdivision . '\' WHERE `groups`.`id` = ' . $groupid . ' LIMIT 1;';
$result - mysql_query($sql);
}

function editMember( $groupid, $userid, $title )
{
$sql = 'UPDATE `group_users` SET `title` = \'' . $title . '\' WHERE `group_users`.`id` = ' . $userid . ' LIMIT 1;';
$result = mysql_query($sql);
}

function removeGroupMember( $userid )
{
$sql = 'DELETE FROM group_users WHERE id = ' . $userid . ' limit 1;';
$result = mysql_query($sql);
}

function updateLeaders( $groupid, $leader1, $leader2 )
{
$sql = 'UPDATE `groups` SET `leader1` = \'' . $leader1 . '\', `leader2` = \'' . $leader2 . '\' WHERE `groups`.`id` = ' . $groupid . ' LIMIT 1;';
$result = mysql_query($sql);
}

function modifyTemplate( $change, $username )
{
	$sql = 'UPDATE `users` SET `template_switch` = \'' . $change . '\' WHERE CONVERT(`users`.`username` USING utf8) = \'' . $username . '\' LIMIT 1;';
	$result = mysql_query($sql);
}

};

$db = new Database;
$db->connect();
?>
