<?php

require "../../../headers.php";
require "../../../response.php";
require "../../../db_pdo.php";
require "data.php";
require "../data_users.php"; 
require "../data_places.php"; 
require "../data_tags.php"; 
require "../data_history.php";

require "../../Place/data.php";
require "../../User/data.php";
require "../../Tag/data.php";

require "../../../images.php";
require "../../../user_data.php";

if(!empty($_GET['key']))
{   
            
    
        $key          =     empty($_GET['key'])               ?    0    :    mysqli_real_escape_string($con,$_GET['key']);
        $user_id      =     empty($_GET['user_id'])           ?    0    :    mysqli_real_escape_string($con,$_GET['user_id']);
        $type         =     empty($_GET['type'])              ?    0    :    mysqli_real_escape_string($con,$_GET['type']);
        $search_item     =     empty($_GET['search_item'])          ?    0    :    mysqli_real_escape_string($con,$_GET['search_item']);
        $search_item_id      =     empty($_GET['search_item_id'])           ?    0    :    mysqli_real_escape_string($con,$_GET['search_item_id']);
 
        

        $sanitize_1 = array($key, $type, $user_id, $search_item, $search_item_id);

        if( Sanitize::check_sanitize($sanitize_1, 1) ){
             response(400,"Invalid Request",NULL);
             exit();
        }

        if(!APIKey::check_key($con, $key)){
             response(400,"Invalid Key",NULL);
             exit();
        }

   

        $add_search = New AddSearch();
        $data = $add_search->add_search($user_id, $search_item, $search_item_id);

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


