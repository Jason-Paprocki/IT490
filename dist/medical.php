<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ('rabbit/path.inc');
require_once ('rabbit/get_host_info.inc');
require_once ('rabbit/rabbitMQLib.inc');

//check if cookie is set
if (strlen($_COOKIE['id']) < 2)
{
    header("Location: login.php");
    exit();
}
//send error with rabbit
function send_error($error)
{
    $client = new rabbitMQClient("errorReporting.ini", "errorReporting");
    $request = array();
    $request['type'] = "Error";
    $request['page'] = "account";
    $request['message'] = $error;
    $response = $client->publish($request);
    exit("sent error");
}

$pname = $_POST["pname"];
$species = $_POST["species"];
$age = $_POST["age"];
$pic = $_POST["pic"];
$petOwner = $_POST["powner"];
$phone = $_POST['phone'];
$address = $_POST['address'];
$checkupTime = $_POST['ptime'];

if (isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] == 'POST'
{
    //send the frontend over to the backend
    $client = new rabbitMQClient("testRabbitMQ.ini", "frontbackcomms");
    $request = array();
    $request['type'] = "insert";
    $request['pname'] = $pname;
    $request['species'] = $species;
    $request['age'] = $age;
    $request['pic'] = $pic;
    $request['ptOwner'] = $petOwner;
    $request['phone'] = $phone;
    $request['address'] = $address;
    $request['checkupTime'] = $checkupTime;

    $response = $client->send_request($request);
    echo var_dump($response["success"]);
    if ($response["success"])
    {
        $js_cookie = "id=" . $response["cookie"];
?>
            <script type="text/JavaScript">
                window.location.reload("medical.php");
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
		<li class="nav-item active"><a href="account.php" class="left-underline nav-button" data-scroll>Account</a></li>
        <li class="nav-item"><a href="friendmatcher.php" class="left-underline nav-button" data-scroll>Friend Matcher</a></li>
        <li class="nav-item"><a href="#section-3" class="left-underline nav-button" data-scroll>Wallet</a></li>
        <li class="nav-item"><a href="#section-2" class="left-underline nav-button" data-scroll>Services</a></li>
		<li class="nav-item active"><a href="forum.php" class="left-underline nav-button" data-scroll>Forums</a></li>
      </ul>
    </div>

<a href="imgur link">
<img src="sample.jpg/png" style="max-width:100%;" alt="description of picture">
</a>

<div class="parallax p1" id="accountform">
      <hgroup>
        <h1>Account</h1>
          <form name="petform" id="petForm" method="POST" >
              <label for="pname">Pet Name:</label><br>
              <input type="text" id="pname" name="pname"><br><br>
              <label for="species">Species:</label><br>
              <input type="text" id="pname" name="pname"><br><br>
              <label for="age">Species:</label><br>
              <input type="text" id="age" name="age"><br><br>
              <label for="pic">Pet Picture link:</label><br>
              <input type="text" id="pic" name="pic"><br><br>
              <label for="ptime">Pet Checkup time:</label><br>
              <input type="text" id="ptime" name="ptime"><br><br>
              <label for="powner">Pet Owner:</label><br>
              <input type="text" id="powner" name="powner"><br><br>
              <label for="phone">Phone Number:</label><br>
              <input type="text" id="phone" name="phone"><br><br>
              <label for="address">Address:</label><br>
              <input type="text" id="address" name="address"><br><br>
              <input type="submit" name ="submit" value="Submit"><br>
          </form>
      </hgroup>
</div>
<div class="parallax p1" id="section-1">
<form action="logout.php">
    <input type="submit" value="Log Out">
</form>
</div>


</body>
</html>
