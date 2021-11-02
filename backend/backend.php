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
			//throw exception with the shit
			if($stmt->errorCode() != "00000")
			{
				$error = $stmt->errorInfo();
				throw new Exception($error[2]);
			}
		}
		else
		{
			$stmt = $db->prepare($query);
			$stmt->execute();
			//throw exception with the shit
			if($stmt->errorCode() != "00000")
			{
				$error = $stmt->errorInfo();
				throw new Exception($error[2]);
			}
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

		//prepare database variables
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
				//update the cookie in the database
				$stmt = "UPDATE `Users` SET cookie = :cookie WHERE email = :email";
				$params = array(":cookie"=> $response["cookie"],
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

function show_posts($cookie){
	$response = array();
	try
	{
		$stmt = "SELECT cookie from `Users` where cookie = :cookie LIMIT 1";
		$params = array(":cookie"=> $cookie);
		$result = send_sql_query_to_databse(true,$stmt,$params);
		if(!empty($result))
		{
			$response["success"] = true;
			//selects new posts
			$stmt = "SELECT post_id, fname, lname, title, message from `Forum`";
			$params = array();
			$result = send_sql_query_to_databse(false,$stmt,$params);
			$response["posts"] = $result;
			echo var_dump($response);
			return $response;
		}
		else
		{
			$response["success"] = false;
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
function create_post($cookie,$title,$message){
	$response = array();
	try
	{
		//select fname lname from users where cookie = cookie
		$stmt = "SELECT fname, lname from `Users` where cookie = :cookie LIMIT 1";
		$params = array(":cookie"=> $cookie);
		$result = send_sql_query_to_databse(true,$stmt,$params);
		if(!empty($result))
		{
			$fname = $result[0]['fname'];
			$lname = $result[0]['lname'];
			$post_id = uniqid();
			$message_id = 1;
			$stmt = "INSERT INTO `Forum`
						(fname, lname, post_id, message_id, title, message) VALUES
						(:fname, :lname, :post_id, :message_id, :title, :message)";
			$params = array(":fname"=> $fname,
							":lname"=> $lname,
							":post_id"=> $post_id,
							":message_id"=> $message_id,
							":title"=> $title,
							":message"=> $message);
			send_sql_query_to_databse(true,$stmt,$params);
			$response["success"] = true;
			return $response;
		}
		else
		{
			$response["success"] = false;
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
function create_reply($cookie,$post_id,$reply_message){
	$response = array();
	try
	{
		//grabs the latest message id
		$stmt = "SELECT message_id from `Forum` where post_id = :post_id ORDER BY message_id desc LIMIT 1";
		$params = array(":post_id"=> $post_id);
		$result1 = send_sql_query_to_databse(true,$stmt,$params);
		if ($result1 == false)
		{
			$response["success"] = false;
			return $response;
		}
		$new_message_id = (int)$result1[0]['message_id'] + 1;
		//grabs the user's fname and lname who replied
		$stmt = "SELECT fname, lname from `Users` where cookie = :cookie LIMIT 1";
		$params = array(":cookie"=> $cookie);
		$result = send_sql_query_to_databse(true,$stmt,$params);
		$fname = $result[0]['fname'];
		$lname = $result[0]['lname'];
		if(!empty($result))
		{
			$fname = $result[0]['fname'];
			$lname = $result[0]['lname'];
			$new_post_id = uniqid();
			$message_id = 1;
			$stmt = "INSERT INTO `Forum`
						(fname, lname, post_id, title, message_id, message) VALUES
						(:fname, :lname, :post_id, :title, :message_id, :message)";
			$params = array(":fname"=> $fname,
							":lname"=> $lname,
							":post_id"=> $new_post_id,
							":title"=> "reply to " . $post_id,
							":message_id"=> $message_id,
							":message"=> $reply_message);
			send_sql_query_to_databse(true,$stmt,$params);
			$response["success"] = true;
			return $response;
		}
		else
		{
			$response["success"] = false;
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
function request_processor($req){
	echo var_dump($req);
	try{
	//Handle message type
	$type = $req['type'];
	switch($type){
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
