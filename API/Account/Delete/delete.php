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

        $key          =    empty($_GET['key'])        ?    0    :    mysqli_real_escape_string($con,$_GET['key']);
        $user_id      =    empty($_GET['user_id'])    ?    0    :    mysqli_real_escape_string($con,$_GET['user_id']);
        $delete       =    empty($_GET['delete'])     ?    0    :    mysqli_real_escape_string($con,$_GET['delete']);
        $token        =    empty($_GET['token'])      ?    0    :    mysqli_real_escape_string($con,$_GET['token']);


      
        $sanitize_1 = array($key, $user_id, $delete);
        $sanitize_4 = array($token);

        
        if( Sanitize::check_sanitize($sanitize_1, 1) || 
            Sanitize::check_sanitize($sanitize_4 , 4)){
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
            'user_id'  =>  $user_id,
            'token'    =>  $token,
            'delete'   =>  $delete,
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





