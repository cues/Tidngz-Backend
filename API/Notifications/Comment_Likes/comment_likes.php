<?php

class Comment_Likes {
    public $count;
    public $headline;
    public $user;
}




class Notification_Comment_Likes extends Db {

    public function comment_likes($id, $user_id, $user_2, $articles_id, $comment_id){


        $comment_likes = New Comment_Likes();
        $number_format = New NumberFormat();
    
        $user_data   =  New UserLight();
        $comment_likes->user = $user_data->user_light($user_id, $user_2);

        $this->query("SELECT * FROM Article_Comments WHERE ID = ?");
        $this->bind(1, $comment_id);
        $row_comment = $this->single();
        $comment_likes->headline = $row_comment['COMMENT'];
      
        if (strlen($comment_likes->headline) > 50){
          $comment_likes->headline = substr($comment_likes->headline,0,50). ' .......';
        }

        $this->query("SELECT * FROM Article_Comments_Likes WHERE COMMENT = ? AND USER != ?");
        $this->bind(1, $comment_id);
        $this->bind(2, $user_id);
        $row_comment_likes = $this->count();

        $comment_likes->count = $number_format->number_format($row_comment_likes);

        return $comment_likes;

    }
}