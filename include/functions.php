<?php
function select_user($disabled, $user_rank)
{
	$sql = 'select username, displayname, ranks.name
			from users, ranks
			where disabled = ' . $disabled . '
			and users.rank < ' . $user_rank . '
			and users.rank = ranks.id
			order by users.rank desc, username asc;';
	$result = mysql_query($sql);
	while( $row = mysql_fetch_array($result) )
	{
		echo('<option value="' . $row['username'] . '">' . $row['name'] . ' ' . $row['displayname'] . '</option>');
	}
}

function select_user_all($disabled)
{
	$sql = 'select username, displayname, ranks.name
			from users, ranks
			where disabled = ' . $disabled . '
			and users.rank = ranks.id
			order by users.rank desc, username asc;';
	$result = mysql_query($sql);
	while( $row = mysql_fetch_array($result) )
	{
		echo('<option value="' . $row['username'] . '">' . $row['name'] . ' ' . $row['displayname'] . '</option>');
	}
}

function select_rank($user_rank)
{
	$sql = 'select name, id
			from ranks
			where id < ' . $user_rank . '
			order by id desc;';
	$result = mysql_query($sql);
	while( $row = mysql_fetch_array($result) )
	{
		echo('<option value="' . $row['id'] . '">' . $row['name'] . '</option>');
	}

}

function select_rank_admin()
{
	$sql = 'select name, id
			from ranks
			order by id desc;';
	$result = mysql_query($sql);
	while( $row = mysql_fetch_array($result) )
	{
		echo('<option value="' . $row['id'] . '">' . $row['name'] . '</option>');
	}

}

function select_templates()
{
 $sql = 'select id, name
 		 from templates;';
$result = mysql_query($sql);
while( $row = mysql_fetch_array($result) )
	{
		echo('<option value="' . $row['id'] . '">' . $row['name'] . '</option>');
	}


}

function daysSinceTimestamp($timestamp)
{
	$seconds = time() - $timestamp;
	$mins = $seconds / 60;
	$hours = $mins / 60;
	$days = $hours / 24;
	return floor($days);

}

function membersList($sql, $sql2)
{
global $config;

$result = mysql_query($sql);
$result2 = mysql_query($sql2);
$array2 = mysql_fetch_array($result2);
echo('	
<div class="content_header"><h3 class="content_box_header">' . $array2['name'] . '</h3></div></div>
					<div class="content_box_minor_header">
						<table align="left" class="dataTable">
							<tr align="center">
								<td>
									<h4 class="content_box_minor_header">
										Rank
									</h4>
								</td>
								<td>
									<h4 class="content_box_minor_header">
										Name
									</h4>
								</td>
								<td>
									<h4 class="content_box_minor_header">
										AIM
									</h4>
								</td>
								<td>
									<h4 class="content_box_minor_header">
										Game
									</h4>
								</td>
								<td>
									<h4 class="content_box_minor_header">
										Online
									</h4>
								</td>
								<td>
									<h4 class="content_box_minor_header">
										DSL
									</h4>
								</td>
							</tr>
	');
while( $row = mysql_fetch_array($result) )
{
	$profile = new Profile($row['username']);
	echo('
			<tr align="center">
				<td>
					' . $profile->image . '
				</td>
				<td>
					<a class="members" href="index.php?view=profile&user=' . $profile->username . '&ref=members.php">' . $profile->displayname . '</a>
				</td>
				<td>
					<div class="aim">' . truncate($profile->aim, 10) . '
				</td>
				<td>
					<img src="images/' . $profile->maingame . '.gif" alt="' . $profile->maingame . '" />
				</td>
				<td>
					');
					 if($profile->status == 1)
					 {
					 echo('<img width="20px" src="images/online.png" alt="Yes" />');
					 }
					 else
					 echo('<img width="20px" src="images/offline.png" alt="No" />');
					echo('
				</td>
				<td>
					' . $profile->dsl . '
				</td>
			</tr>
		');
}
echo('</table>

	');


}


function trunc($phrase, $max_words)
{
   $phrase_array = explode(' ',$phrase);
   if(count($phrase_array) > $max_words && $max_words > 0)
      $phrase = implode(' ',array_slice($phrase_array, 0, $max_words)).'...';
   return $phrase;
}
    
  function truncate($str, $length=10, $trailing='...')
      {
            $length-=strlen($trailing);
            if (strlen($str)> $length)
            {
               // string exceeded length, truncate and add trailing dots
               return substr($str,0,$length).$trailing;
            }
            else
            {
               // string was already short enough, return the string
               $res = $str;
            }
            return $res;
      }



function dateFromTimestamp($timestamp)
{
$date = date("n-j-y", $timestamp);
return $date;
}

function dateFromTimestamp_Long($timestamp)
{
$date = date("g:i A - d F, Y", $timestamp);
return $date;
}

function dateFromTimestamp_Med($timestamp)
{
$date = date("d F, Y", $timestamp);
return $date;
}


function generatePassword($length)
{

  // start with a blank password
  $password = "";

  // define possible characters
  $possible = "0123456789bcdfghjkmnpqrstvwxyz"; 
    
  // set up a counter
  $i = 0; 
    
  // add random characters to $password until $length is reached
  while ($i < $length) { 

    // pick a random character from the possible ones
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
        
    // we don't want this character if it's already in the password
    if (!strstr($password, $char)) { 
      $password .= $char;
      $i++;
    }

  }

  // done!
  return $password;

}



?>
