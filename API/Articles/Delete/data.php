<?php

class Article_Delete extends Db {
    public function delete_info($user_id, $articles_id){

        $this->query("SELECT * FROM Articles WHERE ID = ?");
        $this->bind(1,$articles_id);
        $row_article_user = $this->single();
        $article_user_id = $row_article_user['USER_ID'];

        if($user_id == $article_user_id){
            $this->query("UPDATE Articles SET FAKE_PN = ? WHERE ID = ?");
            $this->bind(1,2);
            $this->bind(2,$articles_id);
            $delete = $this->execute();

            $this->query("DELETE FROM Notifications where ARTICLE = ?");
            $this->bind(1,$articles_id);
            $delete_noti = $this->execute();

            
            if($delete && $delete_noti){
                $this->closeConnection();
                return "1";      
            }
        }

        $this->closeConnection();
    }
}