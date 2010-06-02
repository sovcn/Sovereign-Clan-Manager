<?php
if($_SESSION['security_login'])
	{
		echo('You are not logged in.  You should not be trying to access this page.');
		unset($_SESSION['security_login']);
	}
if($_SESSION['security_disabled'])
	{
		echo('You have been disabled. You should not be trying to access this page.');
		$user->logout();
		unset($_SESSION['security_disabled']);
	}
if($_SESSION['security_permission'])
	{
		echo('You do not have permission to access this page. You should not be trying to access this page.');
		unset($_SESSION['security_permission']);
	}
if($_SESSION['security_commander'])
	{
		echo('You are not the commander or cocommander for that division.');
		unset($_SESSION['security_commander']);
	}


?>
