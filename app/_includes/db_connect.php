<?php

$host = "localhost:3306";
$db = "nortonb_demo_db";
$user = "nortonb_demo_db";
$pass = "*******!!";
//enter your

$link = mysqli_connect($host, $user, $pass, $db);

$db_response = [];
$db_response['success'] = 'not set';

if(!$link){
  $db_response['success'] = false;
}else{
  $db_response['success'] = true;
}
//echo out when live
//echo json_encode($db_response);


?>