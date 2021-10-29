<?php

require("config.php");
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";

function send_to_databse(){
	try{
		$db = new PDO($connection_string, $dbuser, $dbpass);
		echo "Connected to create database\n";
		$stmt = $db->prepare("CREATE DATABASE IF NOT EXISTS Pet_Service");
		$stmt->execute();
		echo var_export($stmt->errorInfo(), true);
	}
	catch(Exception $e){
		echo $e->getMessage();
		$client = new rabbitMQClient("testRabbitMQ.ini","frontbackcomms");
		$request = array();
		$request['type'] = "Error";
		$request['message'] = strval($e->getMessage());
		//$response = $client->send_request($request);
		$response = $client->publish($request);
		exit("sent error");
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
function register($email,$pass,$fname,$lname){
	//check if the email is in use already
	$response = array();
    try
    {        
        $stmt = $db->prepare("SELECT email from `Users` where email = :email ");
        $params = array(":email"=> $email);
        $stmt->execute($params);
        $result = $stmt->fetchAll();

		if ($result[0]["email"] != NULL)
		{
			$response["success"] = FALSE;
			$response["msg"] = "Email already in use"
			exit();
		}
		else
		{
			//this is a 13 character shitter; alphanumeric
			$response["cookie"] = uniqid();
			//need to hash with salt so idk
			$id = md5(uniqid(rand(), true));
			//hashing password
			$pass = password_hash($pass, PASSWORD_BCRYPT);
			//NOT FINISHED
			//CREATE PROPER SQL STATEMENT
			//REFRENCE INIT_DB.PHP
			$stmt = $db->prepare("INSERT INTO `Users`
                        (id, email, cookie, password, fname, lname) VALUES
                        (:id, :email, :cookie, :password, :fname, :lname)");
			$params = array(":id" => $id,
							":email"=> $email, 
							":cookie"=> $response["cookie"],
							":password"=> $pass,
							":fname"=> $fname,
							":lname"=> $lname);
			$stmt->execute($params);
			$response["success"] = true;
			return $response;
		}	














    }
    catch(Exception $e)
    {
		echo $e->getMessage();
		$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
		$request = array();
		$request['type'] = "Error";
		$request['message'] = $e;
		//$response = $client->send_request($request);
		$response = $client->publish($request);
	
		echo "sent error".PHP_EOL;
		exit("It didn't work");
    }
}

function request_processor($req){
	echo "Received Request".PHP_EOL;
	echo "<pre>" . var_dump($req) . "</pre>";
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