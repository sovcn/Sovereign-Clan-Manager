<?php

class Ia
{
var $id;
var $username;
var $reason;
var $time;
var $exists;

function Ia( $id)
{
$this->exists = $this->getInfo($id);
}

function getInfo( $id)
{
	$sql = 'select * 
			from ia_request
			where id = ' . $id . '
			limit 1;';
	$result = mysql_query($sql);
	$array = mysql_fetch_array($result);
	if(mysql_num_rows($result) == 0)
		return false;
	else
	{
		$this->id = $id;
		$this->username = $array['username'];
		$this->reason = $array['reason'];
		$this->time = $array['time'];
		return true;
	}

}

};
?>
