<?php
    ini_set('display_errors',1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
	session_start();
	if(
        isset($_POST["email"])
	&& isset($_POST["pword"])
	)

	{
        $email = $_POST["email"];
        $passwd = $_POST["pword"];

        require_once('path.inc');
        require_once('get_host_info.inc');
        require_once('rabbitMQLib.inc');

        $client = new rabbitMQClient("testRabbitMQ.ini","frontbackcomms");
        $request = array();
        $request['type'] = "login";
        $request['email'] = $email;
        $request['password'] = $passwd;
        $response = $client->send_request($request);
        //$response = $client->publish($request);

            if($$response["success"])
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
                exit();
            }
            else
            {
                echo "<script type='text/javascript'>alert('You are a failure');</script>";
                exit();
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
		<li class="nav-item active"><a href="login.html" class="left-underline nav-button" data-scroll>Account</a></li>
        <li class="nav-item"><a href="#section-3" class="left-underline nav-button" data-scroll>Wallet</a></li>
        <li class="nav-item"><a href="#section-2" class="left-underline nav-button" data-scroll>Services</a></li>
		<li class="nav-item active"><a href="forum.html" class="left-underline nav-button" data-scroll>Forums</a></li>
      </ul>
    </div>

<div class="parallax p1" id="section-1">
      <hgroup>
        <h1>Login</h1>
          <form name="regform" id="myForm" method="POST">
              <label for="email">Email:</label><br>
              <input type="text" id="email" name="email"><br><br>
              <label for="lname">Password:</label><br>
              <input type="password" id="pword" name="pword"><br><br>
              <a href="register.php">Don't have an account? Create an account here</a><br><br>
              <input type="submit" value="Login"><br>
          </form>
      </hgroup>
    </div>
</body>
</html>