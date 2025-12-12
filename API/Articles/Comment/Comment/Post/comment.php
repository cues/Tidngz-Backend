<?php
require "../../../../headers.php";
require "../../../../response.php";
require "../../../../db_pdo.php";
require "data.php"; 
require "../Get/all_comments.php"; 

require "../../../user_data.php";
require "../../../../images.php";


if(!empty($_GET['key'])){

        $key            =   empty($_GET['key'])           ?   0   :   mysqli_real_escape_string($con,$_GET['key']);
        $user_id        =   empty($_GET['user_id'])       ?   0   :   mysqli_real_escape_string($con,$_GET['user_id']);
        $articles_id    =   empty($_GET['articles_id'])   ?   0   :   mysqli_real_escape_string($con,$_GET['articles_id']);
        $comment        =   empty($_GET['comment'])       ?   0   :   mysqli_real_escape_string($con,$_GET['comment']);
        


        $sanitize_1 = array($key, $user_id, $articles_id);
        $sanitize_3 = array($comment);

        if(Sanitize::check_sanitize($sanitize_1, 1) || 
           Sanitize::check_sanitize($sanitize_3, 3)){
             response(400,"Invalid Request",NULL);   
        }

        $check_key = mysqli_query($con,"SELECT * FROM Api_Keys WHERE CLIENT = '$key'");
        $row_key = mysqli_num_rows($check_key);

        if($row_key == 0){
             response(400,"Invalid Key",NULL);
        }


        $comments = new Comments();
        $data = $comments->add_comment($user_id, $articles_id, $comment);

       
        if(empty($data))
        {
            response(200,"Unsuccess",NULL);
        }
        else
        {
            response(200,"Success",$data);
        }
       
}
else
{
	response(400,"Invalid Request",NULL);
}