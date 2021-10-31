//just need to unset cookie here and refresh the page
<script type="text/JavaScript">
    //delete previous cookie
    document.cookie = "id=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
    window.location.reload();
</script>

<?php
//redirect the user to the login page
    header('Location: /login.php');
?>

//added marked line stating that user has now been logged out

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
        <mark>You have now been logged out</mark>
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
