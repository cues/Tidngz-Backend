<?php

require "../../headers.php";
require "../../response.php";
require "../../db_pdo.php";
require "../../images.php";
require "../../user.php"; 
require "../../numbers.php"; 
require "data.php"; 





if(!empty($_GET['key']))
{

        $key          =    empty($_GET['key'])            ?    0     :    mysqli_real_escape_string($con,$_GET['key']);
        $user_id      =    empty($_GET['user_id'])        ?    0     :    mysqli_real_escape_string($con,$_GET['user_id']);
        $bio          =    empty($_GET['bio'])            ?    ''    :    mysqli_real_escape_string($con,$_GET['bio']);
        $website      =    empty($_GET['website'])        ?    ''    :    mysqli_real_escape_string($con,$_GET['website']);
        $facebook     =    empty($_GET['facebook'])       ?    ''    :    mysqli_real_escape_string($con,$_GET['facebook']);
        $instagram    =    empty($_GET['instagram'])      ?    ''    :    mysqli_real_escape_string($con,$_GET['instagram']);
        $twitter      =    empty($_GET['twitter'])        ?    ''    :    mysqli_real_escape_string($con,$_GET['twitter']);
        $youtube      =    empty($_GET['youtube'])        ?    ''    :    mysqli_real_escape_string($con,$_GET['youtube']);
        $token        =    empty($_GET['token'])          ?    0     :    mysqli_real_escape_string($con,$_GET['token']);


      
        $sanitize_1 = array($key, $user_id);
        $sanitize_2 = array($bio, $website, $facebook, $instagram, $twitter, $youtube);
        $sanitize_4 = array($token);

        
        if( Sanitize::check_sanitize($sanitize_1, 1) || 
            Sanitize::check_sanitize($sanitize_2, 2) || 
            Sanitize::check_sanitize($sanitize_4, 4)){
             response(400,"Invalid Request errors",NULL);
             exit();
        }

        $check_key = mysqli_query($con,"SELECT * FROM Api_Keys WHERE CLIENT = '$key'");
        $row_key = mysqli_num_rows($check_key);

        if($row_key == 0){
             response(400,"Invalid Key",NULL);
             exit();
        }

        $userData = array(
            'user_id'     =>  $user_id,
            'bio'         =>  $bio,
            'website'     =>  $website, 
            'facebook'    =>  $facebook,
            'instagram'   =>  $instagram,
            'twitter'     =>  $twitter,
            'youtube'     =>  $youtube,
            'token'       =>  $token,
        );
           
    
        $update = New UpdateUser();
        $data = $update->update_user($userData);



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





