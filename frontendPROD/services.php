<?php
//https://docs.microsoft.com/en-us/bingmaps/rest-services/locations/local-search
//https://open.fda.gov/apis/animalandveterinary/event/how-to-use-the-endpoint/
//https://www.programmableweb.com/api/petfinder
//https://www.akc.org/expert-advice/health/puppy-shots-complete-guide/
//https://www.petmd.com/cat/wellness/essential-cat-vaccinations

//https://dev.virtualearth.net/REST/v1/LocalSearch/?query=petshelter&userLocation=40.7357,-74.1724&key=AoCJK-YyuE35SvClpIX8bvsJHqU_KRDpcGMrTlj7PHa4Hy4pjzkYSjKSFyuNOZBO

?>

<!DOCTYPE html>
<html>
<head>
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
