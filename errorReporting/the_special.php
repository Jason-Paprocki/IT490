#!/usr/bin/php
<?php
	require_once('path.inc');
	require_once('get_host_info.inc');
	require_once('rabbitMQLib.inc');
	try
	{
		throw new Exception('XDDDDDDDDDDDDDDDDDDDDDDDDD');
	}
		//sends error message to every machine
		catch(Exception $e){
		//$e = new Exception('Invalid username or password');
		echo $e->getMessage();
		echo "\n";
		
		$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
		$request = array();
		$request['type'] = "Error";
		$msg = strval($e->getMessage());
		$request['message'] = $msg;
		//$response = $client->send_request($request);
		$response = $client->publish($request);
		exit("It didn't work\nsent error");
	}

