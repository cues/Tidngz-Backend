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
    $place_url    =     empty($_GET['place_url'])         ?    ''   :    mysqli_real_escape_string($con,$_GET['place_url']);
    $place        =     empty($_GET['place'])             ?    ''   :    mysqli_real_escape_string($con,$_GET['place']);
    $county       =     empty($_GET['county'])            ?    ''   :    mysqli_real_escape_string($con,$_GET['county']);
    $province     =     empty($_GET['province'])          ?    ''   :    mysqli_real_escape_string($con,$_GET['province']);
    $country      =     empty($_GET['country'])           ?    ''   :    mysqli_real_escape_string($con,$_GET['country']);
    $formatted_address = empty($_GET['formatted_address'])?   ''   :    mysqli_real_escape_string($con,$_GET['formatted_address']);
    $latitude     =     empty($_GET['latitude'])          ?    0    :    mysqli_real_escape_string($con,$_GET['latitude']);
    $longitude    =     empty($_GET['longitude'])         ?    0    :    mysqli_real_escape_string($con,$_GET['longitude']);
    $latitude_delta  =  empty($_GET['latitude_delta'])    ?    0    :    mysqli_real_escape_string($con,$_GET['latitude_delta']);
    $longitude_delta =  empty($_GET['longitude_delta'])   ?    0    :    mysqli_real_escape_string($con,$_GET['longitude_delta']);


    $sanitize_1 = array($key, $user_id, $place_id);
    $sanitize_2 = array($place_url, $place, $county, $province, $country, $formatted_address);

        if(Sanitize::check_sanitize($sanitize_1, 1)){
             response(400,"Invalid Request",NULL);
             exit();
        }
        
        if(!APIKey::check_key($con, $key)){
             response(400,"Invalid Key",NULL);
             exit();
        }



        if(Sanitize::check_sanitize($sanitize_2, 3)){
            response(400,"Invalid Request",NULL);
            exit();
        }

    $addPlace = New AddPlace();
    $data = $addPlace->add_place($user_id, $place_id, $place_url, $place, $county, $province, $country, $formatted_address, $latitude, $longitude, $latitude_delta, $longitude_delta);




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





