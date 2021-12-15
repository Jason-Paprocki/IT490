#!/usr/bin/php
<?php

require_once('rabbit/path.inc');
require_once('rabbit/get_host_info.inc');
require_once('rabbit/rabbitMQLib.inc');

//send error with rabbit
function send_error($error){
	$client = new rabbitMQClient("errorReporting.ini","errorReporting");
	$request = array();
	$request['type'] = "Error";
	$request['message'] = $error;
	$response = $client->publish($request);
	exit("sent error");
}

function send_sql_query_to_databse($has_params,$query,$query_args){
	try{
		require("config.php");
		$connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
		$db = new PDO($connection_string, $dbuser, $dbpass);
		if($has_params)
		{
			$stmt = $db->prepare($query);
			$stmt->execute($query_args);
		}
		else
		{
			$stmt = $db->prepare($query);
			$stmt->execute();
		}
		$result = $stmt->fetchAll();
		//if result is empty, return false
		if(empty($result))
		{
			return false;
		}
		else
		{
			return $result;
		}
	}
	catch(Exception $e){
		//echo the error out to stdout
		echo $e->getMessage();
		//send the error
		send_error(strval($e->getMessage()));
		exit("send error\n");
	}	
}



function request_processor($req){
	echo var_dump($req);
	try{
	//Handle message type
	$type = $req['type'];
	switch($type){
		case "deploy":
			return deploy($req['package'], $req['version']);
		}
	}
	catch(Exception $e){
		//echo the error out to stdout
		echo $e->getMessage();
		//send the error
		send_error(strval($e->getMessage()));
		exit("send error\n");
	}
}

$server = new rabbitMQServer("testRabbitMQ.ini", "frontbackcomms");

echo "Rabbit MQ Server Start" . PHP_EOL;
$server->process_requests('request_processor');
echo "Rabbit MQ Server Stop" . PHP_EOL;
exit();
?>