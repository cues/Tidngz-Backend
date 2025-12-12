<?php


require "../headers.php";
require "../response.php";
require "../db_pdo.php";
require "../images.php";
require "../user.php"; 
require "../numbers.php"; 
require "data.php"; 





if(!empty($_GET['key']))
{

        $key                    =     empty($_GET['key'])                   ?    0    :    mysqli_real_escape_string($con,$_GET['key']);
        $type                   =     empty($_GET['type'])                  ?    0    :    mysqli_real_escape_string($con,$_GET['type']);
        $user_id                =     empty($_GET['user_id'])               ?    0    :    mysqli_real_escape_string($con,$_GET['user_id']);
        $user_id_follow         =     empty($_GET['user_id_follow'])        ?    0    :    mysqli_real_escape_string($con,$_GET['user_id_follow']);


      
        $sanitize_1 = array($key, $type, $user_id, $user_id_follow);


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
            $follow = new userFollow();
            $data= $follow->follow($user_id, $user_id_follow);
        }else{
            $unfollow = new userUnfollow();
            $data= $unfollow->unfollow($user_id, $user_id_follow);
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





