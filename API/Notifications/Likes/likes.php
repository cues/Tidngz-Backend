<?php

class Likes {
    public $count;
    public $headline;
    public $user;
}
   

class Notification_Likes extends Db {

    public function likes($id, $user_id, $user_2, $articles_id){

        $likes = New Likes();
        $number_format = New NumberFormat();

        $user_data   =  New UserLight(); 
        $likes->user = $user_data->user_light($user_id, $user_2);
      
        $this->query("SELECT * FROM Articles WHERE ID = ?");
        $this->bind(1, $articles_id);
        $row_headline = $this->single();

        $likes->headline = $row_headline['TITLE'];
      
        if (strlen($likes->headline) > 50){
            $likes->headline = substr($headline,0,50). ' .......';
        }
      
        $this->query("SELECT * FROM Article_Likes WHERE ARTICLE = ? ");
        $this->bind(1, $articles_id);
        $row_likes = $this->count();

        $likes->count = $number_format->number_format($row_likes);

        return $likes;
    }
}