#!/usr/bin/php
<?php
require("config.php");
require_once('rabbit/path.inc');
require_once('rabbit/get_host_info.inc');
require_once('rabbit/rabbitMQLib.inc');

//send error with rabbit
function send_error($error){
	$client = new rabbitMQClient("errorReporting.ini","errorReporting");
	$request = array();
	$request['type'] = "Error";
	$request['machine'] = "backend - init_db.php";
	$request['message'] = $error;
	$response = $client->publish($request);
	exit("sent error");
}

//arg shit
$entry_file = $argv[1];
$version_number = $argv[2];

//zip file
$zip_file = $entry_file.".zip";

//scp zip file to server
$scp_command = "scp ".$zip_file." backend@:/home/backend/Desktop/frontend/";
$scp_result = shell_exec($scp_command);

//check if scp_result has no errors
if(strpos($scp_result,"No such file or directory") !== false)
{
	send_error("scp failed");
	exit("scp failed\n");
}

$client = new rabbitMQClient("testRabbitMQ.ini","frontbackcomms");
$request = array();

$request['type'] = "deploy";
$request['package'] = $zip_file;
$request['version'] = $version_number;

$response = $client->send_request($request);

?>