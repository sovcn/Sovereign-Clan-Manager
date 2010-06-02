<?php
$group = new Group;
$group->setInfo($_GET['id']);
switch($group->title)
{
// If the selected group is a corps....
	case 'Corps':
		$corps = new Corps;
		$corps->displayMainPage($_GET['id'], $user->username);
	break;
// If the selected group is a division....
	case 'Division':
		$division = new Division;
		$division->displayMainPage($_GET['id'], $user->username);
	break;
// If the selected group is a squadron....
	case 'Squadron':
		$squadron = new Squadron;
		$squadron->displayMainPage($_GET['id'], $user->username);
	break;
	
}

?>
