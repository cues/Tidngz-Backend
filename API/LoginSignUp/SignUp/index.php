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
        $name         =    empty($_GET['name'])       ?    ''    :    mysqli_real_escape_string($con,$_GET['name']);
        $username     =    empty($_GET['username'])   ?    ''    :    mysqli_real_escape_string($con,$_GET['username']);
        $email        =    empty($_GET['email'])      ?    ''    :    mysqli_real_escape_string($con,$_GET['email']);
        $password     =    empty($_GET['password'])   ?    ''    :    mysqli_real_escape_string($con,$_GET['password']);
        $gender       =    empty($_GET['gender'])     ?    ''    :    mysqli_real_escape_string($con,$_GET['gender']);
        $timezone     =    empty($_GET['timezone'])   ?    ''    :    mysqli_real_escape_string($con,$_GET['timezone']);
        $captcha      =    empty($_GET['captcha'])    ?    ''    :    mysqli_real_escape_string($con,$_GET['captcha']);
        $ip           =    empty($_GET['ip'])         ?    ''    :    mysqli_real_escape_string($con,$_GET['ip']);

        

        $sanitize_1 = array($key, $type, $name, $gender, $ip);
        $sanitize_4 = array($username, $email, $password, $timezone, $captcha);


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


        $userData = array(
            'type'      =>  $type,
            'name'      =>  $name,
            'username'  =>  $username, 
            'email'     =>  $email,
            'password'  =>  $password, 
            'gender'    =>  $gender, 
            'timezone'  =>  $timezone, 
            'captcha'   =>  $captcha, 
            'ip'        =>   $ip
        );
           
        

        $signUp = New SignUp();
        $data = $signUp->insertUser($userData);




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





