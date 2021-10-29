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

function register($email,$pass,$fname,$lname)
{
	//check if the email is in use already
	$response = array();
    try
    {   //prepare database variables
        $stmt = "SELECT email from `Users` where email = :email";
        $params = array(":email" => $email);
		//send to database
        $result = send_sql_query_to_databse(true,$stmt,$params);
        //if result is false, the email is not in use
		if($result != false)
		{
			$response['error'] = "Email already in use";
			$response['success'] = false;
			return $response;
		}
		//this is a 13 character shitter; alphanumeric
		$response["cookie"] = uniqid();
		//need to hash with salt so idk
		$id = md5(uniqid(rand(), true));
		//hashing password
		$pass = password_hash($pass, PASSWORD_BCRYPT);
		//NOT FINISHED
		//CREATE PROPER SQL STATEMENT
		//REFRENCE INIT_DB.PHP
		$stmt = "INSERT INTO `Users`
					(id, email, cookie, password, fname, lname) VALUES
					(:id, :email, :cookie, :password, :fname, :lname)";
		$params = array(":id" => $id,
						":email"=> $email, 
						":cookie"=> $response["cookie"],
						":password"=> $pass,
						":fname"=> $fname,
						":lname"=> $lname);
		//send to database
		send_sql_query_to_databse(true,$stmt,$params);
		//make the response true and send to frontend
		$response["success"] = true;
		return $response;
		
    }
    catch(Exception $e)
    {
		//echo the error out to stdout
		echo $e->getMessage();
		//send the error
		send_error(strval($e->getMessage()));
		$response["success"] = false;
		return $response;
		exit("send error\n");
	}
		
    
}


function login($user,$pass){
	$response = array();
	try
	{
		$db = new PDO($connection_string, $dbuser, $dbpass);
		$stmt = $db->prepare("SELECT email, password from `Users` where email = :email LIMIT 1");
		$params = array(":email"=> $email);
		$stmt->execute($params);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if($result)
		{
			$userpassword = $result['password'];
			if(password_verify($pass, $userpassword))
			{
				//give the user a cookie
				//this is a 13 character shitter; alphanumeric
				//TODO
				//needs to update the cookie in the sql table
				$response["cookie"] = uniqid();
				return True;
				
			}
		}
		else
		{
			return false;
		}
	}
	catch(Exception $e){
		echo $e->getMessage();
		$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
		$request = array();
		$request['type'] = "Error";
		$request['message'] = strval($e->getMessage());
		//$response = $client->send_request($request);
		$response = $client->publish($request);
	
		echo "sent error".PHP_EOL;
		exit("It didn't work");
	}
}


function request_processor($req){
	echo "Received Request".PHP_EOL;
	echo var_dump($req);
	if(!isset($req['type'])){
		//gotta send this dog ass error out
		return "Error: unsupported message type";
	}
	//Handle message type
	$type = $req['type'];
	switch($type){
		case "login":
			return login($req['email'], $req['password']);
        case "register":
            return register($req['email'], $req['password'],$req['fname'],$req['lname']);
        case "validate_session":
			return validate($req['session_id']);
	}
	return array("return_code" => '0',
		"message" => "Server received request and processed it");
}

$server = new rabbitMQServer("testRabbitMQ.ini", "frontbackcomms");

echo "Rabbit MQ Server Start" . PHP_EOL;
$server->process_requests('request_processor');
echo "Rabbit MQ Server Stop" . PHP_EOL;
exit();
?>