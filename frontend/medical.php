<?php
//check if cookie is set
if(!isset($_COOKIE['id'])){
    header("/login.php");
    exit();
}

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

//need to create medical.html for vet search page - Joshua
?>




<!DOCTYPE html>
<html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Homepage</title>
  <link rel="icon" href="https://cloudfront-us-east-1.images.arcpublishing.com/coindesk/XA6KIXE6FBFM5EWSA25JI5YAU4.jpg"/>
  <link rel="stylesheet" href="./style.css">

</head>
<body>
<!-- partial:index.partial.html -->
<html>
  <head>
    <title>Parallax Template - uplusion23</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-smooth-scroll/1.5.5/jquery.smooth-scroll.min.js"></script>
  </head>
  <body>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    <div class="navbar">
      <ul class="navbar-container">
        <li><a href="index.html" class="left-underline nav-button brand-logo">Pet Adoption Service</a></li>
	<li class="nav-item active"><a href="login.html" class="left-underline nav-button" data-scroll>Account</a></li>
        <li class="nav-item"><a href="#section-3" class="left-underline nav-button" data-scroll>Wallet</a></li>
        <li class="nav-item"><a href="#section-2" class="left-underline nav-button" data-scroll>Services</a></li>
	<li class="nav-item active"><a href="forum.html" class="left-underline nav-button" data-scroll>Forums</a></li>
	<li class="nav-item active"><a href="medical.html" class="left-underline nav-button" data-scroll>Medical Info</a></li>
	      </body>
</html>
