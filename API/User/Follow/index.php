<?php


require "../../../headers.php";
require "../../../response.php";
require "../../../db_pdo.php";
require "data.php"; 


  
if(!empty($_GET['key']))
{
   
    
        $key          =     empty($_GET['key'])               ?    0    :    mysqli_real_escape_string($con,$_GET['key']);
        $user_id      =     empty($_GET['user_id'])           ?    0    :    mysqli_real_escape_string($con,$_GET['user_id']);



        $sanitize_1 = array($key, $user_id);

        if( Sanitize::check_sanitize($sanitize_1, 1)){
             response(400,"Invalid Request",NULL);
             exit();
        }

        if(!APIKey::check_key($con, $key)){
             response(400,"Invalid Key",NULL);
             exit();
        }




        $place = New PlaceTimezone();
        $data = $place->get_place_timezone($user_id);





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





