<?php


require "../../../headers.php";
require "../../../response.php";
require "../../../db_pdo.php";
require "data.php"; 
require "../data.php";
require "../../../images.php";


  
if(!empty($_GET['key']))
{
    
        $key          =     empty($_GET['key'])               ?    0    :    mysqli_real_escape_string($con,$_GET['key']);
        $user_id      =     empty($_GET['user_id'])           ?    0    :    mysqli_real_escape_string($con,$_GET['user_id']);
        $place_id     =     empty($_GET['place_id'])          ?    0    :    mysqli_real_escape_string($con,$_GET['place_id']);



        $sanitize_1 = array($key, $user_id, $place_id);

        if(Sanitize::check_sanitize($sanitize_1, 1)){
             response(400,"Invalid Request",NULL);
             exit();
        }
        
        if(!APIKey::check_key($con, $key)){
             response(400,"Invalid Key",NULL);
             exit();
        }



        $place = New FollowPlace();
        $data = $place->follow_place($user_id, $place_id);




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





