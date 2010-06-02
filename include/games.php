<?php
class Games
{
	function nameFromId($id)
	{
		$sql = 'select name from game where id = "' . $id . '" limit 1;';
		$result = mysql_query($sql);
		$array = mysql_fetch_array($result);
		$name = $array['name'];
		return $name;
	}
	
	function listRadioGames()
	{
		$sql = 'select * from game;';
		$result = mysql_query($sql);
		echo('
		<table cellpadding="0" cellspacing="3px">
								<tr>
		');
		while( $row = mysql_fetch_array($result) )
			{
				echo('
				<td>	
					<img src="images/' . $row['image'] . '" alt="' . $row['name'] . '" />
				</td>
				');
			}
			echo('</tr>
				  <tr>');
		$sql = 'select * from game;';
		$result = mysql_query($sql);
		while( $row = mysql_fetch_array($result) )
			{
				echo('
				<td>
					<input type="radio" name="game" value="' . $row['id'] . '" />
				</td>
				');
			}
		echo('
		</tr>
		</table>');
	}
	
	function listRadioGamesGroups($id)
	{
		$sql = 'select * from game;';
		$result = mysql_query($sql);
		echo('
		<table cellpadding="0" cellspacing="3px">
								<tr>
		');
		while( $row = mysql_fetch_array($result) )
			{
				echo('
				<td>	
					<img src="images/' . $row['image'] . '" alt="' . $row['name'] . '" />
				</td>
				');
			}
			echo('</tr>
				  <tr>');
		$sql = 'select * from game;';
		$result = mysql_query($sql);
		while( $row = mysql_fetch_array($result) )
			{
				if( $row['id'] == $id )
				{
					echo('
					<td>
						<input type="radio" name="game" checked="yes" value="' . $row['id'] . '" />
					</td>
					');
				}
				else
				{
					echo('
					<td>
						<input type="radio" name="game" value="' . $row['id'] . '" />
					</td>
					');
				}
			}
		$number = mysql_num_rows($result) - 1;
		echo('
		</tr>
		<tr>
			<td colspan="' . $number . '">
			Check this for no game
			</td>
			<td>
			');
			if( !$id )
			{
				echo('<input type="radio" name="game" value="0" checked="yes" />');
			}
			else
			{
				echo('<input type="radio" name="game" value="0" />');
			}
			echo('
		</tr>
		</table>');
	}
	
	
	function imageFromId($id)
	{
		$sql = 'select image, name from game where id = "' . $id . '" limit 1;';
		$result = mysql_query($sql);
		$array = mysql_fetch_array($result);
		echo('<img src="images/' . $array['image'] . '" alt="' . $array['name'] . '" />');
	}
	
	function validateId($id)
	{
		$sql = 'select * from game where id = "' . $id . '" limit 1;';
		$result = mysql_query($sql);
		if( mysql_num_rows($result) == 1 )
			return true;
		else
			return false;
	}

};

$games = new Games;
?>
