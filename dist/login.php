<?php
	require_once('rabbit/path.inc');
	require_once('rabbit/get_host_info.inc');
	require_once('rabbit/rabbitMQLib.inc');
	if(isset($_POST['email'])
	&& isset($_POST['pword'])
	)
	{
        $email = $_POST["email"];
        $passwd = $_POST["pword"];
		//send the frontend shit over to the backend
		$client = new rabbitMQClient("testRabbitMQ.ini","frontbackcomms");
        $request = array();
        $request['type'] = "login";
        $request['email'] = $email;
        $request['password'] = $passwd;
        $response = $client->send_request($request);
        echo var_dump($response["success"]);
        if($response["success"])
        {
            $js_cookie = "id=" . $response["cookie"];
        ?>
            <script type="text/JavaScript">
                //set cookie
                //generate date 1 hour in the future
                var date = new Date();
                date.setTime(date.getTime() + (1*60*60*1000));
                //set cookie with path to root
                document.cookie = "<?php echo $js_cookie; ?>; expires=" + date.toGMTString() + "; path=/";
                window.location.replace("/account.php");
            </script>
        <?php
        }
        else
        {
            ?>
                <script type="text/javascript">
                alert("Failed to login");
                window.location.href = "login.php";
                </script>
            <?php
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" href="./style.css">
	<link rel="icon" href="https://cloudfront-us-east-1.images.arcpublishing.com/coindesk/XA6KIXE6FBFM5EWSA25JI5YAU4.jpg"/>

</head>
<body>
<link href='https://fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    <div class="navbar">
      <ul class="navbar-container">
        <li><a href="index.html" class="left-underline nav-button brand-logo">Pet Adoption Service</a></li>
		<li class="nav-item active"><a href="login.php" class="left-underline nav-button" data-scroll>Account</a></li>
        <li class="nav-item"><a href="#section-3" class="left-underline nav-button" data-scroll>Wallet</a></li>
        <li class="nav-item"><a href="#section-2" class="left-underline nav-button" data-scroll>Services</a></li>
		<li class="nav-item active"><a href="forum.php" class="left-underline nav-button" data-scroll>Forums</a></li>
      </ul>
    </div>

<div class="parallax p1" id="section-1">
      <hgroup>
        <h1>Login</h1>
          <form name="regform" id="myForm" method="POST">
              <label for="email">Email:</label><br>
              <input type="text" id="email" name="email"><br><br>
              <label for="pword">Password:</label><br>
              <input type="password" id="pword" name="pword"><br><br>
              <a href="register.php">Don't have an account? Create an account here</a><br><br>
              <input type="submit" value="Login"><br>
          </form>
      </hgroup>
    </div>
</body>
</html>