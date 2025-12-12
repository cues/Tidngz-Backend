<?php

class Comment_Array{
    public $comments = array();
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


class Comments extends Db{
    public function get_comments($last_comment_id, $articles_id, $user_id, $records_per_page, $start){
        global $con;

        $image_data        =    New ImageData();

        $all_comments              =    New AllComments();
        $all_comments_replies      =    $all_comments->all_comments_replied($articles_id, $user_id);
        $all_comments_following    =    $all_comments->all_comment_following($articles_id, $user_id);

        $user_data         =    New UserData();
        $users_following   =    $user_data->users_following($user_id);

        $new_comment = New Comment_Array();


        $this->query("SELECT * FROM Articles WHERE ID = ?");
        $this->bind(1,$articles_id);
        $row_comment = $this->single();
        $user_id_article = (int)$row_comment['USER_ID'];


        $c = 0;
        $this->query("SELECT * from Article_Comments where ARTICLE = '$articles_id' AND ID <= '$last_comment_id'  ORDER BY (case when USER = '$user_id' then 1 when USER = '$user_id_article' then 2 when ID in ($all_comments_replies)  then 3 when ID in ($all_comments_following) then 4 else 5 END), FIELD(USER,$users_following),  FIELD(ID,$all_comments_replies), ID DESC  LIMIT $start, $records_per_page " );
        $row_comment = $this->result();
        foreach($row_comment as $comment){
            $new_comment->comments[] = New Comment(); 
            $new_comment->comments[$c]->comment_id = (int)$comment['ID'];
            $new_comment->comments[$c]->comment = $comment['COMMENT'];


              // DATE
              $new_comment->comments[$c]->dates = new Date();
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
              $new_comment->comments[$c]->dates->date = $comment_date;
              $new_comment->comments[$c]->dates->timestamp = date('c', $ts);


                // Like
                $this->query("SELECT * from Article_Comment_Likes where COMMENT = ?");
                $this->bind(1,$new_comment->comments[$c]->comment_id);
                $new_comment->comments[$c]->comment_likes = (int)$this->count();

                // Replies
                $this->query("SELECT * from Article_Comments_Reply where COMMENT = ?") ;
                $this->bind(1,$new_comment->comments[$c]->comment_id);
                $new_comment->comments[$c]->comment_replies = (int)$this->count();


                // User
                $new_comment->comments[$c]->user = new User();
                $users_id = $comment['USER'];

                $this->query("SELECT * FROM users WHERE ID = ?");
                $this->bind(1,$users_id);
                $row_user = $this->single();
                $users_name = strtolower($row_user['NAME']);
                $new_comment->comments[$c]->user->user_id_article = (int)$user_id_article;
                $new_comment->comments[$c]->user->user_id = (int)$users_id;
                $new_comment->comments[$c]->user->username = $row_user['USERNAME'];
                $new_comment->comments[$c]->user->user_name = ucwords($users_name);
                $new_comment->comments[$c]->user->user_name_initial = ucfirst($users_name[0]);
                $new_comment->comments[$c]->user->user_verified = (int)$row_user['VERIFIED'];
                $new_comment->comments[$c]->user->user_image    = $image_data->get_user_image($row_user['IMAGE']);
                $new_comment->comments[$c]->user->user_image_2  = $image_data->get_user_image($row_user['IMAGE_2']);
                $new_comment->comments[$c]->user->user_image_3  = $image_data->get_user_image($row_user['IMAGE_3']);

                          
                
                    if($user_id != 0){

                        if($user_id != $users_id){

                            // Liked
                            $this->query("SELECT * from Article_Comment_Likes where COMMENT = ? AND USER = ?");
                            $this->bind(1,$new_comment->comments[$c]->comment_id);
                            $this->bind(2,$user_id);
                            $new_comment->comments[$c]->this_likes = (int)$this->count();

                            // Replied
                            $this->query("SELECT * from Article_Comment_Reply where COMMENT = ? AND USER = ?");
                            $this->bind(1,$new_comment->comments[$c]->comment_id);
                            $this->bind(2,$user_id);
                            $new_comment->comments[$c]->this_replied = (int)$this->count();

                            // Reported
                            $this->query("SELECT * from Article_Comment_Report where COMMENT = ? AND USER = ?");
                            $this->bind(1,$new_comment->comments[$c]->comment_id);
                            $this->bind(2,$user_id);
                            $new_comment->comments[$c]->this_reported = (int)$this->count();

                        }
                    }

            $c++;
        }

                $this->closeConnection();
                return $new_comment;
    }
}