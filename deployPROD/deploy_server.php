#!/usr/bin/php
<?php

require_once('/home/backend/Music/deploy/rabbit/path.inc');
require_once('/home/backend/Music/deploy/rabbit/get_host_info.inc');
require_once('/home/backend/Music/deploy/rabbit/rabbitMQLib.inc');

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
			//print errors
			$error = $stmt->errorInfo();
		}
		else
		{
			$stmt = $db->prepare($query);
			$stmt->execute();
			//print error
			$error = $stmt->errorInfo();
		}
        if($error[0] != "00000")
        {
            echo "error: " . $error[2] . "\n";
            send_error(strval($error[2]));
            exit("send error\n");
        }
		$result = $stmt->fetchAll();
        return $result;
	}
	catch(Exception $e){
		//echo the error out to stdout
		echo $e->getMessage();
		//send the error
		send_error(strval($e->getMessage()));
		exit("send error\n");
	}	
}

function deploy($package, $version, $target)
{
	switch($target){

		case "frontend":
			$id = uniqid();
			$packageWithVersion = $package . $version;

			$pathtosql = "/home/backend/Desktop/frontend/" . $packageWithVersion;
			
			$command = "cp /home/backend/Desktop/frontend/" . $package . " /home/backend/Desktop/frontend/" . $packageWithVersion;
			shell_exec($command);

			$passfail = NULL;
			//prepare statement to inset package and version into fronteend table
			$stmt = "INSERT INTO `frontend` (`id`, `package`, `version`, `passfail`, `path`) VALUES (:id, :package, :version, :passfail, :path)";
			$params = array(":id" => $id, ":package" => $package, ":version" => $version, ":passfail" => $passfail, ":path" => $pathtosql);
			//send to database
			$result = send_sql_query_to_databse(true,$stmt,$params);

			//scp package over
			$scp_command = "scp /home/backend/Desktop/frontend/" . $package . " frontend@172.28.226.7:/var/www/html/";
			$scp_result = shell_exec($scp_command);
			return;


		case "backend":
			$id = uniqid();
			$packageWithVersion = $package . $version;

			$pathtosql = "/home/backend/Desktop/backend/" . $packageWithVersion;
			
			$command = "cp /home/backend/Desktop/backend/" . $package . " /home/backend/Desktop/backend/" . $packageWithVersion;
			shell_exec($command);

			$passfail = NULL;
			//prepare statement to inset package and version into fronteend table
			$stmt = "INSERT INTO `backend` (`id`, `package`, `version`, `passfail`, `path`) VALUES (:id, :package, :version, :passfail, :path)";
			$params = array(":id" => $id, ":package" => $package, ":version" => $version, ":passfail" => $passfail, ":path" => $pathtosql);
			//send to database
			$result = send_sql_query_to_databse(true,$stmt,$params);

			//scp package over
			$scp_command = "scp /home/backend/Desktop/backend/" . $package . " backend@172.28.189.213:/home/backend/Music/backend/";
			$scp_result = shell_exec($scp_command);
			return;
		
	}
}
function deploy_pass($package, $version, $target)
{
	//takes the package and version
	//marks the package as passed QA
	//scp over to production
	switch($target){

		case "frontend":
            //set the passfail to passed 
			$stmt = "UPDATE `frontend` SET `passfail` = 'passed' WHERE `package` = :package AND `version` = :version";
			$params = array(":package" => $package, ":version" => $version);
			//send to database
			$result = send_sql_query_to_databse(true,$stmt,$params);
			
            //select the path from the frontend table with matching packages and version
			$stmt = "SELECT `path` FROM `frontend` WHERE `package` = :package AND `version` = :version";
			$params = array(":package" => $package, ":version" => $version);
			//send to database
			$result = send_sql_query_to_databse(true,$stmt,$params);
			//get the path
			$path = $result[0]['path'];
			//package is path to the cp file
			$package = substr($path, 0, -1);

			//cp contents of package to a file with the same name but remove last character from the path
			$command = "cp " . $path . " " . $package;
			shell_exec($command);

			//scp over to production
			$scp_command = "scp " . $package . " frontend@172.28.21.35:/var/www/html/";
			$scp_result = shell_exec($scp_command);
			return;


		case "backend":
            //set the passfail to passed 
            $stmt = "UPDATE `backend` SET `passfail` = 'passed' WHERE `package` = :package AND `version` = :version";
            $params = array(":package" => $package, ":version" => $version);
            //send to database
            $result = send_sql_query_to_databse(true,$stmt,$params);
            
            //select the path from the backend table with matching packages and version
            $stmt = "SELECT `path` FROM `backend` WHERE `package` = :package AND `version` = :version";
            $params = array(":package" => $package, ":version" => $version);
            //send to database
            $result = send_sql_query_to_databse(true,$stmt,$params);
            //get the path
            $path = $result[0]['path'];
            //package is path to the cp file
            $package = substr($path, 0, -1);

            //cp contents of package to a file with the same name but remove last character from the path
            $command = "cp " . $path . " " . $package;
            shell_exec($command);

			//scp over to production
			$scp_command = "scp " . $package . " backend@172.28.26.112:/home/backend/Music/backend/";
			$scp_result = shell_exec($scp_command);
			return;
	}
}

