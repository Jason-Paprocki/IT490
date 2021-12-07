#!/usr/bin/php
<?php
//rmq include
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('conn.inc');

function create($package, $host)
{
  $con   = createconnection();
  $query = "SELECT	max(V.VersionNum) FROM	Version as V WHERE	V.PackageName = \"$package\"";
  $sql  = mysqli_query($con, $query);
  $data = array();
  if ($row = mysqli_fetch_assoc($sql)) {
    $version = $row['max(V.VersionNum)'] + 1;

    echo 'version:';
    echo $version;
  }
  exec("scp -o StrictHostKeyChecking=no -r cine@" . $host . ":/home/cine/pack/box.tar /home/cine/packages/" . $package . "-" . $version . ".tar");
  $sql = "INSERT INTO	Version (VersionID, VersionNum, Deprecate, PackageName) VALUES	(null, '" . $version . "', default, '" . $package . "')";
  if ($con->query($sql) === TRUE) {
    echo "DB Insert";
  }
  return $package . " version " . $version . " created";
}

function deploy($package, $version, $target)
{
  $con   = createconnection();
  $query = "SELECT * FROM `Version` WHERE `PackageName` = \"$package\" and `VersionNum` = \"$version\"";
  $sql   = mysqli_query($con, $query);
  $data  = array();
  if ($row = mysqli_fetch_assoc($sql)) {
    $vid = $row['VersionID'];
    $dep = $row['Deprecate'];
    switch ($package) {
      case ("feweb"):
      case ("fephp"): {
        $mach = 'fe';
        break;
      }
      case ("db"):
      case ("bephp"): {
        $mach = 'be';
        break;
      }
      default: {
        return $package . " is not a valid package";
      }
    }
    if ($dep == 'Y') {
      return "package is depreciated";
    }
    if ($target == 'prod') {
      exec("scp -o StrictHostKeyChecking=no -r path to the packages" . $package . "-" . $version . ".tar doctor@490-PROD-" . $mach . ":path to where the file tar file is");
      exec("scp -o StrictHostKeyChecking=no -r path to the packages" . $package . "-" . $version . ".tar doctor@490-PROD-" . $mach . "path to where the file tar file is");
      $q1 = "SET @ip = (SELECT m.hostip FROM Machine as m WHERE m.hostname = '490-prod-" . $mach . "');";
    }
    if ($target == 'qa') {
      exec("scp -o StrictHostKeyChecking=no -r path to the packages" . $package . "-" . $version . ".tar dcotor@490-QA-" . $mach . ":path to where the file tar file is");
      $q1 = "SET @ip = (SELECT m.hostip FROM Machine as m WHERE m.hostname = '490-qa-" . $mach . "');";
    }
    $con                = createconnection();
    $q2                 = "Update Machinehaspackage as H Set VersionID = " . $vid . " Where H.HostIP = @ip and H.PackageName = '" . $package . "'";
    $sql1               = mysqli_query($con, $q1);
    $sql2               = mysqli_query($con, $q2);
    $client             = new rabbitMQClient("pushMQ.ini", "testServer");
    $request            = array();
    $request['type']    = "push";
    $request['package'] = $package;
    $request['target']  = $target;
    $request['mach']    = $mach;
    $response           = $client->send_request($request);
    if ($response) {
      echo($response);
      return 'Deployed';
    }
  } else {
    return "package does not exist";
  }
}

