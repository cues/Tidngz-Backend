<?php
require "../../../../headers.php";
require "../../../../response.php";
require "../../../../db_pdo.php";

require "data.php"; 
require "all_comments.php"; 
require "../../../user_data.php";
require "../../../../images.php";


if(!empty($_GET['key'])){

        $key                =   empty($_GET['key'])               ?   0   :   mysqli_real_escape_string($con,$_GET['key']);
        $last_comment_id    =   empty($_GET['last_comment_id'])   ?   1   :   mysqli_real_escape_string($con,$_GET['last_comment_id']);
        $user_id            =   empty($_GET['user_id'])           ?   0   :   mysqli_real_escape_string($con,$_GET['user_id']);
        $articles_id        =   empty($_GET['articles_id'])       ?   0   :   mysqli_real_escape_string($con,$_GET['articles_id']);
        $records_per_page   =   empty($_GET['records_per_page'])  ?   0   :   mysqli_real_escape_string($con,$_GET['records_per_page']);
        $start              =   empty($_GET['start'])             ?   0   :   mysqli_real_escape_string($con,$_GET['start']);


        $sanitize_1 = array($key,  $last_comment_id, $user_id, $articles_id, $records_per_page, $start);

        if(Sanitize::check_sanitize($sanitize_1, 1)){
             response(400,"Invalid Request",NULL);   
        }

        $check_key = mysqli_query($con,"SELECT * FROM Api_Keys WHERE CLIENT = '$key'");
        $row_key = mysqli_num_rows($check_key);

        if($row_key == 0){
             response(400,"Invalid Key",NULL);
        }


    
        $comments = new Comments();
        $data = $comments->get_comments($last_comment_id, $articles_id, $user_id, $records_per_page, $start);

  
    
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