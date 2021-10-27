<?php
    require("config.php");
    require_once('path.inc');
    require_once('get_host_info.inc');
    require_once('rabbitMQLib.inc');

    $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
    try{
        $db = new PDO($connection_string, $dbuser, $dbpass);
        echo "Created to create Users table\n";
        $stmt = $db->prepare("dafvdfgdfagnsdjfngjdfsngjk;nds;fvd;sfjngodfsngojndfsjg;nf;sjgnjsdknfgjksdngjk;rnf"
                );
        $stmt->execute();
        echo var_export($stmt->errorInfo(), true);
    }
    //sends error message to every machine
    catch(Exception $e){
        echo $e->getMessage();
        $client = new rabbitMQClient("testRabbitMQ.ini","testServer");
        $request = array();
        $request['type'] = "Error";
        $request['message'] = $e;
        //$response = $client->send_request($request);
        $response = $client->publish($request);

        echo "sent error".PHP_EOL;
        exit("It didn't work");
    }
?>