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
	$request['message'] = $error;
	$response = $client->publish($request);
	exit("sent error");
}

$connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
//CREATE THE PET_SERVICE DATABASE TO STORE THE DATA IN
try{
	$db = new PDO($connection_string, $dbuser, $dbpass);
	echo "Connected to create database\n";
	$stmt = $db->prepare("CREATE DATABASE IF NOT EXISTS Pet_Service");
	$stmt->execute();
	echo var_export($stmt->errorInfo(), true);
}
catch(Exception $e){
    //echo the error out to stdout
    echo $e->getMessage();
    //send the error
    send_error(strval($e->getMessage()));
    exit("send error\n");
}

//CREATE THE USERS TABLE
try{
	$db = new PDO($connection_string, $dbuser, $dbpass);
	echo "Created to create Users table\n";
	$stmt = $db->prepare("create table if not exists `Users` (
				`id` varchar(32) not null,
				`email` varchar(100) not null unique,
                `cookie` varchar(13),
                `password` varchar(100) not null,
                `fname` varchar(20) not null,
                `lname` varchar(20) not null,
				PRIMARY KEY (`id`)
				) CHARACTER SET utf8 COLLATE utf8_general_ci"
			);
	$stmt->execute();
	echo var_export($stmt->errorInfo(), true);
}
//sends error message to every machine
catch(Exception $e){
    //echo the error out to stdout
    echo $e->getMessage();
    //send the error
    send_error(strval($e->getMessage()));
    exit("send error\n");
}
//PROB GUNNA NEED A PAYMENT DATABASE, ANIMAL DATABASE, 
//CREATE THE USERS TABLE
try{
	$db = new PDO($connection_string, $dbuser, $dbpass);
	echo "Create Forum table\n";
	$stmt = $db->prepare("create table if not exists `Forum` (
				`id` varchar(32) not null,
                `fname` varchar(20) not null,
                `lname` varchar(20) not null,
                `post_id` varchar(32) not null,
                `message_id` varchar(32) not null,
                `message` varchar(1000) not null,
                `date` varchar(20) not null,
				PRIMARY KEY (`id`)
				) CHARACTER SET utf8 COLLATE utf8_general_ci"
			);
	$stmt->execute();
	echo var_export($stmt->errorInfo(), true);
}
//sends error message to every machine
catch(Exception $e){
    //echo the error out to stdout
    echo $e->getMessage();
    //send the error
    send_error(strval($e->getMessage()));
    exit("send error\n");
}
?>
