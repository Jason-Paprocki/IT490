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
		
		$client = new rabbitMQClient("errorReporting.ini","errorReporting");
		$request = array();
		$request['machine'] = "broker";
		$msg = strval($e->getMessage());
		$request['message'] = $msg;
		date_default_timezone_set("America/New_York");
        $request["time"] = date("h:i:sa");
		$response = $client->publish($request);
		exit("sent error");
	}

