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

        $key                    =    empty($_GET['key'])                ?    0    :    mysqli_real_escape_string($con,$_GET['key']);
        $user_id                =    empty($_GET['user_id'])            ?    0    :    mysqli_real_escape_string($con,$_GET['user_id']);
        $password               =    empty($_GET['password'])           ?    ''   :    mysqli_real_escape_string($con,$_GET['password']);
        $new_password           =    empty($_GET['new_password'])       ?    ''   :    mysqli_real_escape_string($con,$_GET['new_password']);
        $confirm_password       =    empty($_GET['confirm_password'])   ?    ''   :    mysqli_real_escape_string($con,$_GET['confirm_password']);
        $token                  =    empty($_GET['token'])              ?    0    :    mysqli_real_escape_string($con,$_GET['token']);


      
        $sanitize_1 = array($key, $user_id);
        $sanitize_4 = array($password, $new_password, $confirm_password, $token);

        
        if( Sanitize::check_sanitize($sanitize_1, 1) || 
            Sanitize::check_sanitize($sanitize_4, 4) || $password == "" || $new_password == "" || $confirm_password == "" ){
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
            'user_id'          =>  $user_id,
            'password'         =>  $password,
            'new_password'     =>  $new_password, 
            'confirm_password' =>  $confirm_password,
            'token'            =>  $token,
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





