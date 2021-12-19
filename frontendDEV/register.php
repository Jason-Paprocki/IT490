<?php
	require_once('rabbit/path.inc');
	require_once('rabbit/get_host_info.inc');
	require_once('rabbit/rabbitMQLib.inc');
	function send_error($error){
		$client = new rabbitMQClient("errorReporting.ini","errorReporting");
		$request = array();
		$request['type'] = "Error";
		$request['page'] = "register";
		$request['message'] = $error;
		$response = $client->publish($request);
		exit("sent error");
		}
		try{
	if(isset($_POST['fname'])
	&& isset($_POST['lname'])
	&& isset($_POST['email'])
	&& isset($_POST['pword'])
	&& isset($_POST['conf_pword'])
	)
	{
		$fname = $_POST["fname"];
		$lname = $_POST["lname"];
		$email = $_POST["email"];
		$passwd = $_POST["pword"];
		$conf_pword = $_POST["conf_pword"];

		//confirm that regular password is the same as the confirmation password
		if($passwd != $conf_pword)
		{
			//echo javascript alert containing php response message
			?>
			<script type="text/javascript">
				//alert with response message
				alert("Passwords do not match");
				window.location.href = "/register.php";
			</script>
			<?php
		}

		//send the frontend shit over to the backend
		$client = new rabbitMQClient("testRabbitMQ.ini","frontbackcomms");
		$request = array();
		$request['type'] = "register";
		$request['email'] = $email;
		$request['password'] = $passwd;
		$request['lname'] = $lname;
		$request['fname'] = $fname;
		$response = $client->send_request($request);

		//verify that the response is a success from the backend
		if($response["success"])
		{
			$js_cookie = "id=" . $response["cookie"];
			//is this the best way to do this?
			//cookie needs to have exp date and shit
			//thing is we need to parse that
			?>
            <script type="text/JavaScript">
                //set cookie
                //generate date 1 hour in the future
                var date = new Date();
                date.setTime(date.getTime() + (1*60*60*1000));
                document.cookie = "<?php echo $js_cookie; ?>; expires=" + date.toGMTString() + ";path=/";
				window.location.replace("/account.php");
            </script>
			<?php
			//check in account if there is a redirect and be like "hello user name or whatever"
			exit();
		}
		else
		{
			//echo javascript alert containing php response message
			?>
			<script type="text/javascript">
				//alert with response message
				alert("<?php echo $response["msg"]; ?>");
				window.location.href = "/register.php";
			</script>
			<?php
		}
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
