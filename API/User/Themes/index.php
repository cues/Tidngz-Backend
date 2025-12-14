<?php
require "../../headers.php";
require "../../response.php";
require "../../db_pdo.php";
require "data.php";  



if( !empty($_GET['key'])) {

        $key        = empty($_GET['key'])      ? 0 : mysqli_real_escape_string($con,$_GET['key']);
        $type       = empty($_GET['type'])     ? 0 : mysqli_real_escape_string($con,$_GET['type']);
        $user_id    = empty($_GET['user_id'])  ? 0 : mysqli_real_escape_string($con,$_GET['user_id']);
        $theme      = empty($_GET['theme'])    ? 1 : mysqli_real_escape_string($con,$_GET['theme']);

        $sanitize_1 = array($key, $type, $user_id, $theme);

        if(Sanitize::check_sanitize($sanitize_1, 1)){
            response(400,"Invalid Request sanitize",NULL);   
        }

        $check_key = mysqli_query($con,"SELECT * FROM Api_Keys WHERE CLIENT = '$key'");
        $row_key = mysqli_num_rows($check_key);

        if($row_key == 0){
             response(400,"Invalid Key",NULL);
             exit();
        }

        if($type = 'darkTheme'){
            $user_theme = new Themes();
            $data = $user_theme->dark_theme($user_id, $theme);
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