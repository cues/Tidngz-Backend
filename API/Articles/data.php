<?php
class Article_Array{
    public $articles = array();
}


class Article
{
    public $articles_id;
    public $articles_type;  
    public $articles_screen;    
    public $articles_title;
    public $articles_content;     
    public $articles_link;
    public $linked_number;   
    public $linked_article;
    public $linked_count;

    public $articles_likes;   
    public $articles_comments;  
    public $articles_comments_data;  

    public $this_liked;
    public $this_commented;
    public $this_bookmarked;
    public $this_reported;

    public $dates;  

    public $option;

    public $user;

    public $tag;

    public $place;
  
    public $image;

    public $video;

}

class Date{
    public $date;  
    public $timestamp;  
}

class Category{
    public $category_id;
    public $category;
}

class User {
    public $user_id;     
    public $username;
    public $user_name;
    public $user_name_initial;
    public $user_image;
    public $user_image_2;
    public $user_image_3;
    public $user_verified;
}

class Tag {
    public $tags_count;
    public $tags = array();
}

class Tags {
    public $tag_id;
    public $tag;
}

class Place {
    public $id;   
    public $google_id;   
    public $name;
    public $county;
    public $province;
    public $country;
    public $flag;
    public $following;
    public $timezone;
    public $lat;
    public $long;

    // public $landmark_id;
    // public $landmark_google_id;
    // public $landmark_name;
    // public $landmark_desc;  
    // public $landmark_county;
    // public $landmark_province;
    // public $landmark_country;
    // public $landmark_flag;
    // // public $landmark_following;
    // public $landmark_timezone;
    // public $landmark_lat;
    // public $landmark_long;
}

class Landmark {
    public $id;
    public $google_id;
    public $name;
    public $desc;  
    public $county;
    public $province;
    public $country;
    public $flag;
    // public $following;
    public $timezone;
    public $lat;
    public $long;
}



class Image {
    public $images_count;
    public $image_id_thumbnail;
    public $image_thumbnail;
    public $images = array();
}

class Images {
    public $image_id;
    public $image;
    public $image_2;
    public $image_3;
    public $image_4;
    public $caption;
}



class Video {
    public $videos_count;
    public $video_id_thumbnail;
    public $video_img_thumbnail;
    public $videos = array();
    public $videos_small = array();
}

class Videos {
    public $video_id;
    public $video;
    public $video_img;
    public $video_img_2;
    public $video_img_3;
    public $video_img_4;
    public $video_title;
    public $video_description;
    public $video_published_at;
    public $video_uploaded_by;
    public $video_duration;
    public $video_time = 0;
}

class Videos_Small {
    public $video_id;
    public $video_img;
}




