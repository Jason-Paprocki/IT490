<?php
    ini_set('display_errors',1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
	session_start();
	if(	   
		isset($_POST["fname"])
	&& isset($_POST["lname"])
	&& isset($_POST["uname"])
	&& isset($_POST["pword"])
	)
	{
		$fname = $_POST["fname"];
		$lname = $_POST["lname"];
		$uname = $_POST["uname"];
		$passwd = $_POST["pword"];
		
		require_once('path.inc');
		require_once('get_host_info.inc');
		require_once('rabbitMQLib.inc');

		$client = new rabbitMQClient("testRabbitMQ.ini","frontbackcomms");
		$request = array();
		$request['type'] = "register";
		$request['username'] = $uname;
		$request['password'] = $passwd;
		$request['lname'] = $lname;
		$request['fname'] = $fname;
		$response = $client->send_request($request);
		//$response = $client->publish($request);

		if($$response["success"])
		{
			?>
				<script type="text/JavaScript">
					document.cookie = $response["COCK"]; 
				</script>
			<?php
			//make the header go to the account page
			header('Location: /account.php');
			exit();
		}
		else
		{
			echo "<script type='text/javascript'>alert('You are a failure');</script>";
			exit();
		}

	}
?>

<html>
<head>
	<title>Sign Up</title>
	<link rel="stylesheet" href="./style.css">
	<link rel="icon" href="https://cloudfront-us-east-1.images.arcpublishing.com/coindesk/XA6KIXE6FBFM5EWSA25JI5YAU4.jpg"/>
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
      </ul>
    </div>

<div class="parallax p1" id="section-1">
      <hgroup>
        <h1>Register</h1>
		<form name="regform" id="myForm" method="POST">
		  <label for="fname">First Name:</label><br>
		  <input type="text" id="fname" name="fname"><br><br>
		  <label for="lname">Last Name:</label><br>
		  <input type="text" id="lname" name="lname"><br><br>
		  <label for="uname">User name:</label><br>
		  <input type="text" id="uname" name="uname"><br><br>
		  <label for="pword">Password:</label><br>
		  <input type="password" id="pword" name="pword"><br><br>
		  <input type="submit" value="Create Account"><br>
		</form>
      </hgroup>
    </div>

</body>
</html>
