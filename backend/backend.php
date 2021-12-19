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
		
		//create some cool id
		$id = md5(uniqid(rand(), true));
		
		//hash the password using sha256
		$pass = hash("sha256",$pass);

		//added login time
		$login_time = date("Y-m-d H:i:s");

		//prepare database variables
		$stmt = "INSERT INTO `Users`
					(id, email, cookie, password, fname, lname, loginTime) VALUES
					(:id, :email, :cookie, :password, :fname, :lname, :loginTime)";
		$params = array(":id" => $id,
						":email"=> $email, 
						":cookie"=> $response["cookie"],
						":password"=> $pass,
						":fname"=> $fname,
						":lname"=> $lname,
						":loginTime"=> $login_time);
		//send to database
		send_sql_query_to_databse(true,$stmt,$params);
		//make the response true and send to frontend
		$response["success"] = true;
		//return the response to the server
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


function login($email,$pass){
	$response = array();
	try
	{
		$stmt = "SELECT email, password from `Users` where email = :email LIMIT 1";
		$params = array(":email"=> $email);
		$result = send_sql_query_to_databse(true,$stmt,$params);
		if($result != false)
		{
			//grab the user's password
			$userpassword = $result[0]['password'];
			//verifies the hashed password with the one that the user provides
			if(password_verify($pass, $userpassword))
			{
				//give the user a cookie
				$response["cookie"] = uniqid();
				//set login time
				$login_time = date("Y-m-d H:i:s");
				//update the cookie in the database
				$stmt = "UPDATE `Users` SET cookie = :cookie, loginTime = :loginTime WHERE email = :email";
				$params = array(":cookie"=> $response["cookie"],
								":loginTime"=> $login_time,
								":email"=> $email);
				send_sql_query_to_databse(true,$stmt,$params);
				//make the response true and send to frontend
				$response["success"] = true;
				return $response;
			}
		}
		else
		{
			$response["success"] = false;
			$response["msg"] = "Invalid Username or Password";
			return $response;
		}
	}
	catch(Exception $e){
		//echo the error out to stdout
		echo $e->getMessage();
		//send the error
		send_error(strval($e->getMessage()));
		$response["success"] = false;
		return $response;
		exit("send error\n");
	}
}

function validate($cookie){
	$response = array();
	try
	{
		$stmt = "SELECT cookie from `Users` where cookie = :cookie LIMIT 1";
		$params = array(":cookie"=> $cookie);
		$result = send_sql_query_to_databse(true,$stmt,$params);
		if(!empty($result))
		{
			$response["success"] = true;
			return $response;
		}
		else
		{
			$response["success"] = false;
			$response["msg"] = "Invalid Cookie";
			return $response;
		}
	}
	catch(Exception $e){
		//echo the error out to stdout
		echo $e->getMessage();
		//send the error
		send_error(strval($e->getMessage()));
		$response["success"] = false;
		return $response;
		exit("send error\n");
	}
}
function load_posts()
{
	$response = array();
	try
	{
		$stmt = "SELECT fname, lname, date, message from `Forum` where distinct post_id and message_id = 1";
		$params = array();
		$result = send_sql_query_to_databse(false,$stmt,$params);
		if(!empty($result))
		{
			$response["success"] = true;
			$response["posts"] = $result;
			return $response;
		}
		else
		{
			$response["success"] = false;
			$response["posts"] = "No Posts";
			return $response;
		}
	}
	catch(Exception $e){
		//echo the error out to stdout
		echo $e->getMessage();
		//send the error
		send_error(strval($e->getMessage()));
		$response["success"] = false;
		return $response;
		exit("send error\n");
	}
}
function show_time($cookie)
{
	//select login time from database where cookie = cookie
	$stmt = "SELECT loginTime from `Users` where cookie = :cookie LIMIT 1";
	$params = array(":cookie"=> $cookie);
	$result = send_sql_query_to_databse(true,$stmt,$params);
	if(!empty($result))
	{
		//grab the login time
		$login_time = $result[0]['loginTime'];
		//return the login time
		$response["success"] = true;
		$response["loginTime"] = $login_time;
		return $response;
	}
	else
	{
		return false;
	}
}
function request_processor($req)
{
	echo var_dump($req);
	try
	{
		//Handle message type
		$type = $req['type'];
		switch($type)
		{
			case "login":
				return login($req['email'], $req['password']);
			case "register":
				return register($req['email'], $req['password'],$req['fname'],$req['lname']);
			case "show_posts":
				return show_posts($req['session_id']);
			case "create_post":
				return create_post($req['session_id'],$req['title'],$req['message']);
			case "create_reply":
				return create_reply($req['session_id'],$req['post_id'],$req['reply_message']);
			case "insert":
				return insert($req['pname'],$req['species'],$req['age'],$req['pic'],$req['zip']);
			case "match":
				return match($req['pname'],$req['species'],$req['age'],$req['pic'],$req['zip']);
			case "show_time":
				return show_time($req['session_id']);
		}
	}
	catch(Exception $e)
	{
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