#!/usr/bin/php
<?php

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');


$args = parseArgs($argv);
switch ($args[0]) {
  case ("create"): {
    create($args);
    break;
  }
  case ("deploy"): {
    deploy($args);
    break;
  }
  case ("depreciate"): {
    depreciate($args);
    break;
  }
  case ("rollback"): {
    rollback($args);
    break;
  }
  case ("help"): {
    print ("create: package \ndeploy: package, version, target \ndepreciate: package, version \nrollback: package, target\n\n");
    break;
  }
  default: {
    echo $args[0] . " is not a valid command";
    break;
  }
}
function create($args)
{
  $package = $args[1];
  $host    = gethostname();
  $quit    = false;
  switch ($package) {
    case ("feweb"): {
      exec("tar -cf /home/cine/pack/box.tar -P path to the folder dist --exclude='php'");
      break;
    }
    case ("db"): {
      exec("mysqldump -u root -ppasskey databasename > path to the sql file");
      exec("tar -cf path to the tar file -P path to our database sql file");
      break;
    }
    default: {
      echo $package . " is not a valid package";
      $quit = true;
      break;
    }
  }
  if (!$quit) {
    $client             = new rabbitMQClient("deployMQ.ini", "testServer");
    $request            = array();
    $request['type']    = "create";
    $request['package'] = $package;
    $request['host']    = gethostname();
    $response           = $client->send_request($request);
    if ($response) {
      print ($response."\n\n");
    }
  }
}
function deploy($args)
{
  $client             = new rabbitMQClient("deployMQ.ini", "testServer");
  $request            = array();
  $request['type']    = "deploy";
  $request['package'] = $args[1];
  $request['version'] = $args[2];
  $request['target']  = $args[3];
  $response           = $client->send_request($request);
  if ($response) {
    print ($response."\n\n");
  }
}
function depreciate($args)
{
  $client             = new rabbitMQClient("deployMQ.ini", "testServer");
  $request            = array();
  $request['type']    = "depreciate";
  $request['package'] = $args[1];
  $request['version'] = $args[2];
  $response           = $client->send_request($request);
  if ($response) {
    print ($response."\n\n");
  }
}
function rollback($args)
{
  $client             = new rabbitMQClient("deployMQ.ini", "testServer");
  $request            = array();
  $request['type']    = "rollback";
  $request['package'] = $args[1];
  $request['target']  = $args[2];
  $response           = $client->send_request($request);
  if ($response) {
    print ($response."\n\n");
  }
}
function parseArgs($argv)
{
  array_shift($argv);
  $o = array();
  foreach ($argv as $a) {
    if (substr($a, 0, 2) == '--') {
      $eq = strpos($a, '=');
      if ($eq !== false) {
        $o[substr($a, 2, $eq - 2)] = substr($a, $eq + 1);
      } else {
        $k = substr($a, 2);
        if (!isset($o[$k])) {
          $o[$k] = true;
        }
      }
    } else if (substr($a, 0, 1) == '-') {
      if (substr($a, 2, 1) == '=') {
        $o[substr($a, 1, 1)] = substr($a, 3);
      } else {
        foreach (str_split(substr($a, 1)) as $k) {
          if (!isset($o[$k])) {
            $o[$k] = true;
          }
        }
      }
    } else {
      $o[] = $a;
    }
  }
  return $o;
}
?>
