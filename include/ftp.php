<?php
class Ftp
{
var $conid;
var $server;
var $username;
var $password;
var $dir;
var $webdir;

function connect()
{
	$this->server = FTP_SERVER;
	$this->username = FTP_USER;
	$this->password = FTP_PASS;
	$this->conid = ftp_connect($this->server);
	ftp_login($this->conid, $this->username, $this->password);
}
function disconnect()
{
	ftp_close($this->conid);
}
function upload($file, $filename)
{
	$this->connect();
	$this->dir = FTP_DIR . $filename;
	$upload = ftp_put($this->conid, $this->dir, $file, FTP_BINARY);
	$ch = ftp_site($this->conid ,"chmod 777 ".$this->dir);
	$this->disconnect();
}

};
?>
