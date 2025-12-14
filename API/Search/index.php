<?php


require "../../headers.php";
require "../../response.php";
require "../../db_pdo.php";
require "data_all.php"; 
require "data_users.php"; 
require "data_places.php"; 
require "data_tags.php"; 
require "data_history.php"; 
require "data_suggestions.php"; 

require "../Place/data.php";
require "../User/data.php";
require "../Tag/data.php";

require "../../images.php";
require "../../user_data.php";

  
if(!empty($_GET['key']))
{
   


        $key          =     empty($_GET['key'])               ?    0    :    mysqli_real_escape_string($con,$_GET['key']);
        $type         =     empty($_GET['type'])              ?    0    :    mysqli_real_escape_string($con,$_GET['type']);
        $search       =     empty($_GET['query'])             ?    1    :    mysqli_real_escape_string($con,$_GET['query']);
        $user_id      =     empty($_GET['user_id'])           ?    0    :    mysqli_real_escape_string($con,$_GET['user_id']);

 
        
        $search = str_ireplace("'" , "+", $search);
        $search = str_ireplace("," , "<", $search);

        $sanitize_1 = array($key, $type, $user_id);
        $sanitize_3 = array($search);

        if( Sanitize::check_sanitize($sanitize_1, 1) || 
            Sanitize::check_sanitize($sanitize_3, 3) ){
             response(400,"Invalid Request",NULL);
             exit();
        }

        if(!APIKey::check_key($con, $key)){
             response(400,"Invalid Key",NULL);
             exit();
        }


    

    if($type == 'search'){

        $search_ = New Search();
        $data = $search_->get_search($user_id, $search);

    }
    else if($type == 'history'){

        $search_ = New Search_History();
        $data = $search_->get_search($user_id);

    }
    else if($type == 'suggestions'){

        $search_ = New Search_Suggestions();
        // Using combined suggestions for better results (personalized + trending + collaborative filtering)
        $data = $search_->get_combined_suggestions($user_id);

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





