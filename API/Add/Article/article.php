<?php

require "../../headers.php";
require "../../response.php";
require "../../db_pdo.php";
require "data.php"; 





if(!empty($_GET['key']))
{

        $key                 =     empty($_GET['key'])               ?    0    :    mysqli_real_escape_string($con,$_GET['key']);
        $user_id             =     empty($_GET['user_id'])           ?    0    :    mysqli_real_escape_string($con,$_GET['user_id']);
        $post_anyn           =     empty($_GET['post_anyn'])         ?    0    :    mysqli_real_escape_string($con,$_GET['post_anyn']);
        $type                =     empty($_GET['type'])              ?    0    :    mysqli_real_escape_string($con,$_GET['type']);
        $tags                =     empty($_GET['tags'])              ?    0    :    mysqli_real_escape_string($con,$_GET['tags']);
        $screen_size         =     empty($_GET['screen_size'])       ?    1    :    mysqli_real_escape_string($con,$_GET['screen_size']);
        $place_id            =     empty($_GET['place_id'])          ?    0    :    mysqli_real_escape_string($con,$_GET['place_id']);
        $place_local_id      =     empty($_GET['place_local_id'])    ?    0    :    mysqli_real_escape_string($con,$_GET['place_local_id']);
        $landmark_desc       =     empty($_GET['landmark_desc'])     ?    0    :    mysqli_real_escape_string($con,$_GET['landmark_desc']);
        $category_id         =     empty($_GET['category_id'])       ?    0    :    mysqli_real_escape_string($con,$_GET['category_id']);
        $headline            =     empty($_GET['headline'])          ?    0    :    mysqli_real_escape_string($con,$_GET['headline']);
        $description         =     empty($_GET['description'])       ?    ''   :    mysqli_real_escape_string($con,$_GET['description']);
        $link                =     empty($_GET['link'])              ?    0    :    mysqli_real_escape_string($con,$_GET['link']);
        $linked_number       =     empty($_GET['linked_number'])     ?    0    :    mysqli_real_escape_string($con,$_GET['linked_number']);
        $linked_article      =     empty($_GET['linked_article'])    ?    0    :    mysqli_real_escape_string($con,$_GET['linked_article']);
       
      

        $sanitize_1 = array($key, $user_id, $post_anyn, $type, $screen_size, $place_id, $place_local_id, $landmark_desc, $category_id, $linked_number, $linked_article);
        $sanitize_2 = array($tags);
        $sanitize_3 = array($headline, $description, $link);

        if( Sanitize::check_sanitize($sanitize_1, 1) || 
            Sanitize::check_sanitize($sanitize_2, 2) || 
            Sanitize::check_sanitize($sanitize_3, 3) || $user_id == 0 || $type == 0 || $place_id == 0 || $headline == '0'){
             response(400,"Invalid Request d",NULL);
             exit();
        }

        $check_key = mysqli_query($con,"SELECT * FROM Api_Keys WHERE CLIENT = '$key'");
        $row_key = mysqli_num_rows($check_key);

        if($row_key == 0){
             response(400,"Invalid Key",NULL);
             exit();
        }


          $article = new AddArticle();
          $data = $article->add_article($user_id, $post_anyn, $type, $screen_size, $place_id, $place_local_id, $landmark_desc, $category_id, $headline, $description, $link, $linked_number, $linked_article, $tags);



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





