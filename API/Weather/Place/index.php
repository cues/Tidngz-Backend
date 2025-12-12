<?php
require "../../../headers.php";
require "../../../response.php";
require "../../../db_pdo.php";
require "data.php"; 
require "astro.php"; 
require "hourly.php"; 
require "../icon.php"; 

// Temporary debug: show PHP errors in response to help diagnose HTTP 500 locally.
// Remove these lines in production.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



if( !empty($_GET['key'])) {

        $key        = empty($_GET['key'])      ? 0 : mysqli_real_escape_string($con,$_GET['key']);
        $type       = empty($_GET['type'])     ? 0 : mysqli_real_escape_string($con,$_GET['type']);
        $user_id    = empty($_GET['user_id'])  ? 0 : mysqli_real_escape_string($con,$_GET['user_id']);
        $place_id   = empty($_GET['place_id']) ? 0 : mysqli_real_escape_string($con,$_GET['place_id']);
   

        $sanitize_1 = array($key, $type, $user_id, $place_id);

        if(Sanitize::check_sanitize($sanitize_1, 1) || $type == 0 || $user_id == 0 || $place_id == 0  ){
            response(400,"Invalid Request",NULL);   
        }



        $check_key = mysqli_query($con,"SELECT * FROM Api_Keys WHERE CLIENT = '$key'");
        $row_key = mysqli_num_rows($check_key);

        if($row_key == 0){
             response(400,"Invalid Key",NULL);
             exit();
        }

        $place_weather = new PlaceWeather();
        $data = $place_weather->weather($type, $user_id, $place_id);


        if(empty($data))
        {
            response(200,"Unsuccess",NULL);
            exit();
        }
        else
        {
            response(200,"Success",$data);
            exit();
        }
       
}
else
{
	response(400,"Invalid Request",NULL);
    exit();
}