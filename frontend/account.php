<?php
    ini_set('display_errors',1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
  require_once('rabbit/path.inc');
  require_once('rabbit/get_host_info.inc');
  require_once('rabbit/rabbitMQLib.inc');

  //check if id cookie is set
  if (strlen($_COOKIE['id']) < 2){
    header("/login.php");
    exit();
  }
  //get id cookie
  $id = $_COOKIE['id'];
  //send the frontend shit over to the backend
  $client = new rabbitMQClient("testRabbitMQ.ini","frontbackcomms");
  $request = array();
  $request['type'] = "show_time";
  $request['session_id'] = $id;
  $response = $client->send_request($request);

  if($response["success"])
  {
  ?>
      <script type="text/JavaScript">
          alert("<?php echo $response["loginTime"]; ?>");
      </script>
  <?php
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
        <li class="nav-item active"><a href="login.php" class="left-underline nav-button" data-scroll>Account</a></li>
        <li class="nav-item"><a href="#section-3" class="left-underline nav-button" data-scroll>Wallet</a></li>
        <li class="nav-item"><a href="#section-2" class="left-underline nav-button" data-scroll>Services</a></li>
        <li class="nav-item active"><a href="forum.php" class="left-underline nav-button" data-scroll>Forums</a></li>
    </ul>
</div>


<div class="parallax p1" id="section-1">
      <hgroup>
<form action="logout.php">
    <input type="submit" value="Log Out">
</form>
</hgroup>
</div>

</body>
</html>
