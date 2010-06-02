<?php
if($user->logged_in)
{
echo('
<h3>Welcome, ' . $user->displayname . '</h3>
<div><a href="index.php?view=console">Got to your Console</a></div><br />
<div><a href="index.php?view=editprofile">Edit your Profile</a></div><br />
<div><a href="index.php?view=profile&user=' . $user->username . '&ref=console">View Account</a></div>
<br />
<div><a href="index.php?view=send.php">Send a PM</a></div>
<br />
<div><a href="process.php"><img border="0" src="templates/blue_ghost/images/Button_Logout.gif" alt="Logout"></a></div>
');


}

?>
