<?php
// This is the user.php file which defines the user class.

require('database.php');
require('configuration.php');
require('permission.php');
require('form.php');
require('security.php');
require('functions.php');
require('ia.php');
require('statistics.php');
require('notifications.php');
require('games.php');
require('ftp.php');
require('groups.php');
require('information.php');
require('user_form.php');
require('filemanage.php');
require('tagbox.php');

class User
{
	var $username;  // The username of the person logged in.
	var $displayname;  // The display name of the user.
	var $userid;
	var $logged_in; // Variable which holds either true false depending on whether the user is logged in.
	var $rank; //  Holds the rank of the user.
	var $rank_num;
	var $email; // Holds the email address of the user.
	var $disabled; //
	var $aim; // 
	var $title;  //
	var $last_login;
	var $ip;
	var $dsl;
	var $status;
	
	var $maingame;
	var $cs;
	var $css;
	var $bw;
	var $d2;
	var $wow;
	var $h3;
	var $gw;
	var $war3;

	var $template_switch;


// >>>>>>>>>>>>>>>>>>>>>>  BEGIN MEMBER FUNCTIONS <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
 	
function User() // User Constructor.  Initializes all of the member data.
	{
		global $db, $config;
		$db->removeInactiveUsers($config->getSetting('active_timeout'));
		session_start();
		$this->logged_in = $this->checkLogin();  // Runs the check login function and puts the value into $logged_in
		
		if( !$this->logged_in )
			$this->username = 'Guest';
		else
		{
		$array = $db->getInfo($this->username);  // Gets the information on the logged in username
		//>>>   Assigns the information of the logged in user to the member data  <<<<<
		$this->displayname = $array['displayname'];
		$this->rank = $db->getRankName($this->username);
		$this->rank_num = $array['rank'];
		$this->rank_name = $db->getRankName($this->username);
		$this->email = $array['email'];
		$this->disabled = $array['disabled'];
		$this->aim = $array['aim'];
		$this->title = $db->getRankName($this->username) . ' ' . $array['displayname'];
		$this->recruits = $array['recruits'];
		$this->last_login = $array['last_login'];
		$this->dsl = daysSinceTimestamp($this->last_login);
		$this->status = $array['status'];
		$this->recruits = $array['recruits'];
		
		$this->maingame = $array['maingame'];
		$this->cs = $array['cs'];
		$this->css = $array['css'];
		$this->bw = $array['bw'];
		$this->d2 = $array['d2'];
		$this->wow = $array['wow'];
		$this->h3 = $array['h3'];
		$this->gw = $array['gw'];
		$this->war3 = $array['war3'];
		$this->checkDSL();
		$this->checkAutoPromotion();
		$this->checkActive($config->getSetting('active_timeout'));
		
		$this->template_switch = $array['template_switch'];
		}

	}									

function checkLogin()  // Checks if the user has logged in and if they have....
	{
		global $db;
		if( isset($_COOKIE['username']) && isset($_COOKIE['userid']) && $db->validateUserId($_COOKIE['username'], $_COOKIE['userid']) )
		{
			$this->username = $_SESSION['username'] = $_COOKIE['username'];
			$this->userid = $_SESSION['userid'] = $_COOKIE['userid'];
			$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
			return true; // They have logged in.
		}
		else if( isset($_SESSION['username']) && isset($_SESSION['userid']) && $db->validateUserId($_SESSION['username'], $_SESSION['userid']) )
		{
			$this->username = $_SESSION['username'];  // Assigns the session value for username to the $username member data
			$this->userid = $_SESSION['userid'];
			$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
			return true; // They have logged in.
		}
		else
		{
			return false; // They have not logged in.
		}
	}
	

// * Below Function Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
function generateRandStr($length)
	{
      $randstr = "";
      for($i=0; $i<$length; $i++){
         $randnum = mt_rand(0,61);
         if($randnum < 10){
            $randstr .= chr($randnum+48);
         }else if($randnum < 36){
            $randstr .= chr($randnum+55);
         }else{
            $randstr .= chr($randnum+61);
         }
      }
      return $randstr;
   }
	
