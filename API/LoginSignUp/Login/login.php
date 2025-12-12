<?php

require "../../headers.php";
require "../../response.php";
require "../../db_pdo.php";
require "data.php"; 
require "../../user.php";

require "../../images.php";





if( !empty($_GET['key']) )
{

        $key          =    empty($_GET['key'])        ?    ''    :    mysqli_real_escape_string($con,$_GET['key']);
        $type         =    empty($_GET['type'])       ?    ''    :    mysqli_real_escape_string($con,$_GET['type']);
        $username     =    empty($_GET['username'])   ?    ''    :    mysqli_real_escape_string($con,$_GET['username']);
        $password     =    empty($_GET['password'])   ?    ''    :    mysqli_real_escape_string($con,$_GET['password']);
        $ip           =    empty($_GET['ip'])         ?    ''    :    mysqli_real_escape_string($con,$_GET['ip']);
        $token        =    empty($_GET['token'])      ?    ''    :    mysqli_real_escape_string($con,$_GET['token']);

        
        $sanitize_1 = array($key, $type, $ip);
        $sanitize_4 = array($username, $token, $password);


        if( Sanitize::check_sanitize($sanitize_1, 1) || 
            Sanitize::check_sanitize($sanitize_4, 4) ){
             response(400,"Invalid Request",NULL);
             exit();
        }

        $check_key = mysqli_query($con,"SELECT * FROM Api_Keys WHERE CLIENT = '$key'");
        $row_key = mysqli_num_rows($check_key);

        if($row_key == 0){
             response(400,"Invalid Key",NULL);
             exit();
        }


     
        if($type == 'login'){
            $login = New Login();
            $data  = $login->get_login($username , $password, $ip);
        }else{
            $login = New ReLogin();
            $data  = $login->get_reLogin($token, $ip);
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





