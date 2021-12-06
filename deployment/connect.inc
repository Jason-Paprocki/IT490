<?php
function createconnection()
{
  $host = 'localhost';
  $user = 'root';
  $pass = 'passkey';
  $db   = '';
  //create connection
  $con  = mysqli_connect($host, $user, $pass, $db);
  if (!$con) {
    //echo 'Not connected to server';
    //errorSend("DB Connect", 3, "Cannot connect to db server");
  }
  return $con;
}
?>
