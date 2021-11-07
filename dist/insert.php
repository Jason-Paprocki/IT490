<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../backend/backend.php');

$pname = $_POST["pname"];
$species = $_POST["species"];
$pic = $_POST["pic"];
$zip = $_POST["zip"];

if(isset($_POST['pname'])
&& isset($_POST['species'])
&& isset($_POST['pic'])
&& isset($_POST['zip']))
{
    //send the frontend over to the backend
    $client = new rabbitMQClient("testRabbitMQ.ini","frontbackcomms");
	$request = array();
	$request['type'] = "insert";
	$request['pname'] = $pname;
	$request['species'] = $species;
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

?>
