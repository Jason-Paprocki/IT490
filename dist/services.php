<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('rabbit/path.inc');
require_once('rabbit/get_host_info.inc');
require_once('rabbit/rabbitMQLib.inc');

//if the length of cookie is less than 2, then it is not set
if (strlen($_COOKIE['id']) < 2)
{
    header("Location: login.php");
exit();
}
//send error with rabbit
function send_error($error){
    $client = new rabbitMQClient("errorReporting.ini","errorReporting");
    $request = array();
    $request['type'] = "Error";
    $request['page'] = "account";
    $request['message'] = $error;
    $response = $client->publish($request);
    exit("sent error");
}

//https://docs.microsoft.com/en-us/bingmaps/rest-services/locations/local-search
//https://open.fda.gov/apis/animalandveterinary/event/how-to-use-the-endpoint/
//https://www.programmableweb.com/api/petfinder
//https://www.akc.org/expert-advice/health/puppy-shots-complete-guide/
//https://www.petmd.com/cat/wellness/essential-cat-vaccinations

//https://dev.virtualearth.net/REST/v1/LocalSearch/?query=petshelter&userLocation=40.7357,-74.1724&key=AoCJK-YyuE35SvClpIX8bvsJHqU_KRDpcGMrTlj7PHa4Hy4pjzkYSjKSFyuNOZBO
//api key= AIzaSyDqIfZ6J9Y2w3t0tiKC0KZeBW4bNkcThhk
//https://maps.googleapis.com/maps/api/geocode/json?AIzaSyDqIfZ6J9Y2w3t0tiKC0KZeBW4bNkcThhk

?>

<!DOCTYPE html>
<html>
<head>

<div id="googleMap" style="width:100%;height:400px;"></div>

<script>
function myMap() {
var mapProp= {
  center:new google.maps.LatLng(51.508742,-0.120850),
  zoom:5,
};
var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDqIfZ6J9Y2w3t0tiKC0KZeBW4bNkcThhk&callback=myMap"></script>


	<title>Services</title>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
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

    <div class="service"></div>
    <script>
    //gets JSON response from BING map API and displays via html.
    //Was not able to loop it for all results, so right now it will show only the top result for pet shelter
    $.getJSON('https://dev.virtualearth.net/REST/v1/LocalSearch/?query=petshelter&userLocation=40.7357,-74.1724&key=AoCJK-YyuE35SvClpIX8bvsJHqU_KRDpcGMrTlj7PHa4Hy4pjzkYSjKSFyuNOZBO', function(data) {
        var text = `Name: ${data["resourceSets"][0]["resources"][0]["name"]}<br>
                    Address: ${data["resourceSets"][0]["resources"][0]["Address"]["formattedAddress"]}<br>
                    Phone Number: ${data["resourceSets"][0]["resources"][0]["PhoneNumber"]}<br>
                    Website: ${data["resourceSets"][0]["resources"][0]["Website"]}<br>`
        $(".service").html(text);
    });
    </script>


<br><br><br><br><br><br><br><br><br><br>

<iframe width="500" height="400" frameborder="0" src="https://www.bing.com/maps?where1=vet&toWww=1" scrolling="no">
    </iframe>
    <div style="white-space: nowrap; text-align: center; width: 500px; padding: 6px 0;">
        <a id="largeMapLink" target="_blank" href="https://www.bing.com/maps?cp=40.67499999999999~-74.4275&amp;sty=r&amp;lvl=11&amp;FORM=MBEDLD">View Larger Map</a> &nbsp; | &nbsp;
        <a id="dirMapLink" target="_blank" href="https://www.bing.com/maps/directions?cp=40.67499999999999~-74.4275&amp;sty=r&amp;lvl=11&amp;rtp=~pos.40.67499999999999_-74.4275____&amp;FORM=MBEDLD">Get Directions</a>
    </div>

</body>
</div>
</html>
