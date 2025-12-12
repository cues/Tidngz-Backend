<?php

class Comments {
    public $count;
    public $headline;
    public $user;
}



class Notification_Comments extends Db {

    public function comments($id, $user_id, $user_2, $articles_id){

        $comments = New Comments();
        $number_format = New NumberFormat();

        $user_data   =  New UserLight();
        $comments->user = $user_data->user_light($user_id, $user_2);

        $this->query("SELECT * FROM Articles WHERE ID = ?");
        $this->bind(1, $articles_id);
        $row_headline = $this->single();

        $comments->headline = $row_headline['TITLE'];
      
        if (strlen($comments->headline) > 50){
            $comments->headline = substr($comments->headline,0,50). ' .......';
        }

        $this->query("SELECT * FROM Article_Comments WHERE ARTICLE = ? AND USER != ?");
        $this->bind(1, $articles_id);
        $this->bind(2, $user_id);
        $row_comments = $this->count();
        
        $comments->count = $number_format->number_format($row_comments);


        return $comments;

    }
}