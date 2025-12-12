<?php

require "../headers.php";
require "../response.php";
require "../db_pdo.php";
require "../images.php";
require "../user.php"; 
require "../numbers.php"; 
require "data.php"; 
require "all_notifications.php"; 
require "Likes/likes.php"; 
require "Comments/comments.php"; 
require "Comment_Likes/comment_likes.php"; 
require "Comment_Reply/comment_reply.php"; 
require "Comment_Reply_Likes/comment_reply_likes.php"; 
require "Followers/followers.php"; 





if(!empty($_GET['key']))
{

        $key                    =     empty($_GET['key'])                   ?    0    :    mysqli_real_escape_string($con,$_GET['key']);
        $type                   =     empty($_GET['type'])                  ?    0    :    mysqli_real_escape_string($con,$_GET['type']);
        $user_id                =     empty($_GET['user_id'])               ?    0    :    mysqli_real_escape_string($con,$_GET['user_id']);
        $records_per_page       =     empty($_GET['records_per_page'])      ?    0    :    mysqli_real_escape_string($con,$_GET['records_per_page']);
        $start                  =     empty($_GET['start'])                 ?    0    :    mysqli_real_escape_string($con,$_GET['start']);
        $notification_ids       =     empty($_GET['notification_ids'])      ?    0    :    mysqli_real_escape_string($con,$_GET['notification_ids']);


      
        $sanitize_1 = array($key, $type, $user_id, $records_per_page, $start);


        if( Sanitize::check_sanitize($sanitize_1, 1)){
             response(400,"Invalid Request d",NULL);
             exit();
        }

        $check_key = mysqli_query($con,"SELECT * FROM Api_Keys WHERE CLIENT = '$key'");
        $row_key = mysqli_num_rows($check_key);

        if($row_key == 0){
             response(400,"Invalid Key",NULL);
             exit();
        }

        if($type == 1){
            $notifications = new AllNotifications();
            $data= $notifications->all_notifications($user_id);
        }else{
            $notifications = new Notifications();
            $data= $notifications->get_notifications($user_id, $start, $records_per_page, $notification_ids);
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





