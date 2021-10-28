<?php
	require_once('path.inc');
	require_once('get_host_info.inc');
	require_once('rabbitMQLib.inc');
	session_start();
	if(	   
		isset($_POST["fname"])
	&& isset($_POST["lname"])
	&& isset($_POST["email"])
	&& isset($_POST["pword"])
	&& isset($_POST["conf_passwd"])
	)
	{
		$fname = $_POST["fname"];
		$lname = $_POST["lname"];
		$email = $_POST["email"];
		$passwd = $_POST["pword"];
		$conf_passwd = $_POST["conf_pword"];

		//confirm that regular password is the same as the confirmation password
		if($passwd != $conf_passwd)
		{
			echo "<script type='text/javascript'>alert('Passwords do not match');</script>";
			exit();
		}

		//there are some dog ass files that need to be sent in order to make this work
		$client = new rabbitMQClient("testRabbitMQ.ini","frontbackcomms");
		$request = array();
		$request['type'] = "register";
		$request['email'] = $email;
		$request['password'] = $passwd;
		$request['lname'] = $lname;
		$request['fname'] = $fname;
		$response = $client->send_request($request);
		//$response = $client->publish($request);

		if($response["success"])
		{
			$js_cookie = "id=" . $response["cookie"];
			//is this the best way to do this?
			//cookie needs to have exp date and shit
			//thing is we need to parse that
			?>
				<script type="text/JavaScript">
				function deleteAllCookies() 
				{
					var cookies = document.cookie.split(";");

					for (var i = 0; i < cookies.length; i++) 
					{
						var cookie = cookies[i];
						var eqPos = cookie.indexOf("=");
						var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
						document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:01 GMT";
					}
				}
				deleteAllCookies();
				//super scuffed way to create an expiration date
				//make better?
				const d = new Date();
				d.setTime(d.getTime() + (24 * 60 * 60 * 1000));
				let expires = ";expires="+d.toUTCString();
				//set the cookie in js
				document.cookie = "<?php echo $js_cookie?>" + expires; 
				</script>
			<?php
			//make the header go to the account page
			header('Location: /account.php');
			//check in account if there is a redirect and be like "hello user name or whatever"
			exit();
		}
		else
		{
			//tbh idk what this is
			//prob need to check up on this
			echo $response["msg"];
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
		  <label for="email">Email:</label><br>
		  <input type="text" id="email" name="email"><br><br>
		  <label for="pword">Password:</label><br>
		  <input type="password" id="pword" name="pword"><br><br>
		  <label for="conf_pword">Confirm Password:</label><br>
		  <input type="password" id="conf_pword" name="conf_pword"><br><br>
		  <input type="submit" value="Create Account"><br>
		</form>
      </hgroup>
    </div>

</body>
</html>
