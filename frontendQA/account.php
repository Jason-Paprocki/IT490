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

readfile('account.html');

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
if(isset($_POST['pname'])
&& isset($_POST['species'])
&& isset($_POST['age'])
&& isset($_POST['pic'])
&& isset($_POST['zip']))
{
    $pname = $_POST["pname"];
    $species = $_POST["species"];
    $age = $_POST["age"];
    $pic = $_POST["pic"];
    $zip = $_POST["zip"];
    //send the frontend over to the backend
    $client = new rabbitMQClient("testRabbitMQ.ini","frontbackcomms");
	$request = array();
	$request['type'] = "insert";
	$request['pname'] = $pname;
	$request['species'] = $species;
  $request['age'] = $age;
	$request['pic'] = $pic;
	$request['zip'] = $zip;
	$response = $client->send_request($request);
	echo var_dump($response["success"]);
	if($response["success"])
    	{
            $js_cookie = "id=" . $response["cookie"];
        ?>
            <script type="text/JavaScript">
                window.location.replace("/friendmatcher.php");
            </script>
        <?php
            exit();
        }
        else
        {
            ?>
                <script type="text/javascript">
                alert("<?php echo $response["msg"]; ?>");
                window.location.href = "account.php";
                </script>
            <?php
        }
}
/*
echo "<script type='text/javascript'> 
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result).width(150).height(200);
        };
        reader.readAsDataURL(input.files[0]);
    }
};
</script>";
*/
?>