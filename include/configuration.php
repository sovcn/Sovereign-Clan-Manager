<?

class Setting
{
	var $id;
	var $name;
	var $description;
	var $value;
	
	function Setting( $id, $name, $description, $value )
	{
		$this->id = $id;
		$this->name = $name;
		$this->description = $description;
		$this->value = $value;
	}
};

class Config
{
	var $config_list;
	
	var $template_id;
	var $template_use;
	var $template_header;
	var $template_footer;
	
	function Config()
	{
		$sql = 'select * from config;';
		$result = mysql_query($sql);
		while( $row = mysql_fetch_array($result) )
		{
			$this->config_list[$row['id']] = new Setting( $row['id'], $row['name'], $row['description'], $row['value'] );
		}
		
		$this->initializeTemplate();
	}
	
	function getSetting($id)
	{
		return $this->config_list[$id]->value;
	}
	
	function initializeTemplate()
	{
		$sql = 'select * from templates where current = 1 limit 1;';
		$result = mysql_query($sql);
		if( mysql_num_rows( $result ) > 0 )
		{
			$array = mysql_fetch_array($result);
			$this->template_id = $array['id'];
			$this->template_use = 1;
			$this->template_header = $array['header'];
			$this->template_footer = $array['footer'];
		}
		else
		{
			$this->template_id = 0;
			$this->template_use = 0;
			$this->template_header = 0;
			$this->template_footer = 0;
		}
	}
	
	function getTemplate( $value )
	{
		switch( $value )
		{
			case 'id':
				return $this->template_id;
			break;
			case 'use':
				return $this->template_use;
			break;
			case 'header':
				return $this->template_header;
			break;
			case 'footer':
				return $this->template_footer;
			break;
		}
	
	}
	
	function modifyConfiguration( $tempSettings )
	{
		global $config, $db, $form, $user;
		foreach( $this->config_list as $setting )
		{
			$field = $setting->id;
			
			if( !$tempSettings[$setting->id] )
			{$form->setError($field, " * You must enter a value for " . $setting->name . " * ");}
			
			switch( $setting->id )
			{
				case 'minRankCorps':
				case 'minRankDivision':
				case 'minRankSquad':
				
				if( $tempSettings[$setting->id] > 25 || $tempSettings[$setting->id] < 1 )
				{$form->setError($field, " This value must be between 1 and 25 ");}
				
				break;
			
			}
			
			if( $form->num_errors == 0 )  // If there were no errors with the submitted information
			{
				$this->updateConfiguration( $setting->id, $tempSettings[$setting->id] );
			}
		}
				
		if( $form->num_errors == 0 )  // If there were no errors with the submitted information
		{
			$event = 'The site-wide configuration settings were edited by ' . $user->title . '.';
			$db->addToLogs($event, $user->username, 0);
			$db->setLoginInfo($user->username, time(), $_SERVER['REMOTE_ADDR']);
			return $event;
		}
		else
		{
			return false;
		}
	
	}
	
	function updateConfiguration( $id, $value )
	{
		$sql = 'UPDATE `config` SET `value` = \'' . $value . '\' WHERE CONVERT(`config`.`id` USING utf8) = \'' . $id . '\' LIMIT 1;';
		$result = mysql_query($sql);
	}
};

class Config_File extends Config
{
	
	function Config_File()
	{
		$sql = 'select * from config_file;';
		$result = mysql_query($sql);
		while( $row = mysql_fetch_array($result) )
		{
			$this->config_list[$row['id']] = new Setting( $row['id'], $row['name'], $row['description'], $row['value'] );
		}
	}
	
	function updateConfiguration( $id, $value )
	{
		$sql = 'UPDATE `config_file` SET `value` = \'' . $value . '\' WHERE CONVERT(`config_file`.`id` USING utf8) = \'' . $id . '\' LIMIT 1;';
		$result = mysql_query($sql) or die(mysql_error());	
	}
};

class Config_Tagbox extends Config
{
	
	function Config_Tagbox()
	{
		$sql = 'select * from config_tagbox;';
		$result = mysql_query($sql);
		while( $row = mysql_fetch_array($result) )
		{
			$this->config_list[$row['id']] = new Setting( $row['id'], $row['name'], $row['description'], $row['value'] );
		}
	}
	
	function updateConfiguration( $id, $value )
	{
		$sql = 'UPDATE `config_tagbox` SET `value` = \'' . $value . '\' WHERE CONVERT(`config_tagbox`.`id` USING utf8) = \'' . $id . '\' LIMIT 1;';
		$result = mysql_query($sql) or die(mysql_error());	
	}
};

$config = new Config();
$config_file = new Config_File();
$config_tagbox = new Config_Tagbox();
?>
