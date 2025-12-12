<?php

class Comment_Reply_Likes {
    public $count;
    public $headline;
    public $user;
}



class Notification_Comment_Reply_Likes  extends Db {

    public function comment_reply_likes($id, $user_id, $user_2, $articles_id, $reply_id){


        $comment_reply_likes = New Comment_Reply_Likes();
        $number_format = New NumberFormat();

        $user_data   =  New UserLight();
        $comment_reply_likes->user = $user_data->user_light($user_id, $user_2);


        $this->query("SELECT * FROM Article_Comments_Reply WHERE ID = ?");
        $this->bind(1, $reply_id);
        $row_comment = $this->single();
        $comment_reply_likes->headline = $row_comment['REPLY'];
      
        if (strlen($comment_reply_likes->headline) > 50){
          $comment_reply_likes->headline = substr($comment_reply_likes->headline,0,50). ' .......';
        }

        $this->query("SELECT * FROM Article_Comments_Reply_Likes WHERE REPLY = ? AND USER = ?");
        $this->bind(1, $reply_id);
        $this->bind(2, $user_id);
        $row_likes = $this->count();

        $comment_reply_likes->count = $number_format->number_format($row_likes);

        return $comment_reply_likes;

    }
}