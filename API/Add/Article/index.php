<?php


require "../../../headers.php";
require "../../../response.php";
require "../../../db_pdo.php";
require "data.php"; 


// http://192.168.68.84/Tidngz/API/Add/Article/?key=1707&user_id=1&title=Ukraine-Russia%20latest%3A%20Trump%20says%20progress%20made%20after%20Ukraine%20peace%20plan%20talks%20but%20%27thorny%20issues%27%20remain&description=&link=https%3A%2F%2Fwww.bbc.com%2Fnews%2Flive%2Fc7732j0jvnnt&youtube=&category_id=4&tags%5B0%5D=goa&tags%5B1%5D=enter&images%5B0%5D%5Buri%5D=https%3A%2F%2Ffirebasestorage.googleapis.com%2Fv0%2Fb%2Ftidngz-286c5.firebasestorage.app%2Fo%2Farticles%252F1767022526282_0.jpg%3Falt%3Dmedia%26token%3Db7ad0190-5b41-4517-bf2e-4b1f69f10990&place_id=614&latitude=53.38093934452019&longitude=-6.29188569335938&poi=24%2C%20eden%2C%20Dublin%2C%20Co.%20Dublin%2C%20D11%20AK59%2C%20Ireland


if(!empty($_GET['key']))
{

        $key                 =     empty($_GET['key'])               ?    0    :    mysqli_real_escape_string($con,$_GET['key']);
        $user_id             =     empty($_GET['user_id'])           ?    0    :    mysqli_real_escape_string($con,$_GET['user_id']);
        $place_id            =     empty($_GET['place_id'])          ?    0    :    mysqli_real_escape_string($con,$_GET['place_id']);
        $category_id         =     empty($_GET['category_id'])       ?    0    :    mysqli_real_escape_string($con,$_GET['category_id']);
        $tags                =     empty($_GET['tags'])              ?    0    :    mysqli_real_escape_string($con,$_GET['tags']);
        $latitude            =     empty($_GET['latitude'])          ?    0    :    mysqli_real_escape_string($con,$_GET['latitude']);
        $longitude           =     empty($_GET['longitude'])         ?    0    :    mysqli_real_escape_string($con,$_GET['longitude']);
        $poi                 =     empty($_GET['poi'])               ?    0    :    mysqli_real_escape_string($con,$_GET['poi']);
        $title               =     empty($_GET['title'])             ?    0    :    mysqli_real_escape_string($con,$_GET['title']);
        $description         =     empty($_GET['description'])       ?    ''   :    mysqli_real_escape_string($con,$_GET['description']);
        $link                =     empty($_GET['link'])              ?    0    :    mysqli_real_escape_string($con,$_GET['link']);
        $youtube             =     empty($_GET['youtube'])           ?    0    :    mysqli_real_escape_string($con,$_GET['youtube']);
       

        $sanitize_1 = array($key, $user_id, $place_id, $category_id, $latitude, $longitude);
        $sanitize_2 = array($tags);
        $sanitize_3 = array($title, $description, $link, $youtube, $poi);

        if( Sanitize::check_sanitize($sanitize_1, 1) || 
            Sanitize::check_sanitize($sanitize_2, 2) || 
            Sanitize::check_sanitize($sanitize_3, 3) || $user_id == 0 || $place_id == 0 || $title == '0'){
             response(400,"Invalid Request",NULL);
             exit();
        }

        if(!APIKey::check_key($con, $key)){
             response(400,"Invalid Key",NULL);
             exit();
        }


        $article = new AddArticle();
        $data = $article->add_article($user_id, $place_id, $category_id, $title, $description, $link, $youtube, $tags, $latitude, $longitude, $poi );



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





