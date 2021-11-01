<?php
require_once('rabbit/path.inc');
require_once('rabbit/get_host_info.inc');
require_once('rabbit/rabbitMQLib.inc');
session_start();
if(
    isset($_POST["fname"])
    && isset($_POST["lname"])
    && isset($_POST["email"])
    && isset($_POST["pword"])

)
{
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $passwd = $_POST["pword"];



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
            //delete cookie
            document.cookie = "id=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
            //set cookie
            //generate date 1 hour in the future
            var date = new Date();
            date.setTime(date.getTime() + (1*60*60*1000));
            document.cookie = "<?php echo $js_cookie; ?>; expires=" + date.toGMTString();

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

<h3>Name: </h3><br><br>

<p>Email: </p><br><br>

<p></p>

<form action="logout.php">
    <input type="submit" value="Log Out">
</form>

</body>
</html>
