<?php

  require_once('rabbit/path.inc');
  require_once('rabbit/get_host_info.inc');
  require_once('rabbit/rabbitMQLib.inc');

  //check if id cookie is set
  if(!isset($_COOKIE['id'])){
    header("/login.php");
    exit();
  }

readfile('account.html');
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
