<?php

class Bookmark extends Db {

    public function bookmark_info($user_id, $articles_id){
        global $date;

        $this->query("SELECT * from Articles WHERE ID = ?");
        $this->bind(1,$articles_id);
        $row_article = $this->single();
        $category = $row_article['CATEGORY'];
        
        $this->query("SELECT * from Article_Bookmarks where USER = ? AND ARTICLE = ?");
        $this->bind(1,$user_id);
        $this->bind(2,$articles_id);

        if($this->count() == 0){

            $this->query("INSERT INTO Article_Bookmarks (USER, ARTICLE, CATEGORY, DATE) VALUES (?,?,?,?)");
            $this->bind(1,$user_id);
            $this->bind(2,$articles_id);
            $this->bind(3,$category);
            $this->bind(4,$date);
            $this->execute();
            $this->closeConnection();

            return "1";   

        }else{
            $this->query("DELETE FROM Article_Bookmarks WHERE USER = ? AND ARTICLE = ?");
            $this->bind(1,$user_id);
            $this->bind(2,$articles_id);
            $this->execute();
            $this->closeConnection();
            
            return "2";   
        }


    }

}