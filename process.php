<?php
require('include/user.php');
include('include/profile_include.php');
/*
Steps to adding a new process..

1) Create the form and link it to process.php with a hidden value cmd
2) Create the user member function with validation
3) Create the process function and send back functions
4) Set permissions in the constants.php file
5) Configure security settings



*/

class Process
{
    // Holds any information that is to be posted on the page.
	// Tells the user if the event was successful.
	
	function Process()
		{
		global $user, $security, $permission;
		$cmd = $_POST['cmd'];
		if( $cmd == "login" )
			{
			$this->doLogin();
			}
		else if( $cmd == "add" && $security->runSecurity($permission->getPermission('addmember'), $user->username, 1, 1, 1) )
			{
			$this->doAddMember();
			}	
		else if( $cmd == "tag" && $security->runSecurity(1, $user->username, 1, 1, 1) )
			{
			$this->doNewTag();
			}
		else if( $cmd == "deleteTag" && $security->runSecurity(1, $user->username, 1, 1, 1) )
			{
			$this->doDeleteTag();
			}
		else if( $cmd == "modify_music_settings" && $security->runSecurity($permission->getPermission('modify_music_settings'), $user->username, 1, 1, 1) )
			{
			$this->doUpdateMusicSettings();
			}
		else if( $cmd == "upload" && $security->runSecurity($permission->getPermission('upload'), $user->username, 1, 1, 1) )
			{
			$this->doUploadFile();
			}
		else if( $cmd == "new_profile_image" && $security->runSecurity(1, $user->username, 1, 1, 1) )
			{
			$this->doNewProfileImage();
			}
		else if( $cmd == "disable" && $security->runSecurity($permission->getPermission('disable'), $user->username, 1, 1, 1) )
			{
			$this->doDisable();
			}
		else if( $cmd == "enable" && $security->runSecurity($permission->getPermission('enable'), $user->username, 1, 1, 1) )
			{
			$this->doEnable();
			}
		else if( $cmd == "setrank" && $security->runSecurity($permission->getPermission('setrank'), $user->username, 1, 1, 1) )
			{
			$this->doSetRank();
			}
		else if( $cmd == "promote" && $security->runSecurity($permission->getPermission('promote'), $user->username, 1, 1, 1) )
			{
			$this->doPromote();
			}
		else if( $cmd == "demote" && $security->runSecurity($permission->getPermission('demote'), $user->username, 1, 1, 1) )
			{
			$this->doDemote();
			}
		else if( $cmd == "edit_profile" && $security->runSecurity(1, $user->username, 1, 1, 1) )
			{
			$this->doEditProfile();
			}
		else if( $cmd == "change_password" && $security->runSecurity(1, $user->username, 1, 1, 1) )
			{
			$this->doChangePassword();
			}
		else if( $cmd == "post_news" && $security->runSecurity($permission->getPermission('news'), $user->username, 1, 1, 1) )
			{
			$this->doPostNews();
			}
		else if( $cmd == "send_pm" && $security->runSecurity(1, $user->username, 1, 1, 1) )
			{
			$this->doSend();
			}
		else if( $cmd == "delete_message" && $security->runSecurity(1, $user->username, 1, 1, 1) )
			{
			$this->doDeleteMessage();
			}
		else if( $cmd == "delete_upload" && $security->runSecurity($permission->getPermission('upload'), $user->username, 1, 1, 1) )
			{
			$this->doDeleteUpload();
			}
		else if( $cmd == "config" && $security->runSecurity($permission->getPermission('commander'), $user->username, 1, 1, 1) )
			{
			$this->doEditConfig();
			}
		else if( $cmd == "modify_file_settings" && $security->runSecurity($permission->getPermission('modify_file_settings'), $user->username, 1, 1, 1) )
			{
			$this->doModifyFileSettings();
			}
		else if( $cmd == "tagbox_admin" && $security->runSecurity($permission->getPermission('tagbox_admin'), $user->username, 1, 1, 1) )
			{
			$this->doModifyTagboxSettings();
			}
		else if( $cmd == "modify_permissions" && $security->runSecurity($permission->getPermission('commander'), $user->username, 1, 1, 1) )
			{
			$this->doEditPermissions();
			}
		else if( $cmd == "template" && $security->runSecurity($permission->getPermission('commander'), $user->username, 1, 1, 1) )
			{
			$this->doEditTemplate();
			}
		else if( $cmd == "reset" && $security->runSecurity($permission->getPermission('admin'), $user->username, 1, 1, 1) )
			{
			$this->doResetPassword();
			}
		else if( $cmd == "post_ia" && $security->runSecurity(1, $user->username, 1, 1, 1) )
			{
			$this->doPostIa();
			}
		else if( $cmd == "approve_ia" && $security->runSecurity($permission->getPermission('admin'), $user->username, 1, 1, 1) )
			{
			$this->doApproveIa();
			}
		else if( $cmd == "remove_ia" && $security->runSecurity(1, $user->username, 1, 1, 1) )
			{
			$this->doRemoveIa();
			}
		else if( $cmd == "changename" && $security->runSecurity($permission->getPermission('changename'), $user->username, 1, 1, 1) )
			{
			$this->doChangeName();
			}
		else if( $cmd == "send_mass_pm" && $security->runSecurity($permission->getPermission('mass_message'), $user->username, 1, 1, 1) )
			{
			$this->doMassMessage();
			}
		else if( !$cmd )
			{
				if( $_GET['change'] )
				{
					$user->modifyTemplate(1,3, $_GET['change']);
					header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
				else
				{
				$this->doLogout();
				}
			}
		else
			{
			$this->doError();
			}
		
		}
		
	function doLogin()
		{
			global $user, $form;
			// Attempt to log in
			$login = $user->login($_POST['username'], $_POST['password'], $_POST['remember']);
			// If log in is successful
			if($login)
			{
			header("Location: index.php?view=console");
			}
			// If there were errors
			else
			{
			$_SESSION['value'] = array( "username" => $_POST['username'], "password" => $_POST['password'] );
			$_SESSION['value_array'] = $_POST;
         	$_SESSION['error_array'] = $form->getErrorArray();
         	header("Location: index.php?view=console");
			}
		}
	
	function doUpdateMusicSettings()
	{
		global $user, $form, $permission;
		
		$music = new Music($user->username);
		$result = $music->updateMusicSettings( $user->username, $_POST['pp_id'], $_POST['shuffle'], $_POST['autostart'], $_POST['color'] );
		
		if($result)
				{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $result;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['event'] = "Error Uploading File";
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
	
	}
	
	function doNewTag()
	{
		global $user, $form, $permission;
		
		$tagbox = new Tagbox();
		$result = $tagbox->newTag( $user->username, $_POST['text'] );
		
		if($result)
				{
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
	
	}
	
	function doDeleteTag()
	{
		global $user, $form, $permission;
		
		$tagbox = new Tagbox();
		$result = $tagbox->deleteTag( $_POST['tagId'], $user->username );
		
		if($result)
				{
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
	
	}
		
	function doUploadFile()
	{
		global $user, $form, $permission, $file_management;
		
		$result = $file_management->uploadFile( $_FILES['file'], $_POST['name'], $_POST['description'] );
		
		if($result)
				{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $result;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['event'] = "Error Uploading File";
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
	
	}
	
	function doNewProfileImage()
	{
		global $user, $form, $permission, $file_management;
		
		$result = $user->newProfileImage( $_POST['profile_path'] );
		
		if($result)
				{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $result;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['event'] = "Error Setting New Profile Image";
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
	
	}
	
	function doModifyFileSettings()
	{
		global $user, $form, $config_file;
			foreach( $config_file->config_list as $setting )
			{
				$tempSettings[$setting->id] = $_POST[$setting->id];
			}
			$result = $config_file->modifyConfiguration( $tempSettings );
			// If Attempt was successful
			if($result)
				{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $result;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['event'] = "Error Editing Configuration";
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
	}
	
	function doModifyTagboxSettings()
	{
		global $user, $form, $config_tagbox;
			foreach( $config_tagbox->config_list as $setting )
			{
				$tempSettings[$setting->id] = $_POST[$setting->id];
			}
			$result = $config_tagbox->modifyConfiguration( $tempSettings );
			// If Attempt was successful
			if($result)
				{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $result;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['event'] = "Error Editing Configuration";
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
	}
	
	function doEditPermissions()
	{
		global $user, $form, $permission;
		foreach ($permission->permissions as $row)
		{
			$tempPerms[$row->id] = $_POST[$row->id];
		}
		$result = $permission->modifyPermissions( $tempPerms );
		if($result)
				{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $result;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['event'] = "Error modifying permissions";
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
	
	}
	
	function doMassMessage()
	{
		global $user, $form, $permission;
		
		$recipientList = $_POST['to'];
		$result = $user->massMessage( $recipientList, $_POST['medium'], $_POST['title'], $_POST['text'] );

		if($result)
				{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $result;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['event'] = "Error Sending Mass Message/Notification";
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}

	}
	
	function doAddMember()
		{
			global $user, $form;
			// Attempt to add a new member
			$addmember = $user->addMember($_POST['username'], $_POST['username'], $_POST['email'], $_POST['aim'], $_POST['game']);
			// If Attempt was successful
			if($addmember)
				{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $addmember;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['event'] = "Error adding member";
				$_SESSION['value'] = array( "username" => $_POST['username'], "displayname" => $_POST['displayname'], "email" => $_POST['email'], "aim" => $_POST['aim'] );
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
		}

	function doEditConfig()
		{
			global $user, $form, $config;
			foreach( $config->config_list as $setting )
			{
				$tempSettings[$setting->id] = $_POST[$setting->id];
			}
			$result = $config->modifyConfiguration( $tempSettings );
			// If Attempt was successful
			if($result)
				{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $result;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['event'] = "Error Editing Configuration";
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
		}
		
	function doEditTemplate()
		{
			global $user, $form;
			// Attempt to add a new member
			$result = $user->editTemplate( $_POST['template'] );
			// If Attempt was successful
			if($result)
				{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $result;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['event'] = "Error Modifying Template";
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
		}
		
	function doEditProfile()
		{
			global $user, $form;
			$edit = $user->editProfile($user->username, $user->username, $_POST['aim'], $_POST['email'], $_POST['location'], $_POST['quote'], $_POST['game'], $_POST['cs'], $_POST['css'], $_POST['bw'], $_POST['d2'], $_POST['wow'], $_POST['h3'], $_POST['gw'], $_POST['eaw'], $_POST['war3']);
			// If Attempt was successful
			if($edit)
				{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $edit;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['event'] = "Error updating profile";
				$_SESSION['value'] = array( "displayname" => $_POST['displayname'], "aim" => $_POST['aim'], "location" => $_POST['location'], "quote" => $_POST['quote'] );
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
		}
		
	function doChangePassword()
		{
			global $user, $form;
			$change_pass = $user->changePassword( $user->username, $_POST['curpass'], $_POST['newpass'], $_POST['confnewpass']);
			// If Attempt was successful
			if($change_pass)
				{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $change_pass;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['event'] = "Error changing your password";
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
		
		}
				
	function doDisable()
		{
			global $user, $form;
			// Attempt to disable member
			$disable = $user->disable($_POST['username']);
			// If sucessful
			if($disable)
			{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $disable;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
			}
			// If unsuccessful 
			else
			{
				$_SESSION['event'] = "Error disabling member";
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
			}
		}
		
	function doEnable()
		{
			global $user, $form;
			// Attempt to disable member
			$enable = $user->enable($_POST['username']);
			// If sucessful
			if($enable)
			{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $enable;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
			}
			// If unsuccessful 
			else
			{
				$_SESSION['event'] = "Error disabling member";
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
			}
		}
		
	function doSetRank()
		{
			global $user, $form;
			// Attempt to disable member
			$setrank = $user->setRank($_POST['username'], $_POST['rank']);
			// If sucessful
			if($setrank)
			{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $setrank;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
			}
			// If unsuccessful 
			else
			{
				$_SESSION['event'] = "Error changing the rank of the member";
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
			}
		}
		
	function doPromote()
		{
			global $user, $form;
			// Attempt to disable member
			$promote = $user->promote( $_POST['username'] );
			// If sucessful
			if($promote)
			{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $promote;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
			}
			// If unsuccessful 
			else
			{
				$_SESSION['event'] = "Error promoting the member";
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
			}
		}
		
	function doDemote()
		{
			global $user, $form;
			// Attempt to disable member
			$demote = $user->demote( $_POST['username'] );
			// If sucessful
			if($demote)
			{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $demote;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
			}
			// If unsuccessful 
			else
			{
				$_SESSION['event'] = "Error promoting the member";
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
			}
		}
		
	function doPostNews()
		{
			global $user, $form;
			// Attempt to add a new member
			$post_news = $user->postNews( $_POST['title'], $_POST['description'], $_POST['news'] );
			// If Attempt was successful
			if($post_news)
				{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $post_news;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['event'] = "Error posting news";
				$_SESSION['value'] = array( "title" => $_POST['title'], "description" => $_POST['description'], "news" => $_POST['news'] );
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
		}
		
	function doSend()
		{
			global $user, $form;
			// Attempt to add a new member
			$post_news = $user->send( $_POST['title'], $_POST['text'], $_POST['to'] );
			// If Attempt was successful
			if($post_news)
				{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $post_news;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['event'] = "Error Sending Message";
				$_SESSION['value'] = array( "title" => $_POST['title'], "text" => $_POST['text'] );
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
		}
		
	function doDeleteMessage()
		{
			global $user, $form;
			// Attempt to add a new member
			$result = $user->delete( $_POST['delete'] );
			// If Attempt was successful
			if($result)
				{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $result;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['event'] = "Error Deleting Message/s";
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
		}
		
	function doDeleteUpload()
		{
			global $user, $form, $file_management;
			// Attempt to add a new member
			$result = $file_management->delete( $_POST['delete'] );
			// If Attempt was successful
			if($result)
				{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $result;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['event'] = "Error Deleting File/s";
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
		}

function doChangeName()
{
		global $user, $form;
			// Attempt to add a new member
			$result = $user->changeName( $_POST['username'], $_POST['newname'] );
			// If Attempt was successful
			if($result)
				{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $result;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['event'] = "Error Modifying Members Name";
				$_SESSION['value'] = array( "newname" => $_POST['newname']);
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
	}
	
function doResetPassword()
{
		global $user, $form;
			// Attempt to add a new member
			$result = $user->resetPassword( $_POST['username'] );
			// If Attempt was successful
			if($result)
				{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $result;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['event'] = "Error Changing Password";
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
	}
	
function doPostIa()
{
		global $user, $form;
			// Attempt to add a new member
			$result = $user->postIa( $user->username, $_POST['reason'] );
			// If Attempt was successful
			if($result)
				{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $result;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['event'] = "Error Posting IA Request";
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
	}

function doApproveIa()
{
		global $user, $form;
			// Attempt to add a new member
			$result = $user->approveIa( $_POST['id'] );
			// If Attempt was successful
			if($result)
				{
				$_SESSION['success'] = true;
				$_SESSION['event'] = $result;
				header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
			// If it was not successful
			else
				{
				$_SESSION['event'] = "Error Approving IA Request";
				$_SESSION['value_array'] = $_POST;
         		$_SESSION['error_array'] = $form->getErrorArray();
         		header("Location: " . $_SERVER['HTTP_REFERER'] . "");
				}
	}
	
function doRemoveIa()
{
			global $user, $form;
			// Attempt to add a new member
			$user->removeIa( $user->username );
			// If Attempt was successful
			header("Location: index.php?view=console");

}

		
	function doLogout()
		{
		global $user;
		$user->logout();
		header("Location: index.php");
		}
		
	function doError()
		{
		echo('Error with the command variable');
		}
		
};

class Group_Process
{
	function Group_Process()
	{
		global $group_validation, $security, $config, $user, $form, $db;
		$cmd = $_POST['cmd'];
		switch( $cmd )
		{
			case 'new_corps':
				if( $security->runSecurity($config->getSetting('minRankCorps'), $user->username, 1, 1, 1) )
				{
					$result = $group_validation->createCorps( $_POST['name'], 
															  $_POST['description'], 
															  $_POST['leader1'], 
															  $_POST['leader2'], 
															  $_POST['image'] );
					// If Attempt was successful
					if($result)
						{
						$_SESSION['success'] = true;
						$_SESSION['event'] = $result;
						header("Location: " . $_SERVER['HTTP_REFERER'] . "");
						}
					// If it was not successful
					else
						{
						$_SESSION['event'] = "Error Creating New Corps";
						$_SESSION['value_array'] = $_POST;
						$_SESSION['error_array'] = $form->getErrorArray();
						header("Location: " . $_SERVER['HTTP_REFERER'] . "");
						}
					}
					else
					{
						$_SESSION['event'] = "Error Creating New Corps";
						$_SESSION['value_array'] = $_POST;
						$_SESSION['error_array'] = $form->getErrorArray();
						header("Location: " . $_SERVER['HTTP_REFERER'] . "");
					}
			break;
			
			case 'new_division':
				$group_local = new Group;
				if( $security->runSecurity($config->getSetting('minRankDivision'), $user->username, 1, 1, 1) 
					&& $group_local->isAdmin( $_POST['parentcorps'], $user->username ) )
				{
					$result = $group_validation->createDivision( $_POST['name'], 
															  $_POST['description'], 
															  $_POST['leader1'], 
															  $_POST['leader2'],
															  $_POST['game'], 
															  $_POST['image'],
															  $_POST['parentcorps'] );
					// If Attempt was successful
						if($result)
							{
							$_SESSION['success'] = true;
							$_SESSION['event'] = $result;
							header("Location: " . $_SERVER['HTTP_REFERER'] . "");
							}
						// If it was not successful
						else
							{
							$_SESSION['success'] = false;
							$_SESSION['event'] = "Error Creating New Division";
							$_SESSION['value_array'] = $_POST;
							$_SESSION['error_array'] = $form->getErrorArray();
							header("Location: " . $_SERVER['HTTP_REFERER'] . "");
							}
					}
					else
					{
						$_SESSION['success'] = false;
						$_SESSION['event'] = "Error Creating New Division";
						$_SESSION['value_array'] = $_POST;
						$_SESSION['error_array'] = $form->getErrorArray();
						header("Location: " . $_SERVER['HTTP_REFERER'] . "");
					}
			break;
			
			case 'new_squadron':
				$group_local = new Group;
				if( $security->runSecurity($config->getSetting('minRankSquad'), $user->username, 1, 1, 1) 
					&& $group_local->isAdmin( $_POST['parentdivision'], $user->username ) )
				{
					$group_local->setInfo($_POST['parentdivision']);
					$parentcorps = $group_local->parentcorps;
					$result = $group_validation->createSquadron( $_POST['name'], 
															  $_POST['description'], 
															  $_POST['leader1'], 
															  $_POST['leader2'],
															  $_POST['game'], 
															  $_POST['image'],
															  $parentcorps,
															  $_POST['parentdivision'] );
					// If Attempt was successful
						if($result)
							{
							$_SESSION['success'] = true;
							$_SESSION['event'] = $result;
							header("Location: " . $_SERVER['HTTP_REFERER'] . "");
							}
						// If it was not successful
						else
							{
							$_SESSION['event'] = "Error Creating New Squadron";
							$_SESSION['value_array'] = $_POST;
							$_SESSION['error_array'] = $form->getErrorArray();
							header("Location: " . $_SERVER['HTTP_REFERER'] . "");
							}
					}
					else
					{
						$_SESSION['event'] = "Error Creating New Squadron";
						$_SESSION['value_array'] = $_POST;
						$_SESSION['error_array'] = $form->getErrorArray();
						header("Location: " . $_SERVER['HTTP_REFERER'] . "");
					}
			break;
			
			case 'group_application':
				$group_local = new Group;
				if( $security->runSecurity(0, $user->username, 1, 1, 1) )
				{
					$group_local->setInfo($_POST['groupid']);
					$parentcorps = $group_local->parentcorps;
					$result = $group_validation->application( $_POST['reason'], $_POST['comments'], $_POST['groupid']);
					// If Attempt was successful
						if($result)
							{
							$_SESSION['success'] = true;
							$_SESSION['event'] = $result;
							header("Location: " . $_SERVER['HTTP_REFERER'] . "");
							}
						// If it was not successful
						else
							{
							$_SESSION['event'] = "Error Submiting Application";
							$_SESSION['value_array'] = $_POST;
							$_SESSION['error_array'] = $form->getErrorArray();
							header("Location: " . $_SERVER['HTTP_REFERER'] . "");
							}
					}
					else
					{
						$_SESSION['event'] = "Error Submiting Application";
						$_SESSION['value_array'] = $_POST;
						$_SESSION['error_array'] = $form->getErrorArray();
						header("Location: " . $_SERVER['HTTP_REFERER'] . "");
					}
			break;
			
			case 'approve_application':
				$group_local = new Group;
				$sql = 'select * from group_applications where id = ' . $_POST['appid'] . ' limit 1;';
				$result = mysql_query($sql);
				$object = mysql_fetch_object($result);
				if( $security->runSecurity($config->getSetting('minRankSquad'), $user->username, 1, 1, 1) 
					&& $group_local->isAdmin( $object->gid, $user->username ) )
				{
					$group_local->setInfo($object->gid);
					
					$result = $group_validation->approveApplication( $_POST['appid'], $object->username, $object->gid );
					
					// If Attempt was successful
						if($result)
							{
							$_SESSION['success'] = true;
							$_SESSION['event'] = $result;
							header("Location: " . $_SERVER['HTTP_REFERER'] . "");
							}
						// If it was not successful
						else
							{
							$_SESSION['event'] = "Error Approving Application";
							$_SESSION['value_array'] = $_POST;
							$_SESSION['error_array'] = $form->getErrorArray();
							header("Location: " . $_SERVER['HTTP_REFERER'] . "");
							}
					}
					else
					{
						$_SESSION['event'] = "Error Approving Application";
						$_SESSION['value_array'] = $_POST;
						$_SESSION['error_array'] = $form->getErrorArray();
						header("Location: " . $_SERVER['HTTP_REFERER'] . "");
					}
			break;
			
			case 'deny_application':
				$group_local = new Group;
				$sql = 'select * from group_applications where id = ' . $_POST['appid'] . ' limit 1;';
				$result = mysql_query($sql);
				$object = mysql_fetch_object($result);
				if( $security->runSecurity($config->getSetting('minRankSquad'), $user->username, 1, 1, 1) 
					&& $group_local->isAdmin( $object->gid, $user->username ) )
				{
					$group_local->setInfo($object->gid);

					$result = $group_validation->denyApplication( $_POST['appid'], $object->username, $object->gid );
					
					// If Attempt was successful
						if($result)
							{
							$_SESSION['success'] = true;
							$_SESSION['event'] = $result;
							header("Location: " . $_SERVER['HTTP_REFERER'] . "");
							}
						// If it was not successful
						else
							{
							$_SESSION['event'] = "Error Denying Application";
							$_SESSION['value_array'] = $_POST;
							$_SESSION['error_array'] = $form->getErrorArray();
							header("Location: " . $_SERVER['HTTP_REFERER'] . "");
							}
					}
					else
					{
						$_SESSION['event'] = "Error Denying Application";
						$_SESSION['value_array'] = $_POST;
						$_SESSION['error_array'] = $form->getErrorArray();
						header("Location: " . $_SERVER['HTTP_REFERER'] . "");
					}
			break;
			
			case 'group_edit_info':
				$group_local = new Group;
				if( $security->runSecurity(0, $user->username, 1, 1, 1) 
					&& $group_local->isAdmin( $_POST['groupid'], $user->username ) )
				{

					//  ---
					$group_local->setInfo( $_POST['groupid'] );
					
					switch( $group_local->title )
					{
						case 'Corps':
							$result = $group_validation->editInfo( $_POST['groupid'], $_POST['name'], $_POST['description'], $_POST['game'], $_POST['image'],
								 0, 0 );
						break;
						
						case 'Division':
							$result = $group_validation->editInfo( $_POST['groupid'], $_POST['name'], $_POST['description'], $_POST['game'], $_POST['image'],
								 $_POST['parentcorps'], 0 );
						break;
						
						case 'Squadron':
							$group_parent = new Group;
							$group_parent->setInfo( $_POST['parentdivision'] );
							$result = $group_validation->editInfo( $_POST['groupid'], $_POST['name'], $_POST['description'], $_POST['game'], $_POST['image'],
								$group_parent->parentcorps , $_POST['parentdivision'] );
						break;
						
						default:
							$result = false;
						break;
						
					}
						if($result)
								{
								$_SESSION['success'] = true;
								$_SESSION['event'] = $result;
								header("Location: " . $_SERVER['HTTP_REFERER'] . "");
								}
							// If it was not successful
							else
								{
								$_SESSION['event'] = "Error Modifying Group Info";
								$_SESSION['value_array'] = $_POST;
								$_SESSION['error_array'] = $form->getErrorArray();
								header("Location: " . $_SERVER['HTTP_REFERER'] . "");
								}
					}
					else
					{
						$_SESSION['event'] = "Error Modifying Group Info";
						$_SESSION['value_array'] = $_POST;
						$_SESSION['error_array'] = $form->getErrorArray();
						header("Location: " . $_SERVER['HTTP_REFERER'] . "");
					}
				
			break;
			
			case 'group_edit_member':
				$group_local = new Group;
				if( $security->runSecurity(0, $user->username, 1, 1, 1) 
					&& $group_local->isAdmin( $_POST['groupid'], $user->username ) )
				{

					$result = $group_validation->editMember( $_POST['groupid'], $_POST['username'], $_POST['userid'], $_POST['title'] );
					
					// If Attempt was successful
						if($result)
							{
							$_SESSION['success'] = true;
							$_SESSION['event'] = $result;
							header("Location: " . $_SERVER['HTTP_REFERER'] . "");
							}
						// If it was not successful
						else
							{
							$_SESSION['event'] = "Error Modifying Group Member";
							$_SESSION['value_array'] = $_POST;
							$_SESSION['error_array'] = $form->getErrorArray();
							header("Location: " . $_SERVER['HTTP_REFERER'] . "");
							}
					}
					else
					{
						$_SESSION['event'] = "Error Modifying Group Member";
						$_SESSION['value_array'] = $_POST;
						$_SESSION['error_array'] = $form->getErrorArray();
						header("Location: " . $_SERVER['HTTP_REFERER'] . "");
					}
			break;
			
			case 'group_remove_member':
				$group_local = new Group;
				if( $security->runSecurity(0, $user->username, 1, 1, 1) 
					&& $group_local->isAdmin( $_POST['groupid'], $user->username ) )
				{

					$result = $group_validation->removeMember( $_POST['groupid'], $_POST['username'], $_POST['userid'] );
					
					// If Attempt was successful
						if($result)
							{
							$_SESSION['success'] = true;
							$_SESSION['event'] = $result;
							header("Location: " . $_SERVER['HTTP_REFERER'] . "");
							}
						// If it was not successful
						else
							{
							$_SESSION['event'] = "Error Removing Group Member";
							$_SESSION['value_array'] = $_POST;
							$_SESSION['error_array'] = $form->getErrorArray();
							header("Location: " . $_SERVER['HTTP_REFERER'] . "");
							}
					}
					else
					{
						$_SESSION['event'] = "Error Removing Group Member";
						$_SESSION['value_array'] = $_POST;
						$_SESSION['error_array'] = $form->getErrorArray();
						header("Location: " . $_SERVER['HTTP_REFERER'] . "");
					}
			break;
			
			case 'edit_leaders':
				$group_local = new Group;
				if( $security->runSecurity(0, $user->username, 1, 1, 1) 
					&& $group_local->isAdmin( $_POST['groupid'], $user->username ) )
				{

					$result = $group_validation->editLeaders( $_POST['groupid'], $_POST['leader1'], $_POST['leader2'] );
					
					// If Attempt was successful
						if($result)
							{
							$_SESSION['success'] = true;
							$_SESSION['event'] = $result;
							header("Location: " . $_SERVER['HTTP_REFERER'] . "");
							}
						// If it was not successful
						else
							{
							$_SESSION['event'] = "Error Modifying Group Leadership -1";
							$_SESSION['value_array'] = $_POST;
							$_SESSION['error_array'] = $form->getErrorArray();
							header("Location: " . $_SERVER['HTTP_REFERER'] . "");
							}
					}
					else
					{
						$_SESSION['event'] = "Error Modifying Group Leadership -2";
						$_SESSION['value_array'] = $_POST;
						$_SESSION['error_array'] = $form->getErrorArray();
						header("Location: " . $_SERVER['HTTP_REFERER'] . "");
					}
			break;
			
			case 'post_event':
			
			break;
			
			case 'notify_group':
			
			break;
			
			case 'message_group':
			
			break;
			
			default:
				$process = new Process();
			break;
		
		
		}
	
	}
};

$group_process = new Group_Process;




?>