function deploy_fail($package, $version, $target)
{
	switch($target){
		case "frontend":
			//set the passfail to failed
			$stmt = "UPDATE `frontend` SET `passfail` = 'failed' WHERE `package` = :package AND `version` = :version";
			$params = array(":package" => $package, ":version" => $version);
			//send to database
			$result = send_sql_query_to_databse(true,$stmt,$params);
			return;

		case "backend":
			//set the passfail to failed
			$stmt = "UPDATE `backend` SET `passfail` = 'failed' WHERE `package` = :package AND `version` = :version";
			$params = array(":package" => $package, ":version" => $version);
			//send to database
			$result = send_sql_query_to_databse(true,$stmt,$params);
			return;
		}
}

function rollback($package, $version, $target)
{
    switch($target){

		case "frontend":
            //select the path from the frontend table with matching packages and version
			$stmt = "SELECT `path` FROM `frontend` WHERE `package` = :package AND `version` = :version";
			$params = array(":package" => $package, ":version" => $version);
			//send to database
			$result = send_sql_query_to_databse(true,$stmt,$params);
			//get the path
			$path = $result[0]['path'];
			//package is path to the cp file
			$package = substr($path, 0, -1);

			//cp contents of package to a file with the same name but remove last character from the path
			$command = "cp " . $path . " " . $package;
			shell_exec($command);
			//scp package over
			$scp_command = "scp " . $package . " frontend@172.28.226.7:/var/www/html/";
			$scp_result = shell_exec($scp_command);
			return;

		case "backend":
            //select the path from the frontend table with matching packages and version
			$stmt = "SELECT `path` FROM `frontend` WHERE `package` = :package AND `version` = :version";
			$params = array(":package" => $package, ":version" => $version);
			//send to database
			$result = send_sql_query_to_databse(true,$stmt,$params);
			//get the path
			$path = $result[0]['path'];
			//package is path to the cp file
			$package = substr($path, 0, -1);

			//cp contents of package to a file with the same name but remove last character from the path
			$command = "cp " . $path . " " . $package;
			shell_exec($command);
			//scp package over
			$scp_command = "scp " . $package . " backend@172.28.189.213:/home/backend/Music/backend/";
			$scp_result = shell_exec($scp_command);
			return;
		
	}
}

function request_processor($req){
	echo var_dump($req);
	try{
	//Handle message type
	$type = $req['type'];
	switch($type){
		case "deploy":
			return deploy($req['package'], $req['version'], $req['target']);
		
		case "deploy_pass":
			return deploy_pass($req['package'], $req['version'], $req['target']);
		
		case "deploy_fail":
			return deploy_fail($req['package'], $req['version'], $req['target']);
		case "rollback":
			return rollback($req['package'], $req['version'], $req['target']);
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

$server = new rabbitMQServer("deployMQ.ini","deployment");

echo "Rabbit MQ Server Start" . PHP_EOL;
$server->process_requests('request_processor');
echo "Rabbit MQ Server Stop" . PHP_EOL;
exit();
?>