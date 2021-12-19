<?php
//so we can have a picture of the animal
//sort based on the animal the user has
//display the picture in the center
//ask the user in account.php to upload an imgure link 

//link this in the html
//have like an x on the right and a check on the left
//if the user clicks on the x, move to the next profile
//if the user clicks on the check, create some way to message that person (message.php )
//bottom will be distance from each other
//track using zip codes
//$user1
//$user2

readfile('friendmatcher.html');
require('insert.php');
require('account.php');
require_once('rabbit/path.inc');
require_once('rabbit/get_host_info.inc');
require_once('rabbit/rabbitMQLib.inc');

$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "https://redline-redline-zipcode.p.rapidapi.com/rest/multi-info.json/08057,08055/degrees",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"x-rapidapi-host: redline-redline-zipcode.p.rapidapi.com",
		"x-rapidapi-key: 7551f0b822mshdac2ed2083b5b3ap1ac5e3jsn54bff4cb09cc"
	],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	echo $response;
}

//basic api call for distance
//use the api to get the distance between the two users
//display the distance in miles

//html
//display this info with maybe another table
//3 columns; 3 rows
//top left will have an type of animal -- can match cat with dog maybe?
//top center will have animal name
//top right == ???????????????????????
//left and right center are empty
//center; center has the picture --  make it really bug or something
//bottom left has x -- make red?
//bottom right has check -- make green?
//bottom center has distance in miles



//all the way on the right has the message thing
//dont know if we can do this in the same page
//or if we need to make a new page

/*<?php echo $pic; ?> */

?>