class Articles extends Db{
 public  function get_article($user_id, $articles_id){

        

        global $users_articles;

        $image_data        =    New ImageData();

        // $user_data         =    New UserData();
        // $users_following   =    $user_data->users_following($user_id);
        // $places_following  =    $user_data->places_following($user_id);
        // $users_blocked     =    $user_data->users_blocked($user_id);

        $new_article = new Article_Array();

        $articles = "SELECT * FROM Articles WHERE ID IN ($articles_id) ORDER BY ID DESC";
        



        $a = 0;
        $this->query($articles);
        $row_articles = $this->result();


        foreach ($row_articles as $article){

                    $new_article->articles[] = new Article();

                    $new_article->articles[$a]->articles_id = (int)$article['ID'];
                    $new_article->articles[$a]->articles_type = (int)$article['TYPE'];
                    $new_article->articles[$a]->articles_screen = (int)$article['SCREEN'];
                    $new_article->articles[$a]->articles_title = $article['TITLE'];
                    $new_article->articles[$a]->articles_content = $article['ARTICLE'];
                    $new_article->articles[$a]->articles_link = strtolower($article['LINK']);
                    $new_article->articles[$a]->linked_number = (int)$article['LINKED_NUMBER'];
                    $linked_article = (int)$article['LINKED_ARTICLE'];
                    $new_article->articles[$a]->linked_article = $linked_article;
                    $new_article->articles[$a]->linked_count = 0;

            
                    

                    // DATE
                    $new_article->articles[$a]->dates = new Date();
                    $this->query("SELECT * FROM users WHERE ID = ?");
                    $this->bind(1,$user_id);
                    $row_user = $this->single();
                    $user_timezone = $row_user['TIMEZONE'];

                    $articles_date = $article['DATE'];
                    
                    $timestamp = strtotime($articles_date) ;
                    $item_date = $timestamp;
                  
                    $dt = new DateTime();
                    $dt->setTimestamp($item_date);
                    $dt->setTimezone(new DateTimeZone($user_timezone));
                    $is = $dt->format('Y-m-d H:i:sP');
                    $ts = strtotime($is);
                    $new_article->articles[$a]->dates->date = $articles_date;
                    $new_article->articles[$a]->dates->timestamp = date('c', $ts);



                    if($new_article->articles[$a]->linked_number != 1){
                        //Linked   
                        $this->query("SELECT * FROM Articles WHERE LINKED_ARTICLE = ? ");
                        $this->bind(1,$linked_article);
                        $new_article->articles[$a]->linked_count = (int)$this->count();
                    }
                    


                    // Likes
                    $this->query("SELECT * FROM Article_Likes WHERE ARTICLE = ?");
                    $this->bind(1,$new_article->articles[$a]->articles_id);
                    $articles_likes = (int)$this->count();

                    $articles_likes =  $articles_likes >= 1000000   ?  number_format($articles_likes/1000000,1) . " m"  :  $articles_likes;
                    $articles_likes =  $articles_likes >= 1000      ?  number_format($articles_likes/1000,1) . " k"     :  $articles_likes;
                            
                    $a_l = strstr($articles_likes, '.');
                    $a_l = strstr($a_l, ' ', true);

                    $new_article->articles[$a]->articles_likes = $a_l == '.0' ? str_replace(".0","","$articles_likes") : $articles_likes;


                    // Comments
                    $this->query("SELECT * FROM Article_Comments WHERE ARTICLE = ?");
                    $this->bind(1,$new_article->articles[$a]->articles_id);
                    $articles_comments = (int)$this->count();

                    $articles_comments =  $articles_comments >= 1000000   ?  number_format($articles_comments/1000000,1) . " m"  :  $articles_comments;
                    $articles_comments =  $articles_comments >= 1000      ?  number_format($articles_comments/1000,1) . " k"     :  $articles_comments;

                    $a_c = strstr($articles_comments, '.');
                    $a_c = strstr($a_c, ' ', true);

                    $new_article->articles[$a]->articles_comments = $a_c == '.0' ? str_replace(".0","","$articles_comments") : $articles_comments;




                    $comments = new AllComments();
                    $new_article->articles[$a]->articles_comments_data = $comments->all_comments($new_article->articles[$a]->articles_id, $user_id);


                    // Category
                    $new_article->articles[$a]->option = new Category();
                    $articles_category_id = $article['CATEGORY'];

                    $this->query("SELECT * FROM Categories WHERE ID = ?");
                    $this->bind(1,$articles_category_id);
                    $row_article_category = $this->single();
                    $articles_category = $row_article_category['CATEGORY'];
                    $ac = strtolower($articles_category);
                    $new_article->articles[$a]->option->category_id = (int)$articles_category_id;
                    $new_article->articles[$a]->option->category = ucwords($ac);



                    // User
                    $new_article->articles[$a]->user = new User();
                    $users_id = $article['USER_ID'];

                    $this->query("SELECT * FROM users WHERE ID = ?");
                    $this->bind(1,$users_id);
                    $row_user = $this->single();
                    $users_name = strtolower($row_user['NAME']);
                    $new_article->articles[$a]->user->user_id = (int)$users_id;
                    $new_article->articles[$a]->user->username = $row_user['USERNAME'];
                    $new_article->articles[$a]->user->user_name = ucwords($users_name);
                    $new_article->articles[$a]->user->user_name_initial = ucfirst($users_name[0]);
                    $new_article->articles[$a]->user->user_verified = (int)$row_user['VERIFIED'];
                    $new_article->articles[$a]->user->user_image    = $image_data->get_user_image($row_user['IMAGE']);
                    $new_article->articles[$a]->user->user_image_2  = $image_data->get_user_image($row_user['IMAGE_2']);
                    $new_article->articles[$a]->user->user_image_3  = $image_data->get_user_image($row_user['IMAGE_3']);

                
                            
                    if($user_id != 0){

                        if($user_id != $users_id){

                                // Bookmarked
                                $this->query("SELECT * from Article_Bookmarks where ARTICLE = ? AND USER = ?");
                                $this->bind(1,$new_article->articles[$a]->articles_id);
                                $this->bind(2,$user_id);
                                $new_article->articles[$a]->this_bookmarked = (int)$this->count();

                                // Reported
                                $this->query("SELECT * from Articles_Reported where ARTICLE = ? AND USER = ?");
                                $this->bind(1,$new_article->articles[$a]->articles_id);
                                $this->bind(2,$user_id);
                                $new_article->articles[$a]->this_reported = (int)$this->count();

                                // Liked
                                $this->query("SELECT * from Article_Likes where ARTICLE = ? AND USER = ? ");
                                $this->bind(1,$new_article->articles[$a]->articles_id);
                                $this->bind(2,$user_id);
                                $new_article->articles[$a]->this_liked = (int)$this->count();
                        }

                                // Commented
                                $this->query("SELECT * from Article_Comments where ARTICLE = ? AND USER = ? ");
                                $this->bind(1,$new_article->articles[$a]->articles_id);
                                $this->bind(2,$user_id);
                                $new_article->articles[$a]->this_commented = (int)$this->count();
                     
                    }
                       


                
                    // Tags
                    $new_article->articles[$a]->tag = new Tag();

                    $this->query("SELECT * FROM Article_Tags WHERE ARTICLE = ? ORDER BY POSITION ASC");
                    $this->bind(1,$new_article->articles[$a]->articles_id);
                    $new_article->articles[$a]->tag->tags_count =  (int)$this->count();
                    $t_id = 0;
                    $array_tags = $this->result();
                    foreach ($array_tags as $tag){
                            $new_article->articles[$a]->tag->tags[] = new Tags();
                            $new_article->articles[$a]->tag->tags[$t_id]->tag_id = (int)$tag['ID'];
                            $new_article->articles[$a]->tag->tags[$t_id]->tag = $tag['TAG'];
                            $t_id++;
                    }

                

                    // Place
                    $new_article->articles[$a]->place = new Place();

                    $image_data        =    New ImageData();
                    
                    $place_id = $article['PLACE'];
            
                    $this->query("SELECT * FROM Places WHERE ID = ?");
                    $this->bind(1,$place_id);

                    $row_place = $this->single();
                    $place_name = $row_place['PLACE'];
                    $place_county = $row_place['COUNTY'];
                    $place_province = $row_place['PROVINCE'];
                    $place_country = $row_place['COUNTRY'];
                    $place_timezone  =  $row_place['TIMEZONE'];
                    $place_lat  =  $row_place['LATITUDE'];
                    $place_long =  $row_place['LONGITUDE'];

                    $place_country = str_ireplace("+" , "'", $place_country);
                    $place_province = str_ireplace("+" , "'", $place_province);
                    $place_county = str_ireplace("+" , "'", $place_county);
                    $place_name = str_ireplace("+" , "'", $place_name);

                    $place_name = str_ireplace("<" , ",", $place_name);
                    $place_county = str_ireplace("<" , ",", $place_county);
                    $place_province = str_ireplace("<" , ",", $place_province);
                    $place_country = str_ireplace("<" , ",", $place_country);

                    $new_article->articles[$a]->place->id = (int)$place_id;
                    $new_article->articles[$a]->place->google_id = $row_place['PLACE_ID'];
                    $new_article->articles[$a]->place->name = $place_name;
                    $new_article->articles[$a]->place->county = $place_county;
                    $new_article->articles[$a]->place->province = $place_province;
                    $new_article->articles[$a]->place->country = $place_country;
                    $new_article->articles[$a]->place->flag =  $image_data->get_country_flag($place_country, $place_province);
                    $new_article->articles[$a]->place->timezone = $place_timezone;
                    $new_article->articles[$a]->place->lat = $place_lat;
                    $new_article->articles[$a]->place->long = $place_long;

                    $this->query("SELECT * from Places_Following WHERE USER = ?  AND Place_ID = ?");
                    $this->bind(1, $user_id);
                    $this->bind(2, $place_id);
                    
                    $new_article->articles[$a]->place->following = $this->count() == 1 ? true : false;



                    // Landmark
                    $new_article->articles[$a]->landmark = new Landmark();

                    $articles_place_landmark_id = (int)$article['PLACE_LOCAL'];

                    $this->query("SELECT * FROM Places_Landmark WHERE ID = ?");
                    $this->bind(1,$articles_place_landmark_id);
                    $row_place_landmark = $this->single();
                    $landmark_name = $row_place_landmark['PLACE'];
                    $landmark_county = $row_place_landmark['COUNTY'];
                    $landmark_province = $row_place_landmark['PROVINCE'];
                    $landmark_country = $row_place_landmark['COUNTRY'];
                    $landmark_timezone  =  $row_place_landmark['TIMEZONE'];
                    $landmark_lat  =  $row_place_landmark['LATITUDE'];
                    $landmark_long =  $row_place_landmark['LONGITUDE'];

                    $landmark_country = str_ireplace("+" , "'", $landmark_country);
                    $landmark_province = str_ireplace("+" , "'", $landmark_province);
                    $landmark_county = str_ireplace("+" , "'", $landmark_county);
                    $landmark_name = str_ireplace("+" , "'", $landmark_name);

                    $landmark_county = str_ireplace("<" , ",", $landmark_county);
                    $landmark_county = str_ireplace("<" , ",", $landmark_county);
                    $landmark_province = str_ireplace("<" , ",", $landmark_province);
                    $landmark_country = str_ireplace("<" , ",", $landmark_country);


                    $landmark_name = str_ireplace("+" , "'", $landmark_name);
                    $landmark_name = str_ireplace("<" , ",", $landmark_name);

                    
                    $new_article->articles[$a]->landmark->id = (int)$articles_place_landmark_id;
                    $new_article->articles[$a]->landmark->google_id = $row_place_landmark['PLACE_ID'];
                    $new_article->articles[$a]->landmark->name = $landmark_name;
                    $new_article->articles[$a]->landmark->desc = $article['PLACE_LOCAL_DESC'];
                    $new_article->articles[$a]->landmark->county = $landmark_county;
                    $new_article->articles[$a]->landmark->province = $landmark_province;
                    $new_article->articles[$a]->landmark->country = $landmark_country;
                    $new_article->articles[$a]->landmark->flag =  $image_data->get_country_flag($landmark_country, $landmark_province);
                    $new_article->articles[$a]->landmark->timezone = $landmark_timezone;
                    $new_article->articles[$a]->landmark->lat = $landmark_lat;
                    $new_article->articles[$a]->landmark->long = $landmark_long;



                    // Images
                    $new_article->articles[$a]->image = new Image();
                    $this->query("SELECT * FROM Article_Images WHERE ARTICLE = ? AND SCREEN  = '1' ORDER BY PLACEMENT ASC");
                    $this->bind(1,$new_article->articles[$a]->articles_id);
                    $array_img_thumbnail = $this->single();
                    $new_article->articles[$a]->image->image_id_thumbnail = (int)$array_img_thumbnail['ID'];
                    $new_article->articles[$a]->image->image_thumbnail = $image_data->get_article_image($array_img_thumbnail['IMAGE']);

                    $this->query("SELECT * FROM Article_Images WHERE ARTICLE = ? ORDER BY PLACEMENT ASC LIMIT 10");
                    $this->bind(1,$new_article->articles[$a]->articles_id);
                    $new_article->articles[$a]->image->images_count = (int)$this->count();
                    $i_id = 0;
                    $row_images = $this->result();
                    foreach ($row_images as $image){
                        $new_article->articles[$a]->image->images[] = new Images();
                        $new_article->articles[$a]->image->images[$i_id]->image_id = (int)$image['ID'];
                        $new_article->articles[$a]->image->images[$i_id]->image    = $image_data->get_article_image($image['IMAGE']);
                        $new_article->articles[$a]->image->images[$i_id]->image_2  = $image_data->get_article_image($image['IMAGE_2']);
                        $new_article->articles[$a]->image->images[$i_id]->image_3  = $image_data->get_article_image($image['IMAGE_3']);
                        $new_article->articles[$a]->image->images[$i_id]->image_4  = $image_data->get_article_image($image['IMAGE_4']);
                        $new_article->articles[$a]->image->images[$i_id]->caption  = $image['CAPTION'];
                        $i_id++;
                    }


                
                    // Videos
                    $new_article->articles[$a]->video = new Video();
                    $this->query("SELECT * FROM Article_Videos WHERE ARTICLE = ? ORDER BY POSITION ASC");
                    $this->bind(1,$new_article->articles[$a]->articles_id);
                    $new_article->articles[$a]->video->videos_count = (int)$this->count();

                    $row_videos_thumbnail = $this->single();
                    $new_article->articles[$a]->video->video_id_thumbnail = (int)$row_videos_thumbnail['ID'];
                    $new_article->articles[$a]->video->video_img_thumbnail = $row_videos_thumbnail['THUMBNAIL_2'];
                    
                    $v_id = 0;
                    $row_videos = $this->result();
                    foreach ($row_videos as $video){
                        $new_article->articles[$a]->video->videos_small[] = new Videos_Small();
                        $new_article->articles[$a]->video->videos_small[$v_id]->video_id      =   (int)$video['ID'];
                        $new_article->articles[$a]->video->videos_small[$v_id]->video_img     =   $video['THUMBNAIL_2'];

                        $new_article->articles[$a]->video->videos[] = new Videos();
                        $new_article->articles[$a]->video->videos[$v_id]->video_id            =   (int)$video['ID'];
                        $new_article->articles[$a]->video->videos[$v_id]->video               =   $video['VIDEO'];
                        $new_article->articles[$a]->video->videos[$v_id]->video_img           =   $video['THUMBNAIL'];
                        $new_article->articles[$a]->video->videos[$v_id]->video_img_1         =   $video['THUMBNAIL_1'];
                        $new_article->articles[$a]->video->videos[$v_id]->video_img_2         =   $video['THUMBNAIL_2'];
                        $new_article->articles[$a]->video->videos[$v_id]->video_img_3         =   $video['THUMBNAIL_3'];
                        $new_article->articles[$a]->video->videos[$v_id]->video_img_4         =   $video['THUMBNAIL_4'];
                        $new_article->articles[$a]->video->videos[$v_id]->video_title         =   $video['TITLE'];
                        $article_video_description = nl2br(htmlentities($video['DESCRIPTION'], ENT_QUOTES, 'UTF-8'));
                        $new_article->articles[$a]->video->videos[$v_id]->video_description   =   $article_video_description;
                        $new_article->articles[$a]->video->videos[$v_id]->video_published_at  =   $video['PUBLISHED_AT'];
                        $new_article->articles[$a]->video->videos[$v_id]->video_uploaded_by   =   $video['UPLOADED_BY'];
                        $new_article->articles[$a]->video->videos[$v_id]->video_duration      =   $video['DURATION'];

                        $this->query("SELECT * FROM Article_Videos_Current_Time WHERE USER = ? AND VIDEO= ?");
                        $this->bind(1,$user_id);
                        $this->bind(2,$new_article->articles[$a]->video->videos[$v_id]->video_id);
                        if($this->count() == 1){
                            $array_video_time = $this->single();
                            $new_article->articles[$a]->video->videos[$v_id]->video_time  = $array_video_time['TIME'];    
                        }
                    
                        $v_id++;
                    }



             $a++;       
        }


                $this->closeConnection();
                return $new_article;

    }


}


