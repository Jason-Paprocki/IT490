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

try{
    $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
    //CREATE THE PET_SERVICE DATABASE TO STORE THE DATA IN
	$db = new PDO($connection_string, $dbuser, $dbpass);
	echo "Connected to database\n";
    
    //create users table
	echo "Created to create Users table\n";
	$stmt = $db->prepare(file_get_contents("sql_files/users.sql"));
	$stmt->execute();
    //throw exception with the shit
    if($stmt->errorCode() != "00000")
    {
        $error = $stmt->errorInfo();
        throw new Exception($error[2]);
    }
    
    //create forum table
    echo "Created to create Forum table\n";
	$stmt = $db->prepare(file_get_contents("sql_files/forum.sql"));
    $stmt->execute();
    //throw exception with the shit
    if($stmt->errorCode() != "00000")
    {
        $error = $stmt->errorInfo();
        throw new Exception($error[2]);
    }
}
catch(Exception $e){
    //echo the error out to stdout
    echo $e->getMessage();
    //send the error
    send_error(strval($e->getMessage()));
    exit("send error\n");
}
?>
