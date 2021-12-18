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
$target = $argv[3];

if ($target == "backend")
{
	$target = "backend";
}
else if ($target == "frontend")
{
	$target = "frontend";
}
else
{
	echo "invalid target";
	exit();
}

$scp_command = "scp " . $entry_file . " backend@172.28.189.213:/home/backend/Desktop/" . $target . "/";
$scp_result = shell_exec($scp_command);

//check if scp_result has no errors
if(strpos($scp_result,"No such file or directory") !== false)
{
	send_error("scp failed");
	exit("scp failed\n");
}
//else then scp worked
else
{
	print("scp worked\n");
}

$client = new rabbitMQClient("deployMQ.ini","deployment");
$request = array();

$request['type'] = "deploy";
$request['package'] = $entry_file;
$request['version'] = $version_number;
$request['target'] = $target;

$response = $client->publish($request);

?>