	//  This function is used to log a user in.  If the validation and errorchecking creates no errors, then it sets
	//  the $_SESSION['username'] variable to the value of the $username parameter.  This is used in the check_login
	//  function to find out of a user has successfully logged in.
	
	
function modifyTemplate($min, $max, $change)
	{
		if( $this->logged_in && $change <= $max && $change >= $min )
			{
				global $db;
				$db->modifyTemplate( $change, $this->username );
			}
	}
	
// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>     ACTIVITY FUNCTIONS  <<<<<<<<<<<<<<<<<<<<<<<<<<<//	

function checkActive( $timeout )
{
	$sqlTime = time() - $timeout;
	$sql = 'DELETE FROM `online_users` WHERE timestamp <= ' . $sqlTime . '';
	@mysql_query($sql) or die("Mysql Error");	
}

function checkDSL()
{
	
	global $config, $notification, $db;
	
	/*  This prevents the scripts from disabling everyone just because the config value can't be found */
	if( $config->getSetting('days_demotion') )
		$days_demotion = $config->getSetting('days_demotion');
	else
		$days_demotion = 100; 
		
	if( $config->getSetting('days_disable') )
		$days_disable = $config->getSetting('days_disable');
	else
		$days_disable = 100;
	// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>/
	
	$seconds_demotion = $days_demotion * 24 * 60 * 60;
	$seconds_disable = $days_disable * 24 * 60 * 60;
	
		$time = time() - 86400;
		$query = @mysql_query('select * from users where disabled = 0 and ia = 0 and last_demotion <= ' . $time . ';');
		while( $array = mysql_fetch_array($query) )
		{
			if( $array['last_login'] >= time() - $seconds_demotion && $array['last_login'] >= time() - $seconds_disable )
			{
				// User has logged in often enough to avoid a demotion or disablement	
			}
			else if( $array['last_login'] <= time() - $seconds_demotion && $array['last_login'] >= time() - $seconds_disable )
			{
				// The user has not logged in often enough.  They will be demoted.
				$newRank = $array['rank'] - 1;
				$sql = 'update users set rank = ' . $newRank . ', last_demotion = ' . time() . ' where username = "' . $array['username'] . '" limit 1;';
				$result = @mysql_query($sql) or die("error with mysql");
				$event = $db->titleFromUsername($array['username']) . ' was demoted for having ' .  $days_demotion . ' days since login.';
				$db->addToLogs($event, 0, $array['username']);
				$notification->setNot($array['username'], $this->username, 'You have been Disabled', $event);
			}
			else if( $array['last_login'] <= time() - $seconds_demotion && $array['last_login'] <= time() - $seconds_disable )
			{
				// The user has not logged in often enough.  They will be disabled.
				$sql = 'UPDATE `users` SET `disabled` = \'1\' WHERE CONVERT(`users`.`username` USING utf8) = \'' . $array['username'] . '\' LIMIT 1;';
				$result = @mysql_query($sql) or die("error with mysql");
				$event = $db->titleFromUsername($array['username']) . ' was disabled for having ' .  $days_disable . ' days since login.';
				$db->addToLogs($event, 0, $array['username']);
				$notification->setNot($array['username'], $this->username, 'You have been Disabled', $event);
			}
			else
			{
				// There was an error made somewhere and against all logic... the user does not fit any of the above categories..
			}
	}
		
	
/*global $db, $config, $notification;
$time = time() - 86400;
$query = mysql_query('select * from users where disabled = 0 and ia = 0 and last_demotion <= ' . $time . ';');
while( $array = mysql_fetch_array($query) )
{
	if( $config->getSetting('days_demotion') )
		$days_demotion = $config->getSetting('days_demotion');
	else
		$days_demotion = 100;
		
	if( $config->getSetting('days_disable') )
		$days_disable = $config->getSetting('days_disable');
	else
		$days_disable = 100;
	
	$rank = $array['rank'] - 1;
	if(daysSinceTimestamp($array['last_login']) >= $days_demotion )
	{
		$sql = 'update users
				set rank = ' . $rank . ', last_demotion = ' . time() . '
				where username = "' . $array['username'] . '"
				and disabled = 0
				and ia = 0
				and last_demotion <= ' . $time . ';';
		$result = mysql_query($sql);
		$event = $db->titleFromUsername($array['username']) . ' was demoted for having ' . $config->getSetting('days_demotion') . ' DSL.';
		$db->addToLogs($event, 0, $array['username']);
		$notification->setNot($array['username'], $this->username, 'You have been Demoted', $event);
	}
	if( daysSinceTimestamp($array['last_login']) >= $days_disable )
	{
		$sql = 'update users
				set disabled = 1
				where username = "' . $array['username'] . '";';
		$result = mysql_query($sql);
		$event = $db->titleFromUsername($array['username']) . ' was disabled for having ' . $config->getSetting('days_disable') . ' DSL.';
		$db->addToLogs($event, 0, $array['username']);
		$notification->setNot($array['username'], $this->username, 'You have been Disabled', $event);
	}
}
*/
}

function checkAutoPromotion()
{
global $db, $config, $notification;
	$sql = 'select username, joined
			from users
			where rank = 1;';
	$result = mysql_query('select username, joined
			from users
			where rank = 1;');
	while( $row = @mysql_fetch_array($result) )
	{
		if( daysSinceTimestamp($row['joined']) >= 2 )
		{
		$sql = 'update users
				set rank = 2
				where username = "' . $row['username'] . '"
				limit 1;';
		$result = mysql_query($sql);
		$event = $row['username'] . ' was promoted for having 2 days in the clan.';
		$notification->setNot($array['username'], 0, 'You have been Promoted', $event);
		}
	}

	$sql = 'select username, joined
			from users
			where rank = 2;';
	$result = mysql_query($sql);
	while( $row = @mysql_fetch_array($result) )
	{
		if( daysSinceTimestamp($row['joined']) >= 5 )
		{
		$sql = 'update users
				set rank = 3
				where username = "' . $row['username'] . '"
				limit 1;';
		$result = mysql_query($sql);
		$event = $row['username'] . ' was promoted for having 5 days in the clan.';
		$notification->setNot($array['username'], 0, 'You have been Promoted', $event);
		}
	}
	
	$sql = 'select username, joined
			from users
			where rank = 3;';
	$result = mysql_query($sql);
	while( $row = @mysql_fetch_array($result) )
	{
		if( daysSinceTimestamp($row['joined']) >= 10 )
		{
		$sql = 'update users
				set rank = 4
				where username = "' . $row['username'] . '"
				limit 1;';
		$result = mysql_query($sql);
		$event = $row['username'] . ' was promoted for having 10 days in the clan.';
		$notification->setNot($array['username'], 0, 'You have been Promoted', $event);
		}
	}
	$sql = 'select username, joined
			from users
			where rank = 4;';
	$result = mysql_query($sql);
	while( $row = @mysql_fetch_array($result) )
	{
		if( daysSinceTimestamp($row['joined']) >= 15 )
		{
		$sql = 'update users
				set rank = 5
				where username = "' . $row['username'] . '"
				limit 1;';
		$result = mysql_query($sql);
		$event = $row['username'] . ' was promoted for having 15 days in the clan.';
		$notification->setNot($array['username'], 0, 'You have been Promoted', $event);
		}
	}


}
	
	
// >>>>>>>>>>>>>>>>>>>>>>>>  END ACTIVITY FUNCTIONS  <<<<<<<<<<<<<<<<<<<<//	

function login($username, $password, $remember)
	{
	global $db, $form, $notification;

	 /* Username error checking */
	$field = "user";
	 if( !$username || strlen($username) == 0 )
	 	{$form->setError($field, " * Username not entered");}
		
	 /* Password error checking */
	 $field = "pass";
	 if( !$password || strlen($password) == 0 )
	 	{$form->setError($field, " * Password not entered");}
	// Disablement Error Checking
	$field = "disabled";
	 if( $db->isDisabled($username) )
		 {$form->setError($field, " * You have been Disabled! * ");}
		
	// Authenticate the password
		$passwordEncrypt = md5($password);  // Encrypt the password with the md5 function
		$dbpassword = $db->getPassword($username);  // Compare the two encrypted passwords
		
	if( $dbpassword == $passwordEncrypt && $form->num_errors == 0 )
		{
			// The information the user submited is correct
			$_SESSION['username'] = $username;
			$_SESSION['userid'] = md5( $this->generateRandStr( 16 ) );
			//$db->updateUserId( $_SESSION['username'], $_SESSION['userid'] );
			$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
			$db->updateUserId( $_SESSION['username'], $_SESSION['userid'] );
			
			if( $remember )
			{
				$expire = time() + (60 * 60 * 60 * 24 * 30);
				setcookie('username', $_SESSION['username'], $expire);
				setcookie('userid', $_SESSION['userid'], $expire);
			}
		}
	else
		{
		// The information the user submited is incorrect
		$field = "info";
		$form->setError($field, " * The login information you provided was incorrect * ");
		return false;
		}
	}
	

//>>>>>>>>>>>>   ADD MEMBER  <<<<<<<<<<<<<<<<<<<<<</
	
function addMember($username, $displayname, $email, $aim, $game)
	{
	global $db, $form, $notification;
	/* Username error checking */
	$field = "username";
			// Makes sure the username field isnt empty
	 if( !$username || strlen($username) == 0 )
	 	{$form->setError($field, " * Username not entered");}
			// Makes sure that the username isnt already taken
	 if( $db->usernameTaken($username) )
	  	{$form->setError($field, " * That username is already taken");}
		
	if( strlen($username) > 15 )
		{$form->setError($field, " * Username must be 15 or less characters long.");}
	
	/* Display Name error checking */
	$field = "displayname";
	 if( !$displayname || strlen($displayname) == 0 )
	 	{$form->setError($field, " * Display Name not entered");}
	
	/* Email error checking */
	$field = "email";
	 if( !$email || strlen($email) == 0 )
	 	{$form->setError($field, " * Email not entered");}
	
	 if( $db->emailTaken($email) )
	 	{$form->setError($field, " * Email is already taken");}
		
		
		
	if( $form->num_errors == 0 )  // If there were no errors with the submitted information
	{
		$password = generatePassword(6);
		$insertPassword = md5($password);
		$db->addMember($username, $displayname, $insertPassword, $email, $aim, $game); // Add user to the database
		$db->setRecruiter($username, $this->username); // Set their recruiter as the logged in user
		$event = 'A new member with the username: ' . $username . ' was added to the website by ' . $this->title . '.';
		$db->addToLogs($event, $this->username, $username);
		$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
		$event = 'Hello ' . $db->titleFromUsername($username) . ' and welcome to clan Divine Intervention.  Thank you for choosing our clan.  Now that you have logged in for the first time, we would like you to do a few things.  First, we would really apreciate it if you would register on our forums and post an introduction.  Second, you should read the rules and handbook(if you have been given one.)   If you have any questions, please dont hesitate to contact someone in the clan.   - Thanks, Di.Sovereign';
		$notification->setNot($username, $this->username, 'Welcome to the clan', $event);
		$event = $db->titleFromUsername($username) . ' was added to the site with the password: ' . $password . '.';
		return $event;
	}
	else
	{
		return false;
	}
	
}

// >>>>>>>>>>>>>>    POST NEWS     <<<<<<<<<<<<<<<<<<<<<<<<

function postNews($title, $description, $news)
{
	global $db, $form;
	
	/* Title Error Checking */
	$field = "title";
	 if( !$title || strlen($title) == 0 )
	 	{$form->setError($field, " * Title not entered.");}
		
	/* Password error checking */
	$field = "news";
	 if( !$news || strlen($news) == 0 )
	 	{$form->setError($field, " * The news field has not been filled out.");}
		
	if( strlen($news) >= 50000 )
		{$form->setError($field, " * The news you entered was too long. It must be less than 50,000 characters long.");}

		
		
	if( $form->num_errors == 0 )  // If there were no errors with the submitted information
	{
		$db->insertNews( $this->username, $title, $description, nl2br($news), time() );
		$event = 'A new News post was added to the website by ' . $this->title . '.';
		$db->addToLogs($event, $this->username, 0);
		$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
		return $event;
	}
	else
	{
		return false;
	}
	
}

// >>>>>>>>>>>>>>    POST IA REQUEST    <<<<<<<<<<<<<<<<<<<<<<<<

function postIa($username, $reason)
{
	global $db, $form;
	
	/* Security Error Checking */
	$field = "security";
	 if( !$username || strlen($username) == 0 )
	 	{$form->setError($field, " * Title not entered.");}
		
	/* Reason Error Checking */
	$field = "security";
	 if( !$reason || strlen($reason) <= 10 || strlen($reason) >= 5000 )
	 	{$form->setError($field, " * Your reason must be between 10 and 5000 characters.");}

		
		
	if( $form->num_errors == 0 )  // If there were no errors with the submitted information
	{
		$db->postIa( $username, $reason );
		$event = 'An IA request was submited by ' . $this->title . '.';
		$db->addToLogs($event, $this->username, 0);
		$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
		return $event;
	}
	else
	{
		return false;
	}
	
}

// >>>>>>>>>>>>>>    APPROVE IA REQUEST    <<<<<<<<<<<<<<<<<<<<<<<<

function approveIa($id )
{
	global $db, $form;
	
	/* Security Error Checking */
		
	 if( !$id || strlen($id) == 0 )
	 	{$form->setError($field, " * IA Request not selected." . $id . "" . $username);}

		
		
	if( $form->num_errors == 0 )  // If there were no errors with the submitted information
	{
		$username = $db->approveIa( $id );
		$event = $this->title . ' approved ' . $username . '\'s IA request.';
		$db->addToLogs($event, $this->username, $username);
		$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
		return $event;
	}
	else
	{
		return false;
	}
	
}

// >>>>>>>>>>>>>>    REMOVE IA STATUS   <<<<<<<<<<<<<<<<<<<<<<<<

function removeIa( $username )
{
	global $db, $form;
	
	/* Security Error Checking */
	$field = "security";
	 if( !$username || strlen($username) == 0 )
	 	{$form->setError($field, " * Username not entered.");}
		
		
	if( $form->num_errors == 0 )  // If there were no errors with the submitted information
	{
		$db->removeIa( $username );
		$event = $this->title . ' has returned form being IA.';
		$db->addToLogs($event, $this->username, 0);
		$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
		return $event;
	}
	else
	{
		return false;
	}
	
}




// >>>>>>>>>>>>>>   EDIT TEMPLATE     <<<<<<<<<<<<<<<<<<<<<<<<

function editTemplate($template)
{
	global $config, $db, $form;
	
	/* Timeout Error Checking */
	$field = "template";
	 if( !$template || strlen($template) == 0 )
	 	{$form->setError($field, " * You did not enter a new template *");}
		
		
	if( $form->num_errors == 0 )  // If there were no errors with the submitted information
	{
		$db->changeTemplate( $template, $config->getTemplate('id') );
		$event = 'The template configuration was edited by ' . $this->title . '.';
		$db->addToLogs($event, $this->username, 0);
		$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
		return $event;
	}
	else
	{
		return false;
	}
	
}


//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>   EDIT PROFILE  <<<<<<<<<<<<<<<<<<<<<<<<<<

function editProfile($username, $displayname, $aim, $email, $location, $quote, $game, $cs, $css, $bw, $d2, $wow, $h3, $gw, $eaw, $war3)
{
	global $db, $form;

	/* Display Name error checking */
	$field = "displayname";
	 if( !$displayname || strlen($displayname) == 0 )
	 	{$form->setError($field, " * Display Name not entered");}
		
	/* AIM error checking */
	$field = "aim";
		if( !$aim || strlen($aim) == 0 )
		{$form->setError($field, " * AIM name not entered");}
		
	$field = "email";
		if( !$email || strlen($email) == 0 )
		{$form->setError($field, " * Email not entered");}
		
	$field = "game";
		if( !$game || strlen($game) == 0 )
		{$form->setError($field, " * Main Game not selected");}

	if( $form->num_errors == 0 )  // If there were no errors with the submitted information
		{
			$db->updateProfile($username, $displayname, $aim, $email, $location, $quote, $game, $cs, $css, $bw, $d2, $wow, $h3, $gw, $eaw, $war3);
			$event = $username . ' edited their profile.';
			$db->addToLogs($event, $this->username, 0);
			$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
			return $event;
		}
		else
		{
			return false;
		}
		
}

function newProfileImage( $profile_path )
{
	global $db, $form;

	/* Display Name error checking */
	$field = "security";
	 if( !$profile_path || strlen($profile_path) == 0 )
	 	{$form->setError($field, " * No Image Selected");}
	
	if( !file_exists( $profile_path ) )
		$form->setError($field, " * That file does not exist.");
		
	if( !getimagesize( $profile_path ) )
		$form->setError($field, " * That is not a valid image file.");

	if( $form->num_errors == 0 )  // If there were no errors with the submitted information
		{
			$sql = 'UPDATE `users` SET `image_path` = \'' . $profile_path . '\' WHERE CONVERT(`users`.`username` USING utf8) = \'' . $this->username . '\' LIMIT 1;';
			$result = mysql_query($sql) or die( mysql_error() );
			$event = $this->title . ' set a new profile image.';
			$db->addToLogs($event, $this->username, 0);
			$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
			return $event;
		}
		else
		{
			return false;
		}
		
}


//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>   MASS MESSAGE   <<<<<<<<<<<<<<<<<<<<<<<<<<

function massMessage( $recipientList, $medium, $title, $text )
{
	global $db, $form, $config, $notification;

	/* Title error checking */
	$field = "title";
	 if( !$title || strlen($title) == 0 )
	 	{$form->setError($field, " * Title not entered");}
		
	/* To error checking */
	$field = "to";
	 if( !$recipientList || strlen($recipientList) == 0 )
	 	{$form->setError($field, " * No recipient selected");}
		
	/* Text error checking */
	$field = "text";
		if( !$text || strlen($text) == 0 )
		{$form->setError($field, " * No text entered");}	
		
		if( strlen($text) >= $config->getSetting('max_message_length') )
		{$form->setError($field, "* Messages must be less than " . $config->getSetting('max_message_length') . " letters long. *");}
	
	$field = "medium";
		if( !$medium || strlen($medium) == 0 )
		{$form->setError($field, " * You must select either Message or Notification.");}

	if( $form->num_errors == 0 )  // If there were no errors with the submitted information
		{
			$usernameList = array();
			$field = 'to';
			foreach( $recipientList as $recipient )
			{	
				switch($recipient)
				{
					// Entire Clan
					case 1:
						$sql = 'select username from users where disabled = 0;';
						$result = mysql_query($sql);
						while( $row = mysql_fetch_array($result))
						{
							if( !in_array( $row['username'], $usernameList ) )
							$usernameList[] = $row['username'];
						}
					break;
					// Commanders Only
					case 2:
						$sql = 'select username from users where rank = 25 and disabled = 0;';
						$result = mysql_query($sql);
						while( $row = mysql_fetch_array($result))
						{
							if( !in_array( $row['username'], $usernameList ) )
							$usernameList[] = $row['username'];
						}
					break;
					// Administrators Only
					case 3:
						$sql = 'select username from users where (rank >= 21 and rank <= 24) and disabled = 0;';
						$result = mysql_query($sql);
						while( $row = mysql_fetch_array($result))
						{
							if( !in_array( $row['username'], $usernameList ) )
							$usernameList[] = $row['username'];
						}
					break;
					// Officers Only
					case 4:
						$sql = 'select username from users where (rank >= 15 and rank <= 20) and disabled = 0;';
						$result = mysql_query($sql);
						while( $row = mysql_fetch_array($result))
						{
							if( !in_array( $row['username'], $usernameList ) )
							$usernameList[] = $row['username'];
						}
					break;
					// Warrant Officers Only
					case 5:
						$sql = 'select username from users where (rank >= 10 and rank <= 14) and disabled = 0;';
						$result = mysql_query($sql);
						while( $row = mysql_fetch_array($result))
						{
							if( !in_array( $row['username'], $usernameList ) )
							$usernameList[] = $row['username'];
						}
					break;
					// Enlisted Only
					case 6:
						$sql = 'select username from users where (rank >= 0 and rank <= 9) and disabled = 0;';
						$result = mysql_query($sql);
						while( $row = mysql_fetch_array($result))
						{
							if( !in_array( $row['username'], $usernameList ) )
							$usernameList[] = $row['username'];
						}
					break;
					// Administrators and Above
					case 7:
						$sql = 'select username from users where (rank >= 21 ) and disabled = 0;';
						$result = mysql_query($sql);
						while( $row = mysql_fetch_array($result))
						{
							if( !in_array( $row['username'], $usernameList ) )
							$usernameList[] = $row['username'];
						}
					break;
					// Officers and Above
					case 8:
						$sql = 'select username from users where (rank >= 16 ) and disabled = 0;';
						$result = mysql_query($sql);
						while( $row = mysql_fetch_array($result))
						{
							if( !in_array( $row['username'], $usernameList ) )
							$usernameList[] = $row['username'];
						}
					break;
					// Warrant Officers and Above
					case 9:
						$sql = 'select username from users where (rank >= 10 ) and disabled = 0;';
						$result = mysql_query($sql);
						while( $row = mysql_fetch_array($result))
						{
							if( !in_array( $row['username'], $usernameList ) )
							$usernameList[] = $row['username'];
						}
					break;
					// Trial Members
					case 10:
						{$form->setError($field, " * Trial Members Not Enabled");}
					break;
					// DSL 0 - 7
					case 11:
					case 12:
					case 13:
					case 14:
					case 15:
					case 16:
					case 17:
					case 18:
					{$form->setError($field, " * By DSL not supported");}
					/*
						$dsl = $recipient - 11;
						$timestamplow = time() - ($dsl * 86400);
						$timestamphigh = time() -  ( ($dsl + 1) * 86400 );
						$sql = 'select username
						from users
						where disabled = 0
						and last_login > ' . $timestamplow . '
						and last_login < ' . $timestamphigh . ';';
						$result = mysql_query($sql);
						while( $row = mysql_fetch_array($result))
						{
							if( !in_array( $row['username'], $usernameList ) )
							$usernameList[] = $row['username'];
						}*/
					break;
					// Corps Leaders
					case 19:
						{$form->setError($field, " * Group Mass Message Disabled");}
					break;
					// Division Leaders
					case 20:
						{$form->setError($field, " * Group Mass Message Disabled");}
					break;
					// Squadron Leaderes
					case 21:
						{$form->setError($field, " * Group Mass Message Disabled");}
					break;
				}	
				
			}
			
			if( $form->num_errors == 0 )  // If there were no errors with the submitted information
			{

				
				foreach( $usernameList as $username )
				{
					switch( $medium )
					{
						case 1:
							$db->send($title, $username, $this->username, time(), $text );
							$notification->setNot($username, $this->username, $title, 'You have recieved a new mass message. <br /><br />Posted by - ' . $this->username . '');
						break;
						case 2:
							$notification->setNot($username, $this->username, $title, $text . '<br /><br />Posted by - ' . $this->username . '');
						break;
					}
				}
				switch( $medium )
					{
						case 1:
							$event = 'A mass message was sent by ' . $this->title . '.';
							$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
							return $event;
							
						break;
						case 2:
							$event = 'A mass notification was created by ' . $this->title . '.';
							$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
							return $event;
						break;
					}
				/*
				$db->send($title, $to, $this->username, time(), $text);
				$event = 'A message was sent to ' . $db->titleFromUsername($to) . ' by ' . $this->title . '.';
				$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
				$event2 = 'You have just recieved a new message from ' . $this->title . '';
				$notification->setNot($to, $this->username, 'New Message', $event2);
				return $event;
				return $usernameList*/
				}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		
}

//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>   SEND MESSAGE  <<<<<<<<<<<<<<<<<<<<<<<<<<

function send($title, $text, $to)
{
	global $db, $form, $config, $notification;

	/* Title error checking */
	$field = "title";
	 if( !$title || strlen($title) == 0 )
	 	{$form->setError($field, " * Title not entered");}
		
	/* To error checking */
	$field = "to";
	 if( !$to || strlen($to) == 0 )
	 	{$form->setError($field, " * Username not entered");}
		
	/* Text error checking */
	$field = "text";
		if( !$text || strlen($text) == 0 )
		{$form->setError($field, " * No text entered");}	
		
		if( strlen($text) >= $config->getSetting('max_message_length') )
		{$form->setError($field, "* Messages must be less than " . $config->getSetting('max_message_length') . " letters long. *");}	

	if( $form->num_errors == 0 )  // If there were no errors with the submitted information
		{
			$db->send($title, $to, $this->username, time(), $text);
			$event = 'A message was sent to ' . $db->titleFromUsername($to) . ' by ' . $this->title . '.';
			$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
		$event2 = 'You have just recieved a new message from ' . $this->title . '';
		$notification->setNot($to, $this->username, 'New Message', $event2);
			return $event;
		}
		else
		{
			return false;
		}
		
}

//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>   DELETE MESSAGE  <<<<<<<<<<<<<<<<<<<<<<<<<<

function delete($idList)
{
	global $db, $form, $config;
	$field = "security";
	foreach( $idList as $id )
	{
		if( !$db->messageIsTo($id, $this->username) )
	 	{$form->setError($field, " * That message is not addressed to you * ");}
		
	}
	/* Ownership Security Checking */

	if( $form->num_errors == 0 )  // If there were no errors with the submitted information
		{
			foreach( $idList as $id )
			{
				$db->deleteMessage($id);
			}
			$event = 'A message or several messages were deleted by ' . $this->title . '.';
			$db->addToLogs($event, $this->username, 0);
			$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
			return $event;
		}
	else
		{
			return false;
		}
		
}

//>>>>>>>>>>>>>>>>>>>  CHANGE PASSWORD   <<<<<<<<<<<<<<<<<<<<<<<<

function changePassword($username, $current_password, $new_password, $confirmed_new_password)
{
	global $db, $form, $notification;
	
	/* Username error checking */
	$field = "username";
	 if( !$username || strlen($username) == 0 )
	 	{$form->setError($field, " * Username not found in our databases *");}
		
	/* Password error checking */
	$field = "currentPassword";
		if( !$current_password || strlen($current_password) == 0 )
		{$form->setError($field, " * Password not entered");}
		
		if( strlen($current_password) < 5 )
		{$form->setError($field, " * Password must be longer than 5 characters");}
		
		if( strlen($current_password) >= 15 )
		{$form->setError($field, " * Password must be shorter than 15 characters");}
		
	$field = "newPassword";
		if( !$new_password || strlen($new_password) == 0 )
		{$form->setError($field, " * Password not entered");}
		
		if( strlen($new_password) < 5 )
		{$form->setError($field, " * Password must be longer than 5 characters");}
		
		if( strlen($new_password) >= 15 )
		{$form->setError($field, " * Password must be shorter than 15 characters");}
		
	$field = "confirmNewPassword";
		if( !$confirmed_new_password || strlen($confirmed_new_password) == 0 )
		{$form->setError($field, " * Password not entered");}
		
		if( strlen($confirmed_new_password) < 5 )
		{$form->setError($field, " * Password must be longer than 5 characters");}
		
		if( strlen($confirmed_new_password) >= 15 )
		{$form->setError($field, " * Password must be shorter than 15 characters");}
		
	/* Security error checking */
	$field = "security";
		if( $new_password != $confirmed_new_password )
		{$form->setError($field, " * The new passwords do not match up * ");}
		
		if( md5($current_password) != $db->getPassword($username) )
		{$form->setError($field, " * Incorrect Current Password * ");}




	if( $form->num_errors == 0 )  // If there were no errors with the submitted information
		{
			$db->updatePassword( $username, md5($new_password) );
			$event = 'You have sucessfully changed your password.';
			$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
			$notification->setNot($username, $this->username, 'Password Changed', $event);
			return $event;
		}
		else
		{
			return false;
		}
		
}

// >>>>    DISABLE A MEMBER  <<<<<<<<

function disable($username)
{
	global $db, $form;
	
	/* Username Error Checking */
	$field = "username";
	
	if( !$db->usernameTaken($username) )
	{$form->setError($field, " * That username is not in the database");}
	
	if( !$username || strlen($username) == 0 )
	{$form->setError($field, " * Username not entered");}
	
	/* Security Error Checking */
	$field = "security";
	if( $db->rank_numFromUsername($username) >= $this->rank_num )
	{$form->setError($field, " * You do not have permission to do this.");}
	
	
	if( $form->num_errors == 0 )// There were no errors with the information.
	{
		$db->disableMember($username);
		$event = '' . $db->rankFromUsername($username) . ' ' . $db->displaynameFromUsername($username) . ' was disabled by ' . $this->title.'.';
		$db->addToLogs($event, $this->username, $username);
		$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
		return $event;
	}
	else
	{
		return false;
	}
}

// >>>>    ENABLE A MEMBER  <<<<<<<<

function enable($username)
{
	global $db, $form, $notification;
	
	/* Username Error Checking */
	$field = "username";
	
	if( !$db->usernameTaken($username) )
	{$form->setError($field, " * That username is not in the database");}
	
	if( !$username || strlen($username) == 0 )
	{$form->setError($field, " * Username not entered");}
	
	/* Security Error Checking */
	$field = "security";
	if( $db->rank_numFromUsername($username) >= $this->rank_num )
	{$form->setError($field, " * You do not have permission to do this.");}
	
	
	if( $form->num_errors == 0 )// There were no errors with the information.
	{
		$db->enableMember($username);
		$event = '' . $db->rankFromUsername($username) . ' ' . $db->displaynameFromUsername($username) . ' was enabled by ' . $this->title.'.';
		$db->addToLogs($event, $this->username, $username);
		$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
		$db->setLoginInfo($username, time(), $_SERVER['REMOTE_ADDR']);
		$event2 = 'You have been enabled by ' . $this->title . '';
		$notification->setNot($username, $this->username, 'Enabled', $event2);
		return $event;
	}
	else
	{
		return false;
	}
}

// >>>>    MODIFY THE RANK OF A MEMBER  <<<<<<<<

function setRank($username, $rank)
{
	global $db, $form, $notification;
	
	/* Username Error Checking */
	$field = "username";
	
	if( !$db->usernameTaken($username) )
	{$form->setError($field, " * That username is not in the database");}
	
	if( !$username || strlen($username) == 0 )
	{$form->setError($field, " * Username not entered");}
	
	/* Rank Error Checking */
	$field = "rank";
	
	if( $rank > 25 || $rank < 1 )
	{$form->setError($field, " * That is an invalid rank");}
	
	
	/* Security Error Checking */
	$field = "security";
	if( $db->rank_numFromUsername($username) >= $this->rank_num && $this->rank_num != 25 )
	{$form->setError($field, " * You do not have permission to do this.");}
	
	if( $this->rank_num <= $rank && $this->rank_num != 25 )
	{$form->setError($field, " * You do not have permission to do this *"); }
	
	
	if( $form->num_errors == 0 )// There were no errors with the information.
	{
		$rank_start = $db->rankFromUsername($username);
		$db->setRank($username, $rank);
		$event = '' . $rank_start . ' ' . $db->displaynameFromUsername($username) . ' was set to the rank of ' . $db->rankFromUsername($username) . ' by ' . $this->title . ' .';
		$db->addToLogs($event, $this->username, $username);
		$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
		$event2 = 'You have been set to the rank of ' . $db->rankFromUsername($username) . ' by ' . $this->title . '';
		$notification->setNot($username, $this->username, 'Your rank has been changed.', $event2);
		return $event;
	}
	else
	{
		return false;
	}
}


// >>>>   PROMOTE A MEMBER <<<<<<<<

function promote($username)
{
	global $db, $form, $notification;
	
	/* Username Error Checking */
	$field = "username";
	
	if( !$db->usernameTaken($username) )
	{$form->setError($field, " * That username is not in the database");}
	
	if( !$username || strlen($username) == 0 )
	{$form->setError($field, " * Username not entered");}
	
	if( $db->rank_numFromUsername($username) >= 25 )
	{$form->setError($field, " * You cannot promote a Commander");}
	
	
	/* Security Error Checking */
	$field = "security";
	if( $db->rank_numFromUsername($username) >= $this->rank_num - 1 && $this->rank_num != 25 )
	{$form->setError($field, " * You do not have permission to do this.");}
	
	if( $db->getLastDemotion($username) >= time() - (3600 * 24) )
	{$form->setError($field, " * That person has been promoted or demoted in the last 24 hours.");}
	
	
	if( $form->num_errors == 0 )// There were no errors with the information.
	{
		$rank_start = $db->rankFromUsername($username);
		$rank_promote = $db->rank_numFromUsername($username) + 1;
		$db->setRank($username, $rank_promote);
		$event = '' . $rank_start . ' ' . $db->displaynameFromUsername($username) . ' was promoted to the rank of ' . $db->rankFromUsername($username) . ' by ' . $this->title . ' .';
		$db->addToLogs($event, $this->username, $username);
		$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
		$event2 = 'You have been promoted to the rank of ' . $db->rankFromUsername($username) . ' by ' . $this->title . '';
		$notification->setNot($username, $this->username, 'You have been Promoted', $event2);
		return $event;
		
	}
	else
	{
		return false;
	}
}

// >>>>   DEMOTE A MEMBER <<<<<<<<

function demote($username)
{
	global $db, $form, $notification;
	
	/* Username Error Checking */
	$field = "username";
	
	if( !$db->usernameTaken($username) )
	{$form->setError($field, " * That username is not in the database");}
	
	if( !$username || strlen($username) == 0 )
	{$form->setError($field, " * Username not entered");}
	
	if( $db->rank_numFromUsername($username) <= 1 )
	{$form->setError($field, " * You cannot demote a private");}
	
	
	/* Security Error Checking */
	$field = "security";
	if( $db->rank_numFromUsername($username) >= $this->rank_num && $this->rank_num != 25 )
	{$form->setError($field, " * You do not have permission to do this.");}
	
	if( $db->getLastDemotion($username) >= time() - (3600 * 24) )
	{$form->setError($field, " * That person has been promoted or demoted in the last 24 hours.");}
	
	
	if( $form->num_errors == 0 )// There were no errors with the information.
	{
		$rank_start = $db->rankFromUsername($username);
		$rank_demote = $db->rank_numFromUsername($username) - 1;
		$db->setRank($username, $rank_demote);
		$event = '' . $rank_start . ' ' . $db->displaynameFromUsername($username) . ' was demoted to the rank of ' . $db->rankFromUsername($username) . ' by ' . $this->title . ' .';
		$db->addToLogs($event, $this->username, $username);
		$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
		$event2 = 'You have been demoted to the rank of ' . $db->rankFromUsername($username) . ' by ' . $this->title . '';
		$notification->setNot($username, $this->username, 'You have been Demoted', $event2);
		return $event;
	}
	else
	{
		return false;
	}
}

function editDivision( $id, $name, $dl1, $dl2, $description, $game )
{
global $db, $form;
/* Name Error Checking */
	$field = "name";
	
	if( !$name || strlen($name) <= 5 )
	{$form->setError($field, " * Division Name must be more than 5 characters long.");}
	
	$field = "dl1";
	
	if( !$dl1 || strlen($dl1) <= 5 )
	{$form->setError($field, " * You must select at least one divisin leader.");}

	$field = "description";
	
	if( !$description || strlen($description) <= 5 || strlen($description) >= 1000 )
	{$form->setError($field, " * The description must be between 5 and 1000 characters long. *");}
	
	$field = "game";
	
	if( !$game || strlen($game) == 0 )
	{$form->setError($field, " * No game was selected");}
	
	if( $form->num_errors == 0 )// There were no errors with the information.
	{
		$db->editDivision( $id, $name, $dl1, $dl2, $description, $game );
		$event = ' The ' . $name . ' division was edited by ' . $this->title . ' .';
		$db->addToLogs($event, $this->username, 0);
		$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
		return $event;
		
	}
	else
	{
		return false;
	}

}

function editDivisionMember( $id, $username, $title)
{
global $db, $form;
/* Name Error Checking */
	$field = "username";
	
	if( !$username || strlen($username) == 0 )
	{$form->setError($field, " * Username not found or entered *");}
	
/* ID Error Checking */
	$field = "id";
	
	if( !$id || strlen($id) == 0 )
	{$form->setError($field, " * ID not entered *");}
	
/* Division Title Error Checking */
	$field = "title";
	
	if( !$title || strlen($title) < 3 )
	{$form->setError($field, " * Title must be at least 3 characters long *");}

	
	if( $form->num_errors == 0 )// There were no errors with the information.
	{
		$db->editDivisionMember( $id, $username, $title );
		$event = '' . $db->titleFromUsername($username) . ', a member of the ' . $db->divisionNameFromId($id) . ' division was edited by ' . $this->title . ' .';
		$db->addToLogs($event, $this->username, $username);
		$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
		return $event;
	}
	else
	{
		return false;
	}

}

function removeDivisionMember( $id, $username )
{
global $db, $form;
/* Name Error Checking */
	$field = "username";
	
	if( !$username || strlen($username) == 0 )
	{$form->setError($field, " * Username not found or entered *");}
	
/* ID Error Checking */
	$field = "id";
	
	if( !$id || strlen($id) == 0 )
	{$form->setError($field, " * ID not entered *");}

	if( $form->num_errors == 0 )// There were no errors with the information.
	{
		$db->removeDivisionMember( $id, $username );
		$event = '' . $db->titleFromUsername($username) . ', a member of the ' . $db->divisionNameFromId($id) . ' division was removed by ' . $this->title . ' .';
		$db->addToLogs($event, $this->username, $username);
		$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
		return $event;
	}
	else
	{
		return false;
	}

}

function addDivision( $name, $description, $dl1, $dl2, $game )
{
global $db, $form;
/* Name Error Checking */
	$field = "name";
	
	if( !$name || strlen($name) == 0 )
	{$form->setError($field, " * Division Name Not Entered");}
	
/* Description Error Checking */
	$field = "description";
	
	if( strlen($description) < 1 || strlen($description) >= 5000 )
	{$form->setError($field, " * Must be between 1 and 5000 characters long.");}
	
/* Division Leader Checking */
	$field = "dl1";
	
	if( strlen($dl1) == 0 || !$dl1 )
	{$form->setError($field, " * No Division Leader Selected");}
	
/* Game Error Checking */
	$field = "game";
	
	if( strlen($game) == 0 || !$game )
	{$form->setError($field, " * No Game Selected");}

	if( $form->num_errors == 0 )// There were no errors with the information.
	{
		$db->addDivision( $name, $description, $dl1, $dl2, $game );
		
		$db->addDivisionMember($dl1, 'Division Leader', $db->getMaxDivisionId());
		if($dl2)
		{
		$db->addDivisionMember($dl2, 'Division Leader', $db->getMaxDivisionId());
		}
		$event = 'The ' . $name . ' division was added to the website by ' . $this->title . ' .';
		$db->addToLogs($event, $this->username, $username);
		$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
		return $event;
		
	}
	else
	{
		return false;
	}


}

function resetPassword( $username )
{
global $db, $form, $notification;
/* Name Error Checking */
	$field = "username";
	
	if( !$username || strlen($username) == 0 )
	{$form->setError($field, " * Username Not Entered");}
	
/* Game Error Checking */
	$field = "security";
	
	if( $db->rank_numFromUsername($username) >= $db->rank_numFromUsername($this->username) && $db->rank_numFromUsername($this->username) != 25 )
	{$form->setError($field, " * You cannot reset the password of that user");}

	if( $form->num_errors == 0 )// There were no errors with the information.
	{
		$newpassword = generatePassword(6);
		$password = md5($newpassword);
		$db->updatePassword($username, $password);
		$event = '' . $this->username . ' reset ' . $username . '\'s password.';
		$db->addToLogs($event, $this->username, $username);
		$event = 'You have reset the password of ' . $username . ' to ' . $newpassword . '.';
		$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
		$event2 = 'Your password was changed by ' . $this->title . ' to ' . $newpassword . '';
		$notification->setNot($username, $this->username, 'Password Reset', $event2);
		return $event;
	}
	else
	{
		return false;
	}


}

function changeName( $username, $newname )
{
global $db, $form, $notification;
/* Name Error Checking */
	$field = "username";
	
	if( !$username || strlen($username) == 0 )
	{$form->setError($field, " * Username Not Selected");}

/* Name Error Checking */
	$field = "newname";
	
	if( !$newname || strlen($newname) == 0 )
	{$form->setError($field, " * New Username not Entered");}
	
/* Game Error Checking */
	$field = "security";
	
	if( $db->rank_numFromUsername($username) >= $db->rank_numFromUsername($this->username) && $db->rank_numFromUsername($this->username) != 25 )
	{$form->setError($field, " * You cannot change the name of that user");}

	if( $form->num_errors == 0 )// There were no errors with the information.
	{
		$db->changeName($username, $newname);
		$event = '' . $this->username . ' changed ' . $username . '\'s username to ' . $newname . '.';
		$db->addToLogs($event, $this->username, $username);
		$event = 'You changed the username of ' . $username . ' to ' . $newname . '.';
		$db->setLoginInfo($this->username, time(), $_SERVER['REMOTE_ADDR']);
		$event2 = 'Your username was changed by ' . $this->title . ' to ' . $newname . '';
		$notification->setNot($username, $this->username, 'Name Changed', $event2);
		return $event;
	}
	else
	{
		return false;
	}


}

function logout() // Set the logged_in variable to false and destroy the session.
	{
	global $db;
	$this->logged_in = false;
	$db->setLogoutInfo($this->username);
	setcookie('username', '', 1);
	setcookie('userid', '', 1);
	session_destroy();
	}
};
// >>  Begin Setting up the user object for use  <<
$user = new User();  // Create the User object
$form = new Form();
$security = new Security();
?>
