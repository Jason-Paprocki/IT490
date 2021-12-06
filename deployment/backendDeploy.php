#!/usr/bin/php
<?php
//rmq include
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQPush.inc');
function push($package, $target, $machine)
{
  $host = preg_split('[-]', gethostname());
  $thishost = (strtolower($host[1] . $host[2]));
  if ($thishost == $target . $machine) {
    echo ("Deploying...");

  switch ($package) {
    case ("feweb"): {
      exec("tar -xf /box.tar -C /");
      break;
    }
    case ("fephp"): {
      exec("tar -xf /box.tar -C /");
      break;
    }
    case ("db"): {
      if (!isset($host[3])){
      exec("tar -xf /box.tar -C /");
      exec("mysql -u root -ppasskey databsename < path to database");
    }
      break;
    }
    case ("bephp"): {
      exec("tar -xf /box.tar -C /");
      if (!isset($host[3])) {
      exec("systemctl restart cine4_auth cine4_data cine4_log");
    }
      break;
    }
     default: {
      echo $package . " is not a valid package";
      break;
    }
  }
  return "Deployed " . $package;
  }


