<?php
require "../../../../headers.php";
require "../../../../response.php";
require "../../../../db_pdo.php";

require "../../../user_data.php";
require "../../../../images.php";


if(!empty($_GET['key'])){

        $key                =   empty($_GET['key'])             ?   0   :   mysqli_real_escape_string($con,$_GET['key']);
        $type               =   empty($_GET['type'])            ?   0   :   mysqli_real_escape_string($con,$_GET['type']);
        $user_id            =   empty($_GET['user_id'])         ?   0   :   mysqli_real_escape_string($con,$_GET['user_id']);
        $comments_id        =   empty($_GET['comments_id'])     ?   0   :   mysqli_real_escape_string($con,$_GET['comments_id']);
        $report_number      =   empty($_GET['report_number'])   ?   0   :   mysqli_real_escape_string($con,$_GET['report_number']);


        $sanitize_1 = array($key, $type, $user_id, $comments_id, $report_number);

        if(Sanitize::check_sanitize($sanitize_1, 1)){
             response(400,"Invalid Request",NULL);   
        }

        $check_key = mysqli_query($con,"SELECT * FROM Api_Keys WHERE CLIENT = '$key'");
        $row_key = mysqli_num_rows($check_key);

        if($row_key == 0){
             response(400,"Invalid Key",NULL);
        }


        if($type === 'like'){

            require "like_data.php"; 

            $comments = new Comments();
            $data = $comments->like_comment($comments_id, $user_id);
    
        }
        else if($type === 'delete'){

            require "delete_data.php"; 

            $comments = new Comments();
            $data = $comments->delete_comment($comments_id, $user_id);
    
        }
        else if($type === 'report' && $report_number != 0){

            require "report_data.php"; 

            $comments = new Comments();
            $data = $comments->report_comment($comments_id, $user_id, $report_number);
    
        }
        else
        {
            response(400,"Invalid Request",NULL);     
        }
  


    
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