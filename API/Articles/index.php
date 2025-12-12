<?php

require "../../headers.php";
require "../../response.php"; 
require "../../db_pdo.php";

require "data.php"; 
require "all_articles.php";
require "../../user_data.php";
require "../../place_data.php";

require "../../images.php";

require "Comment/Comment/Get/all_comments.php"; 
    


if(!empty($_GET['key']))
{

        $key                 =     empty($_GET['key'])               ?    0    :    mysqli_real_escape_string($con,$_GET['key']);
        $type                =     empty($_GET['type'])              ?    0    :    mysqli_real_escape_string($con,$_GET['type']);
        $last_articles_id    =     empty($_GET['last_articles_id'])  ?    1    :    mysqli_real_escape_string($con,$_GET['last_articles_id']);
        $user_id             =     empty($_GET['user_id'])           ?    0    :    mysqli_real_escape_string($con,$_GET['user_id']);
        $article_source      =     empty($_GET['article_source'])    ?    11   :    mysqli_real_escape_string($con,$_GET['article_source']);
        $place_id            =     empty($_GET['place_id'])          ?    0    :    mysqli_real_escape_string($con,$_GET['place_id']);
        $place_local_id      =     empty($_GET['place_local_id'])    ?    0    :    mysqli_real_escape_string($con,$_GET['place_local_id']);
        $tag                 =     empty($_GET['tag'])               ?    0    :    mysqli_real_escape_string($con,$_GET['tag']);
        $articles_id         =     empty($_GET['articles_id'])       ?    0    :    mysqli_real_escape_string($con,$_GET['articles_id']);
        $user_1              =     empty($_GET['user_1'])            ?    0    :    mysqli_real_escape_string($con,$_GET['user_1']);
        $options_id          =     empty($_GET['options_id'])        ?    0    :    mysqli_real_escape_string($con,$_GET['options_id']);
        $top                 =     empty($_GET['top'])               ?    0    :    mysqli_real_escape_string($con,$_GET['top']);
        $calender_1          =     empty($_GET['calender_1'])        ?    0    :    mysqli_real_escape_string($con,$_GET['calender_1']);
        $date_1              =     empty($_GET['date_1'])            ?    0    :    mysqli_real_escape_string($con,$_GET['date_1']);
        $calender_2          =     empty($_GET['calender_2'])       ?    0    :    mysqli_real_escape_string($con,$_GET['calender_2']);
        $date_2              =     empty($_GET['date_2'])            ?    0    :    mysqli_real_escape_string($con,$_GET['date_2']);
        $records_per_page    =     empty($_GET['records_per_page'])  ?    0    :    mysqli_real_escape_string($con,$_GET['records_per_page']);
        $start               =     empty($_GET['start'])             ?    0    :    mysqli_real_escape_string($con,$_GET['start']);


        $article_limit = 2000;
        $users_articles = "USER_ID = '$user_id' AND ACCEPTED = '1' AND FAKE_PN = '0'";

        // response(400,"Invalid Request",$users_articles);
        //      exit();

        $sanitize_1 = array($key, $type, $last_articles_id, $user_id, $article_source, $place_id, $place_local_id,
                        $articles_id, $user_1, $options_id, $top, $calender_1, $date_1, $calender_2, $date_2, $records_per_page, $start);
        $sanitize_2 = array($tag);

        if( Sanitize::check_sanitize($sanitize_1, 1) || 
            Sanitize::check_sanitize($sanitize_2, 2) ){
             response(400,"Invalid Request",NULL);
             exit();
        }

        $check_key = mysqli_query($con,"SELECT * FROM Api_Keys WHERE CLIENT = '$key'");
        $row_key = mysqli_num_rows($check_key);

        if($row_key == 0){
             response(400,"Invalid Key",NULL);
             exit();
        }



// 

    if($type == 1){
        
          $articles = new AllArticles();
          $data = $articles->all_articles($user_id, $article_source, $place_id, $place_local_id, $tag, 
          $articles_id, $user_1, $options_id, $top, $calender_1, $date_1, $calender_2, $date_2);
        // response(200,"Success",11);

    }
    else if($type == 2){


        $articles = new Articles();
        $data = $articles->get_article($user_id, $articles_id);

        // response(200,"Success",11);
        // exit();
    }
    else if($type == 3){
        // Find similar articles
        // Required: articles_id (the article to find similar ones for)
        
        if(empty($articles_id) || $articles_id == 0){
            response(400,"Article ID is required",NULL);
            exit();
        }

        $articles = new AllArticles();
        $limit = !empty($records_per_page) && $records_per_page > 0 ? $records_per_page : 10;
        $data = $articles->find_similar_articles_simple($articles_id, $limit);

    }
    else if($type == 4){
        // Merge similar articles into one new article
        // Required: articles_id (the article to find similar ones for and merge)
        // Optional: user_id (the user who will own the merged article)
        
        if(empty($articles_id) || $articles_id == 0){
            response(400,"Article ID is required",NULL);
            exit();
        }

        $articles = new AllArticles();
        $data = $articles->merge_similar_articles($articles_id, $user_id);

    }
    else if($type == 5){
        // Auto-merge all similar articles in the database (batch operation)
        // Optional: user_id (the user who will own merged articles)
        // Optional: options_id (minimum similarity count, default: 2)
        
        $articles = new AllArticles();
        $min_count = !empty($options_id) && $options_id > 0 ? $options_id : 2;
        $data = $articles->auto_merge_all_similar_articles($user_id, $min_count);

    }
    else
    {
        response(400,"Invalid Request",NULL);
        exit();
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





