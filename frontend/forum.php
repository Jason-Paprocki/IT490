<?php

  //check if id cookie is set
  if(!isset($_COOKIE['id'])){
    header("/login.php");
    exit();
  }
  //get id cookie
  $id = $_COOKIE['id'];

  $client = new rabbitMQClient("testRabbitMQ.ini","frontbackcomms");
  $request = array();
  $request['type'] = "validate_session";
  $request['password'] = $id;

  $response = $client->send_request($request);
  if(!$response["success"])
  {
			//echo javascript alert containing invalid login session
			?>
			<script type="text/javascript">
				//alert with response message
				alert("Your session has expired");
				window.location.href = "/login.php";
			</script>
			<?php
      exit();
  }

  $client = new rabbitMQClient("testRabbitMQ.ini","frontbackcomms");
  $request = array();
  $request['type'] = "load_posts";
  $response = $client->send_request($request);
  if ($response["posts"] == "No Posts")
  {
    //html display for no posts
    ?>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1>No Posts</h1>
        </div>
      </div>
    </div>
    <?php
  }
  else
  {
    //html display for posts
    ?>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1>Posts</h1>
        </div>
      </div>
    </div>
    <?php
    foreach ($response["posts"] as $post)
    {
      //html display for each post
      ?>
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h2><?php echo $post["title"]; ?></h2>
            <p><?php echo $post["content"]; ?></p>
          </div>
        </div>
      </div>
      <?php
    }
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
    <div class="parallax p1" id="section-1">
      <hgroup>
        <h1>Forums</h1>
      </hgroup>
    </div>
    <div class="row">
      <div class="col-3">
        <h1>First Section Col1</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam cursus maximus quam et dignissim. Praesent felis arcu, 
		euismod et ullamcorper ut, condimentum ut ante. Vestibulum vel libero commodo, aliquam libero eu, gravida arcu. Proin scelerisque faucibus
		ligula quis efficitur. Donec at sollicitudin purus, suscipit tempus augue. Sed imperdiet volutpat sapien at hendrerit. Mauris egestas ex a 
		quam tincidunt gravida. Quisque interdum tempor lacinia. Nulla eget varius purus. Integer non sollicitudin dui. Phasellus sem turpis, maximus 
		in auctor vulputate, porta id nunc.</p>
      </div>
      <div class="col-3">
        <h1>First Section Col2</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque imperdiet est id leo facilisis, 
		quis egestas erat vehicula. Aenean nec facilisis leo, et tristique lorem. Aliquam porttitor, elit ac ornare lacinia,
		sapien augue sagittis dolor, tempor ultricies lorem arcu et ante. Nulla facilisi. Praesent facilisis lacus at blandit maximus.
		Ut at libero nisi. Cras eu augue tellus. Nam pretium eget nisi non viverra. Maecenas eget tincidunt nibh, vitae interdum dolor.</p>
      </div>
      <div class="col-3">
        <h1>First Section Col3</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ac nibh dolor.
		Cras rutrum molestie ligula posuere hendrerit. Donec luctus vitae elit in gravida. 
		Duis in viverra nunc. Nunc et metus blandit, blandit quam in, laoreet mi. In vitae mauris sit amet tellus dictum rhoncus malesuada non arcu.
		Ut lacus lacus, dignissim at commodo id, dapibus sed felis. Cras in nunc id est lobortis euismod. Sed egestas nulla et augue sagittis lacinia.
		Quisque finibus bibendum risus, vitae accumsan ante mollis ac. Aliquam fermentum, mauris eu gravida dictum, tortor risus finibus nibh,
		in tincidunt velit tortor vehicula mi.</p>
      </div>
    </div>
    <div class="parallax p2" id="section-2">
      <hgroup>
        <h1>Well</h1>
        <h2>This is some bold info!</h2>
      </hgroup>
    </div>
    <div class="row">
      <div class="col-3">
        <h1>First Section Col1</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam cursus maximus quam et dignissim. 
		Praesent felis arcu, euismod et ullamcorper ut, condimentum ut ante. Vestibulum vel libero commodo, 
		aliquam libero eu, gravida arcu. Proin scelerisque faucibus ligula quis efficitur. Donec at sollicitudin purus, 
		suscipit tempus augue. Sed imperdiet volutpat sapien at hendrerit. Mauris egestas ex a quam tincidunt gravida.
		Quisque interdum tempor lacinia. Nulla eget varius purus. Integer non sollicitudin dui. Phasellus sem turpis,
		maximus in auctor vulputate, porta id nunc.</p>
      </div>
      <div class="col-3">
        <h1>First Section Col2</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque imperdiet est id leo facilisis,
		quis egestas erat vehicula. Aenean nec facilisis leo, et tristique lorem. Aliquam porttitor, elit ac ornare lacinia,
		sapien augue sagittis dolor, tempor ultricies lorem arcu et ante. Nulla facilisi. Praesent facilisis lacus at blandit maximus.
		Ut at libero nisi. Cras eu augue tellus. Nam pretium eget nisi non viverra. Maecenas eget tincidunt nibh, vitae interdum dolor.</p>
      </div>
      <div class="col-3">
        <h1>First Section Col3</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ac nibh dolor.
		Cras rutrum molestie ligula posuere hendrerit. Donec luctus vitae elit in gravida.
		Duis in viverra nunc. Nunc et metus blandit, blandit quam in, laoreet mi. 
		In vitae mauris sit amet tellus dictum rhoncus malesuada non arcu. Ut lacus lacus, dignissim at commodo id,
		dapibus sed felis. Cras in nunc id est lobortis euismod. Sed egestas nulla et augue sagittis lacinia. Quisque finibus bibendum risus,
		vitae accumsan ante mollis ac. Aliquam fermentum, mauris eu gravida dictum, tortor risus finibus nibh, in tincidunt velit tortor vehicula mi.</p>
      </div>
    </div>
    <div class="parallax p3">
      <hgroup>
        <h1>Well</h1>
        <h2>This is some bold info!</h2>
      </hgroup>
    </div>
    <footer>
      <div class="row" id="section-3">
        <div class="col-3">
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum interdum tortor ac dui malesuada,
		  id molestie elit aliquam. Fusce laoreet nisl id tellus molestie mattis. Fusce vitae ante quis augue imperdiet rutrum a vitae purus.
		  Etiam tincidunt enim id turpis varius, in condimentum elit.</p>
        </div>
      </div>
      <div class="footer-copyright">
        <div class="container">
          © 2021 Team Doge, All rights reserved.
        </div>
      </div>
    </footer>
  </body>
</html>
<!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script><script  src="./script.js"></script>

</body>
</html>
