<?php

if( $security->runSecurity(0, $user->username, 1, 1, 1) )
{
	if($_SESSION['success'])
	{
		$cmd = $_GET['cmd'];
		echo('		<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">');
									
				switch( $cmd )
				{
				// Depending on the CMD variable, display the coressponding form
					case 'add_corps':
						echo('Adding New Corps');
					break;
					
					case'add_division':
						echo('Adding New Division');
					break;
					
					case 'add_squadron':
						echo('Adding New Squadron');
					break;
						
					case 'application':
						echo('Submit a Group Application');
					break;
					
					case 'edit_info':
						echo('Editing Group Info');
					break;
						
					case 'edit_member':
						echo('Editing Group Member');
					break;
						
					case 'edit_leaders':
						echo('Editing Group Leaders');
					break;
					
					case 'post_event':
						echo('Posting Group Event');
					break;
					
					case 'notify_members':
						echo('Notifying Group Members');
					break;
					
					case 'message_members':
						echo('Mass Messaging Group Members');
					break;
					
					case 'process_applications':
						echo('Processing Group Applications');
					break;
					
					default:
						echo('No Command Variable Found');
					break;
				}
									
									echo('</h3>
								</div>
							</div>
							<div class="content_box_minor_header">
								<h4 class="content_box_minor_header">
									
								</h4>
							</div>
						<p class="event">' . $_SESSION['event'] . '</p>
		<br /><a class="return" href="index.php?view=console">Click Here to return to your console.</a>
		
								</div>
					</div>
					<div align="center"><div class="content_box_footer"></div></div>
				</div>');
		
		unset($_SESSION['success']);
		unset($_SESSION['event']);
	}
	else  // If not successful in adding the member or if no attempt as been made
	{
		$array = $_SESSION['value'];  // Gets the values that the user submited in their first attempt so that they can be used again
		unset($_SESSION['value']);
		
/// BEGING DISPLAYING FORMS  ///
		
		if( $_GET['id'] )
		{
			$group = new Group;
			$cmd = $_GET['cmd'];
			
			if( $group->isAdmin( $_GET['id'], $user->username ) || $cmd == 'application' || $cmd == 'add_corps' || $cmd == 'add_division' || $cmd == 'add_squadron' )
			{
				$id = $_GET['id'];
				switch( $cmd )
				{
				// Depending on the CMD variable, display the coressponding form
					case 'add_corps':
						if( $security->hasPermission( $config->getSetting('minRankCorps') ) )
						{
							$group_form->addCorps();
						}
						else
						{
							echo('You are not a high enough rank to do that.');
						}
					break;
					
					case'add_division':
					if( $security->hasPermission( $config->getSetting('minRankDivision') ) )
						{
							$group_form->addDivision($user->username);
						}
						else
						{
							echo('You are not a high enough rank to do that.');
						}
					break;
					
					case 'add_squadron':
						$group_form->addSquadron($user->username);
					break;
						
					case 'application':
						$group_form->application($id);
					break;
					
					case 'edit_info':
						$group_form->editInfo($user->username, $id);
					break;
						
					case 'edit_member':
						$group_form->editMember($id);
					break;
						
					case 'edit_leaders':
						$group_form->editLeaders($id);
					break;
					
					case 'post_event':
						$group_form->postEvent($id);
					break;
					
					case 'notify_members':
						$group_form->notifyMembers($id);
					break;
					
					case 'message_members':
						$group_form->messageMembers($id);
					break;
					
					case 'process_applications':
						$group_form->processApplication($id);
					break;
				}
			}
			else
			{
				die('You do not have permission to modify that group... Stop Snooping, logging event... logging ip address...');
			}
		}
		else
		{
			echo('No Group Was Selected');
		}

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
unset($_SESSION['success']);
unset($_SESSION['event']);
?>
