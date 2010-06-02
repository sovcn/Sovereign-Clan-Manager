<?php
class Security
{
	var $level;  // The current users level of security
	
	function Security()
	{
		global $db, $user, $form;
		$this->level = $user->rank_num;
	}
	
	function lockdown( $start, $end, $minrank, $message )
	{
		
	}
	
	function checkLockdown()
	{
		$sql = 'select * from lockdown;';
		$result = @mysql_query($sql) or die("Mysql Error");
		if( mysql_num_rows($result) > 0 )
			return true;
		else
			return false;	
	}
		
	function runSecurity($action_level, $username, $test1, $test2, $test3)
	{
		global $db, $user, $form;
		if( $this->isLoggedIn() && $test1 == 1)
		{
			$_SESSION['security_login'] = true;
		}
		else
		{
			$_SESSION['security_login'] = false;
		}
		if( $this->isDisabled($username) && $test2 == 1 )
		{
			$_SESSION['security_disabled'] = true;
		}
		else
		{
			$_SESSION['security_disabled'] = false;
		}
		if( !$this->hasPermission($action_level) && $test3 == 1 )
		{
			$_SESSION['security_permission'] = true;
		}
		else
		{
			$_SESSION['security_permission'] = false;
		}
		if( $_SESSION['security_login'] || $_SESSION['security_disabled'] || $_SESSION['security_permission'] )
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	/*
	function runGroupSecurity($action_level, $username, $groupid, $test1, $test2, $test3, $test4, $test5, $test6)
	{
		global $db, $user, $form;
		if( $this->isLoggedIn() && $test1 == 1)
		{
			$_SESSION['security_login'] = true;
		}
		else
		{
			$_SESSION['security_login'] = false;
		}
		if( $this->isDisabled($username) && $test2 == 1 )
		{
			$_SESSION['security_disabled'] = true;
		}
		else
		{
			$_SESSION['security_disabled'] = false;
		}
		if( !$this->hasPermission($action_level) && $test3 == 1 )
		{
			$_SESSION['security_permission'] = true;
		}
		else
		{
			$_SESSION['security_permission'] = false;
		}
		if( $_SESSION['security_login'] || $_SESSION['security_disabled'] || $_SESSION['security_permission'] )
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	*/
	function isLoggedIn()
	{
		global $db, $user, $form;
		if( !$user->logged_in )
			{return true;}
		else
			{return false;}
	}
		
	function isDisabled($username)
	{
		global $db, $user, $form;
		if( $db->isDisabled($username) )
			{return true;}
		else
			{return false;}
	}
	
	function hasPermission( $action_level )
	{
		global $db, $user, $form;
		if( $this->level >= $action_level )
			{return true;}
		else
			{return false;}
	}
	
	function isGroupLeader( $username, $id )
	{
	$sql = 'select * from groups where (leader1 = "' . $username . '" || leader2 = "' . $username . '") and id = ' . $id . ' limit 1;';
	$result = mysql_query($sql);
	if( mysql_num_rows($result) == 1 )
		return true;
	else
		return false;
	}
	
	function isGroupAdminCorps( $username, $id )
	{
	$sql = 'select * from groups where (leader1 = "' . $username . '" || leader2 = "' . $username . '") and id = ' . $id . ';';
	$result = mysql_query($sql);
	if( mysql_num_rows($result) > 0 )
		return true;
	else
		return false;
	}
	
	function isGroupAdminDivision( $username, $id )
	{
	$sql = 'select * from groups where (leader1 = "' . $username . '" || leader2 = "' . $username . '") and id = ' . $id . ' limit 1;';
	$result = mysql_query($sql);
	$array = mysql_fetch_array($result);
	$corpsid = $array['parentid'];
	if( $this->isGroupLeader( $username, $corpsid ) || $this->isGroupLeader( $username, $id ) )
		return true;
	else
		return false;
	
	}
	
	function isGroupAdminSquadron( $username, $id )
	{
	$sql = 'select * from groups where leader1 = "' . $username . '" or leader2 = "' . $username . '" and id = ' . $id . ' limit 1;';
	$result = mysql_query($sql);
	$array = mysql_fetch_array($result);
	$divisionid = $array['parentid'];
	if( $this->isGroupAdminDivision( $username, $divisionid ) || $this->isGroupLeader( $username, $id ) )
		return true;
	else
		return false;
	}


};

?>
