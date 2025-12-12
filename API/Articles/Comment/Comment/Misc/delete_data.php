<?php
class Delete {
    public $articles_id;
    public $comments_count;
    public $comments_count_2;
    public $delete;
}

class Comments extends Db {
    function delete_comment($comments_id, $user_id){

        $delete = new Delete();

        $this->query("SELECT * FROM Article_Comments WHERE ID = ?");
        $this->bind(1,$comments_id);
        $row_comment = $this->single();
        $user_id_comment = (int)$row_comment['USER'];
        $delete->articles_id = (int)$row_comment['ARTICLE'];

        if($user_id == $user_id_comment){

            $this->query("DELETE FROM Article_Comments WHERE ID = ?");
            $this->bind(1,$comments_id);
            $this->execute();

            $this->query("DELETE FROM Article_Comment_Likes WHERE  COMMENT = ?");
            $this->bind(1,$comments_id);
            $this->execute();

            $this->query("DELETE FROM Article_Comments_Reply WHERE  COMMENT = ?");
            $this->bind(1,$comments_id);
            $this->execute();

            $this->query("DELETE FROM Article_Comment_Reply_Likes WHERE COMMENT = ?");
            $this->bind(1,$comments_id);
            $this->execute();

            $this->query("SELECT * FROM Article_Comments WHERE ARTICLE = ?  ORDER BY ID DESC");
            $this->bind(1,$delete->articles_id);
            $articles_comments = (int)$this->count();
            $row_likes = $this->single();
            $last_user_id = $row_likes['USER'];

            if($articles_comments == 0){

                $this->query("DELETE FROM Notifications  WHERE ARTICLE = ? AND COMMENT = ?");
                $this->bind(1,$delete->articles_id);
                $this->bind(2,1);
                $this->execute();

            }else{

                $this->query("UPDATE Notifications SET USER_2 =  ?, COMMENT_COUNT = ? WHERE ARTICLE = ? AND COMMENT = ?");
                $this->bind(1,$last_user_id);
                $this->bind(2,$articles_comments);
                $this->bind(3,$delete->articles_id);
                $this->bind(4,1);
                $this->execute();

            }


            $this->query("DELETE FROM Notifications  WHERE  COMMENT_ID = ?");
            $this->bind(1,$comments_id);
            $this->execute();


            $delete->delete = 1;


            $delete->comments_count = $articles_comments;

            $articles_comments =  $articles_comments >= 1000000   ?  number_format($articles_comments/1000000,1) . " m"  :  $articles_comments;
            $articles_comments =  $articles_comments >= 1000      ?  number_format($articles_comments/1000,1) . " k"     :  $articles_comments;

            $a_c = strstr($articles_comments, '.');
            $a_c = strstr($a_c, ' ', true);

            $delete->comments_count_2 = $a_c == '.0' ? str_replace(".0","","$articles_comments") : $articles_comments;


           
        }


        $this->closeConnection();
        return $delete;
        
    }
}