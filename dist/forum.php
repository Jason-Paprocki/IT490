<?php

  require_once('rabbit/path.inc');
  require_once('rabbit/get_host_info.inc');
  require_once('rabbit/rabbitMQLib.inc');

  //check if id cookie is set
  if(!isset($_COOKIE['id'])){
    header("/login.php");
    exit();
  }
?>

<!DOCTYPE html>
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
		
      </ul>
    </div>
    </html>
<?php
  if (isset($_POST["title"])
    && isset($_POST["message"])
    && isset($_COOKIE['id']))
  {
    //send the frontend shit over to the backend
    $client = new rabbitMQClient("testRabbitMQ.ini","frontbackcomms");
    $request = array();
    $request['type'] = "create_post";
    $request['session_id'] = $_COOKIE['id'];
    $request['title'] = $_POST["title"];
    $request['message'] = $_POST["message"];
    $response = $client->send_request($request);
    if ($response["success"])
    {
			?>
      <script type="text/javascript">
        //alert with response message
        alert("Thanks for submitting your post");
        window.location.href = "/forum.php";
      </script>
    <?php
    }
  }
  elseif(isset($_POST["post_id"])
        && isset($_POST["reply_message"]))
  {
    //send the frontend shit over to the backend
    $client = new rabbitMQClient("testRabbitMQ.ini","frontbackcomms");
    $request = array();
    $request['type'] = "create_reply";
    $request['session_id'] = $_COOKIE['id'];
    $request['post_id'] = $_POST["post_id"];
    $request['reply_message'] = $_POST["reply_message"];
    $response = $client->send_request($request);
    if ($response["success"])
    {
      ?>
      <script type="text/javascript">
        //alert with response message
        alert("Thanks for submitting your reply");
        window.location.href = "/forum.php";
      </script>
    <?php
    }
    else
    {
      ?>
      <script type="text/javascript">
        //alert with response message
        alert("Invalid Post ID");
        window.location.href = "/forum.php";
      </script>
    <?php
    }
  }
  else
  {
  //validate the session and display the posts
  $client = new rabbitMQClient("testRabbitMQ.ini","frontbackcomms");
  $request = array();
  $request['type'] = "show_posts";
  $request['session_id'] = $_COOKIE['id'];
  $response = $client->send_request($request);
  if ($response["success"] == false)
  {
			?>
        <script type="text/javascript">
          //alert with response message
          alert("session expired");
          window.location.href = "/login.php";
        </script>
			<?php
  }

  if (empty($response["posts"]))
  {
    //html no posts to display
    ?>
      <div class="container">
        <div class="row">
          <div class="parallax p1">
            <h1>No posts to display</h1>
          </div>
        </div>
      </div>
    <?php
  }
  else
  {
    foreach ($response["posts"] as $post)
    {
      ?>
      </html>
      <div class="post">
        <div class="post-header">
        <h1><?php echo "Post ID: " . $post["post_id"]; ?></h1>
          <h1><?php echo "Title " . $post["title"]; ?></h1>
          <h2><?php echo "Message: " . $post["message"]; ?></h2>
          <h3><?php echo "Posters name: " . $post["fname"] . " ". $post["lname"]; ?></h3>
          <h3></h3>
      </div>
        </div>
      </div>
      <?php
    }
  }
  }
?>
<div class="parallax p1" id="section-1">
    <hgroup>
      <h1>Reply to post</h1>
        <form name="replyform" id="replyform" method="POST">
            <label for="post_id">Enter post id of post to reply to:</label><br>
            <input type="text" id="post_id" name="post_id"><br><br>
            <label for="reply_message">message:</label><br>
            <input type="text" id="reply_message" name="reply_message"><br><br>
            <input type="submit" value="submit"><br>
        </form>
    </hgroup>
</div>

<div class="parallax p1" id="section-1">
    <hgroup>
      <h1>New Post</h1>
        <form name="postform" id="postform" method="POST">
            <label for="title">title:</label><br>
            <input type="text" id="title" name="title"><br><br>
            <label for="message">message:</label><br>
            <input type="text" id="message" name="message"><br><br>
            <input type="submit" value="submit"><br>
        </form>
    </hgroup>
</div>









