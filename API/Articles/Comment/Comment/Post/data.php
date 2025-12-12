<?php

class Comment_Array{
    public $articles_comments;
    public $articles_comments_data;
    public $comment;
}

class Comment {
    public $comment_id;
    public $comment;
    public $comment_likes;
    public $comment_replies;
    public $this_likes;
    public $this_replied;
    public $this_reported;
    public $dates;
    public $user;
}

class Date{
    public $date;  
    public $timestamp;  
}


class User {
    public $user_id_article;     
    public $user_id;     
    public $username;
    public $user_name;
    public $user_name_initial;
    public $user_image;
    public $user_image_2;
    public $user_image_3;
    public $user_verified;
}



class Comments extends Db {
    public function add_comment($user_id, $articles_id, $comment){
            global $date;

            $image_data                      =    New ImageData();
            $new_comment                     =    New Comment_Array();
            $new_comment->comment            =    New Comment();
            $new_comment->comment->dates     =    New Date();
            $new_comment->comment->user      =    New User();

            $this->query("SELECT * FROM Article_Comments WHERE ARTICLE = ? AND USER = ?");
            $this->bind(1,$articles_id);
            $this->bind(2,$user_id);

            if($this->count() == 0){

                $this->query("SELECT * FROM Articles WHERE ID = ?");
                $this->bind(1,$articles_id);
                $row_user_article = $this->single();
                $user_id_article = $row_user_article['USER_ID'];

                $this->query("INSERT INTO Article_Comments (USER,USER_ARTICLE,ARTICLE,COMMENT,DATE) VALUES (?,?,?,?,?)");
                $this->bind(1,$user_id);
                $this->bind(2,$user_id_article);
                $this->bind(3,$articles_id);
                $this->bind(4,$comment);
                $this->bind(5,$date);
                $add = $this->execute();

                if($user_id_article != $user_id){

                    $this->query("SELECT * FROM Article_Comments WHERE ARTICLE = ?");
                    $this->bind(1,$articles_id);
                    $row_article_comments = $this->count();

                    $this->query("SELECT * FROM Notifications WHERE ARTICLE = ? AND COMMENT = ?");
                    $this->bind(1,$articles_id);
                    $this->bind(2,1);

                    if($this->count() == 0){

                        $this->query("INSERT INTO Notifications (USER,USER_2,ARTICLE,COMMENT,COMMENT_COUNT,DATE,VIEWED_OUTER) VALUES (?,?,?,?,?,?,?)");
                        $this->bind(1,$user_id_article);
                        $this->bind(2,$user_id);
                        $this->bind(3,$articles_id);
                        $this->bind(4,1);
                        $this->bind(5,$row_article_comments);
                        $this->bind(6,$date);
                        $this->bind(7,0);
                        $this->execute();

                    }else{

                        $this->query("UPDATE Notifications SET USER_2 = ? , COMMENT_COUNT = ? , DATE = ?, VIEWED_OUTER = ? WHERE ARTICLE = ? AND COMMENT = ?");
                        $this->bind(1,$user_id);
                        $this->bind(2,$row_article_comments);
                        $this->bind(3,$date);
                        $this->bind(4,0);
                        $this->bind(5,$articles_id);
                        $this->bind(6,1);
                        $this->execute();

                    }
                  
                }



                $this->query('SELECT * FROM Article_Comments WHERE ARTICLE = ? AND USER = ?');
                $this->bind(1,$articles_id);
                $this->bind(2,$user_id);
                $comment = $this->single();


                $new_comment->comment->comment_id = (int)$comment['ID'];
                $new_comment->comment->comment = $comment['COMMENT'];

                $this->query("SELECT * FROM users WHERE ID = ?");
                $this->bind(1,$user_id);
                $row_user = $this->single();
                $user_timezone = $row_user['TIMEZONE'];

                $comment_date = $comment['DATE'];
                
                $timestamp = strtotime($comment_date) ;
                $item_date = $timestamp;
                
                $dt = new DateTime();
                $dt->setTimestamp($item_date);
                $dt->setTimezone(new DateTimeZone($user_timezone));
                $is = $dt->format('Y-m-d H:i:sP');
                $ts = strtotime($is);
                $new_comment->comment->dates->date = $comment_date;
                $new_comment->comment->dates->timestamp = date('c', $ts);

                // User
            
                $users_id = $comment['USER'];

                $this->query("SELECT * FROM users WHERE ID = ?");
                $this->bind(1,$users_id);
                $row_user = $this->single();
                $users_name = strtolower($row_user['NAME']);
                $new_comment->comment->user->user_id_article = (int)$user_id_article;
                $new_comment->comment->user->user_id = (int)$users_id;
                $new_comment->comment->user->username = $row_user['USERNAME'];
                $new_comment->comment->user->user_name = ucwords($users_name);
                $new_comment->comment->user->user_name_initial = ucfirst($users_name[0]);
                $new_comment->comment->user->user_verified = (int)$row_user['VERIFIED'];
                $new_comment->comment->user->user_image    = $image_data->get_user_image($row_user['IMAGE']);
                $new_comment->comment->user->user_image_2  = $image_data->get_user_image($row_user['IMAGE_2']);
                $new_comment->comment->user->user_image_3  = $image_data->get_user_image($row_user['IMAGE_3']);



                // Comments
                $this->query("SELECT * FROM Article_Comments WHERE ARTICLE = ?");
                $this->bind(1,$articles_id);
                $articles_comments = (int)$this->count();

                $articles_comments =  $articles_comments >= 1000000   ?  number_format($articles_comments/1000000,1) . " m"  :  $articles_comments;
                $articles_comments =  $articles_comments >= 1000      ?  number_format($articles_comments/1000,1) . " k"     :  $articles_comments;

                $a_c = strstr($articles_comments, '.');
                $a_c = strstr($a_c, ' ', true);

                $new_comment->articles_comments = $a_c == '.0' ? str_replace(".0","","$articles_comments") : $articles_comments;

                $comments = new AllComments();
                $new_comment->articles_comments_data = $comments->all_comments($articles_id, $user_id);


            }


            $this->closeConnection();
             
            return $new_comment;

    }
}