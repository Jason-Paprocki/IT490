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
$passfail = $argv[4];


//set passfail to deploypass or fail for request type
if($passfail == "pass")
{
    $type = "deploy_pass";
}
else if($passfail == "fail")
{
    $type = "deploy_fail";
}
else
{
    echo "invalid passfail";
    exit();
}

//send this to deploy server 
$client = new rabbitMQClient("testRabbitMQ.ini","frontbackcomms");
$request = array();

$request['type'] = $type;
$request['package'] = $entry_file;
$request['version'] = $version_number;
$request['target'] = $target;
$request['passfail'] = $passfail;

$response = $client->publish($request);

?>