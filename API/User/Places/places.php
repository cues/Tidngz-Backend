<?php

require "../../headers.php";
require "../../response.php";
require "../../db_pdo.php";
require "../../images.php";
require "data.php"; 





if(!empty($_GET['key']))
{

        $key                 =     empty($_GET['key'])        ?    0    :    mysqli_real_escape_string($con,$_GET['key']);
        $type                =     empty($_GET['type'])       ?    0    :    mysqli_real_escape_string($con,$_GET['type']);
        $search              =     empty($_GET['search'])     ?    0    :    mysqli_real_escape_string($con,$_GET['search']);
        $user_id             =     empty($_GET['user_id'])     ?    0    :    mysqli_real_escape_string($con,$_GET['user_id']);

      
        $sanitize_1 = array($key, $type, $search, $user_id);


        if( Sanitize::check_sanitize($sanitize_1, 1)){
             response(400,"Invalid Request d",NULL);
             exit();
        }

        $check_key = mysqli_query($con,"SELECT * FROM Api_Keys WHERE CLIENT = '$key'");
        $row_key = mysqli_num_rows($check_key);

        if($row_key == 0){
             response(400,"Invalid Key",NULL);
             exit();
        }

        if($type == 1){
            $place = new FollowingPlaces();
            $data = $place->following_places($user_id);
        }
        else if($type == 2){
            $place = new SearchPlaces();
            $data = $place->search_places($user_id, $search);
        }
       



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





