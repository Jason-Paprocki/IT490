<?php
//check if cookie is set

//prob going to need a table for medical info
try{
	$db = new PDO($connection_string, $dbuser, $dbpass);
	echo "Created to create Medical Info table\n";
	$stmt = $db->prepare("create table if not exists `Medical` (
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

    //display
//name of customer
//name of pet
//type of pet
// vaccines
//gromming
//vet checkup times
//vet name
//vet phone
//vet address

//will pull this data by checking the id and matching it to the id set in the Users table
//will need to be able to edit this data

//picture of the animal?

//pull display info from database
//set all these variables in php and pass them to the html








//html
//will need to make a table for the medical info
//picture can be small in the corner top right or left
//will need to be able to edit this data -- edit button or something?
//how can we create push notifications? -- send to email or just javascript at login?

?>




<!DOCTYPE html>
<html>
</html>
