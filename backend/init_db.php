#!/usr/bin/php
<?php
//`sudo apt-get install php*-mysql` 
//sudo service apache2 restart
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



try{
    $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
    //CREATE THE PET_SERVICE DATABASE TO STORE THE DATA IN
	$db = new PDO($connection_string, $dbuser, $dbpass);
	echo "Connected to create database\n";
	$stmt = $db->prepare("CREATE DATABASE IF NOT EXISTS Pet_Service");
	$stmt->execute() or throw new Exception(print_r($stmt->errorInfo(), true));
    //create users table
	echo "Created to create Users table\n";
	$stmt = $db->prepare(file_get_contents("sql_files/users.sql"));
	$stmt->execute() or throw new Exception(print_r($stmt->errorInfo(), true));
    //create forum table
    echo "Created to create Forum table\n";
	$stmt = $db->prepare("create table if not exists `Forum` (
                `fname` varchar(20) not null,
                `lname` varchar(20) not null,
                `post_id` varchar(13) not NULL,
                `message_id` varchar(13),
                `title` varchar(100) not null,
                `message` varchar(1000) not null,
				PRIMARY KEY (`post_id`)
				) CHARACTER SET utf8 COLLATE utf8_general_ci"
			);
    $stmt->execute() or throw new Exception(print_r($stmt->errorInfo(), true));
}
catch(Exception $e){
    //echo the error out to stdout
    echo $e->getMessage();
    //send the error
    send_error(strval($e->getMessage()));
    exit("send error\n");
}
?>
