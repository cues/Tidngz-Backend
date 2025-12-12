<?php

require "../../../response.php";
require "../../../headers.php";
require "../../../db_pdo.php";
require "data.php"; 
require "astro.php"; 
require "hourly.php"; 
require "../icon.php"; 



if( !empty($_GET['key'])) {

   
    

        $key        = empty($_GET['key'])      ? 0 : mysqli_real_escape_string($con,$_GET['key']);
        $type       = empty($_GET['type'])     ? 0 : mysqli_real_escape_string($con,$_GET['type']);
        $user_id    = empty($_GET['user_id'])  ? 0 : mysqli_real_escape_string($con,$_GET['user_id']);
        $lat        = empty($_GET['lat'])      ? 0 : mysqli_real_escape_string($con,$_GET['lat']);
        $long       = empty($_GET['long'])     ? 0 : mysqli_real_escape_string($con,$_GET['long']);


        $sanitize_1 = array($key, $type, $user_id, $lat, $long);

        if(Sanitize::check_sanitize($sanitize_1, 1) || $type == 0 || $user_id == 0 || $lat == 0 || $long == 0){
            response(400,"Invalid Request sanitize",NULL);   
        }



        $check_key = mysqli_query($con,"SELECT * FROM Api_Keys WHERE CLIENT = '$key'");
        $row_key = mysqli_num_rows($check_key);

        if($row_key == 0){
             response(400,"Invalid Key",NULL);
             exit();
        }

        $user_weather = new UserWeather();
        $data = $user_weather->weather($type, $user_id, $lat, $long);


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