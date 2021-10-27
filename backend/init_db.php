
<?php

require("config.php");
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
echo "DBUser: " .  $dbuser;

$connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
//CREATE THE PET_SERVICE DATABASE TO STORE THE DATA IN
try{
	$db = new PDO($connection_string, $dbuser, $dbpass);
	echo "Connected to create database\n";
	$stmt = $db->prepare("CREATE DATABASE [IF NOT EXISTS] Pet_Service");
	$stmt->execute();
	echo var_export($stmt->errorInfo(), true);
}
catch(Exception $e){
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

//CREATE THE USERS TABLE
try{
	$db = new PDO($connection_string, $dbuser, $dbpass);
	echo "Created to create Users table\n";
	$stmt = $db->prepare("create table if not exists `Users` (
				`id` int auto_increment not null,
				`email` varchar(100) not null unique,
                `password` varchar(100) not null,
                `fname` varchar(20) not null,
                `lname` varchar(20) not null,
				PRIMARY KEY (`id`)
				) CHARACTER SET utf8 COLLATE utf8_general_ci"
			);
	$stmt->execute();
	echo var_export($stmt->errorInfo(), true);
}
catch(Exception $e){
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
//PROB GUNNA NEED A PAYMENT DATABASE, ANIMAL DATABASE, 
?>
