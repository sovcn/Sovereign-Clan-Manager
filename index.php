<?php

require('include/user.php');
include('include/news.php');
include('include/browser.php');
include('include/profile_include.php');
class Main
{
	function Main( $view, $file )
	{
		global $config, $config_file, $user, $group, $db, $security, $statistic, $form, 
				$notification, $group_form, $permission, $games, $file_management;
		
		
		if( true )
		{
		if($config->getTemplate('use'))
		{include($config->getTemplate('header'));}
		

		
		switch( $view )
		{	
			case 'console':
				include('console.php');
			break;
			
			case 'members':
				include('members.php');
			break;
			
			case 'groups':
				include('group_list.php');
			break;
			
			case 'group':
				include('group.php');
			break;
			
			case 'group_function':
				include('group_function.php');
			break;
			
			case 'news':
				include('news.php');
			break;
			
			case 'logs':
				include('logs.php');
			break;
			
			case 'profile':
				include('profile.php');
			break;
			
			case 'statistics':
				include('member_statistics.php');
			break;
			
			case 'manage_users':
				include('user_management.php');
			break;
			
			case 'user_personal':
				include('user_personal.php');
			break;
			
			case 'user_form':
				include('user_form_misc.php');
			break;
			
			case 'user_administration':
				include('user_administration.php');
			break;
			
			case 'user_commander':
				include('user_commander.php');
			break;
			
			
			case 'message':
				include('message.php');
			break;
			
			case 'information':
				$information = new Information;
				$information->displayFile( $file );
			break;
			
			case 'edit_information':
				$information = new Information;
				$information->editFile( $file );
			break;
			
			case 'security_error':
				include('security_error.php');
			break;
			
			case 'fix':
				include('fix.php');
			break;
			
			case 'file':
				$file_management->displayFile($file);
			break;
			
			case 'project_playlist':
				include('information/project_playlist.html');
			break;
			
			case 'soon':
				echo('The feature that you have requested is not yet available but will be soon.');
			break;
			
			default:
				if( $view )
				{
					echo('You have an error with your URL.  Please check to see that your "View" variable is correct.');
				}
				else
				{
				$information = new Information;
				$information->displayFile( 1 );
				echo('
				<div class="green_contentbox">
					<div class="green_content_top">
						<h3 class="content_box_header">Online Members</h3>
					</div>
					<div class="green_content">
						<h5>These users have been active on the site in the past ' . ( $config->getSetting("active_timeout") / 60 ) . ' minutes.</h5>
						<hr />
						');
						$online_array = $statistic->getActiveMembers();
						echo('| ');
						foreach( $online_array as $username )
						{
							echo('<a href="index.php?view=profile&user=' . $username . '">' .
									$db->titleFromUsername($username) . '</a> |
								  ');	
						}
						
						echo('
						<hr />
					</div>
					<div class="green_content_bottom">
					</div>
				</div>

				');
				}
			break;
		}

		if($config->getTemplate('use'))
		{include($config->getTemplate('footer'));}
		}
		else
		{
			include('um/um.html');
		}
	
	}

};

$main = new Main($_GET['view'], $_GET['file']);
?>
