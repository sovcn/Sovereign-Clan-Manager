<?php

class Permission
{
	var $id;
	var $name;
	var $description;
	var $header;
	var $value;
	
	function Permission( $id, $name, $description, $header, $value )
	{
		$this->id = $id;
		$this->name = $name;
		$this->description = $description;
		$this->header = $header;
		$this->value = $value;
	}
};

class Permissions
{
	var $permissions;
	
	function Permissions()
	{
		$sql = 'select * from permission';
		$result = mysql_query($sql);
		while( $row = mysql_fetch_array($result) )
		{
			$this->permissions[$row['id']] = new Permission( $row['id'], $row['name'], $row['description'], $row['header'], $row['value'] );
		}
	}
	
	function getPermission( $id )
	{
		if($this->permissions[$id]->value)
		return $this->permissions[$id]->value;
		else
		return $this->permissions['commander']->value;
	}
	
	function selectRanks( $id, $current )
	{
		global $db;
		$sql = 'select id, name from ranks order by id desc;';
		$result = mysql_query($sql);
		echo('
				<select class="select" name="' . $id . '">
					<option value="' . $current . '">' . $db->rankFromRank_Num($current) . '</option>
				');
		while( $row = mysql_fetch_array($result) )
		{
			echo('<option value="' . $row['id'] . '">' . $row['name'] . '</option>');
		}
		echo(' </select> ');
		
	}
	
	function modifyPermissions( $tempPerms )
	{
		global $config, $db, $form, $user;
		foreach( $this->permissions as $row )
		{
			$field = $row->id;
			if( !$tempPerms[$row->id] )
			{$form->setError($field, " * You must enter a value for " . $row->name . " * ");}
			
			if( $form->num_errors == 0 )  // If there were no errors with the submitted information
			{
				$this->updatePermission( $row->id, $tempPerms[$row->id] );
			}
		}
			
		if( $form->num_errors == 0 )  // If there were no errors with the submitted information
		{
			$event = 'The site-wide permissions were edited by ' . $user->title . '.';
			$db->addToLogs($event, $user->username, 0);
			$db->setLoginInfo($user->username, time(), $_SERVER['REMOTE_ADDR']);
			return $event;
		}
		else
		{
			return false;
		}
	}
	
	function updatePermission( $id, $value )
	{
		$sql = 'UPDATE `permission` SET `value` = \'' . $value . '\' WHERE CONVERT(`permission`.`id` USING utf8) = \'' . $id . '\' LIMIT 1;';
		$result = mysql_query($sql);
	}
	

	
};

$permission = new Permissions();

?>