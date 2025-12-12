<?php

class Comment_Reply {
    public $count;
    public $headline;
    public $user;
}



class Notification_Comment_Reply extends Db {

    public function comment_reply($id, $user_id, $user_2, $articles_id, $comment_id){


        $comment_reply = New Comment_Reply();
        $number_format = New NumberFormat();

        $user_data   =  New UserLight();
        $comment_reply->user = $user_data->user_light($user_id, $user_2);
        
        $this->query("SELECT * FROM Article_Comments WHERE ID = ?");
        $this->bind(1, $comment_id);
        $row_comment = $this->single();
        $comment_reply->headline = $row_comment['COMMENT'];
      
        if (strlen($comment_reply->headline) > 50){
          $comment_reply->headline = substr($comment_reply->headline,0,50). ' .......';
        }
      
        $user_all = $user_2 . ',' . $user_id;
      
        $this->query("SELECT DISTINCT(USER) FROM Article_Comments_Reply WHERE COMMENT= ? AND USER NOT IN ($user_all)");
        $this->bind(1, $comment_id);
        $comment_reply_count = $this->count();

        $comment_reply->count = $number_format->number_format($comment_reply_count);

        return $comment_reply;

    }
}