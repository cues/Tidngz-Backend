<?php

require "../../headers.php";
require "../../response.php";
require "../../db_pdo.php";
require "data.php"; 



if( !empty($_GET['key'])){

        $key = empty($_GET['key']) ? 0 : mysqli_real_escape_string($con,$_GET['key']);
        $user_id = empty($_GET['user_id']) ? 0 : mysqli_real_escape_string($con,$_GET['user_id']);
        $articles_id = empty($_GET['articles_id']) ? 0 : mysqli_real_escape_string($con,$_GET['articles_id']);
        $report_number = empty($_GET['report_number']) ? 0 : mysqli_real_escape_string($con,$_GET['report_number']);


        $sanitize_1 = array($key, $user_id, $articles_id, $report_number);

        if(Sanitize::check_sanitize($sanitize_1, 1) || $articles_id == 0){
             response(400,"Invalid Request",NULL);   
        }




        $check_key = mysqli_query($con,"SELECT * FROM Api_Keys WHERE CLIENT = '$key'");
        $row_key = mysqli_num_rows($check_key);

        if($row_key == 0){
             response(400,"Invalid Key",NULL);
             exit();
        }


        $article_report = new Article_Report();
        $data = $article_report->report_info($user_id, $articles_id, $report_number);


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