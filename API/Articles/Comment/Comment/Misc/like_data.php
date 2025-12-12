<?php
class Likes {
    public $like;
    public $like_count;
}


class Comments extends Db {
    function like_comment($comments_id, $user_id){
        global $date;

        $this->query("SELECT * FROM Article_Comments WHERE ID = ?");
        $this->bind(1,$comments_id);
        $row_comment = $this->single();
        $user_id_comment = (int)$row_comment['USER'];
        $articles_id = (int)$row_comment['ARTICLE'];

        $likes = New Likes();

        if($user_id == $user_id_comment){
            $likes->like = 0;
            $likes->like_count = 0;
        }else{

            $this->query("SELECT * from Article_Comment_Likes where COMMENT = ? AND USER = ?");
            $this->bind(1,$comments_id);
            $this->bind(2,$user_id);

            if($this->count() == 0){
                
                $this->query("INSERT INTO Article_Comment_Likes (USER,ARTICLE,COMMENT,DATE) VALUES (?,?,?,?)");
                $this->bind(1,$user_id);
                $this->bind(2,$articles_id);
                $this->bind(3,$comments_id);
                $this->bind(4,$date);
                $add = $this->execute();

                if($add){

                    $this->query("SELECT * FROM Article_Comment_Likes WHERE COMMENT = ?");
                    $this->bind(1,$comments_id);
                    $comments_likes = (int)$this->count();


                    $this->query("SELECT * FROM Notifications WHERE COMMENT_ID = ? AND COMMENT_LIKES = ?");
                    $this->bind(1,$comments_id);
                    $this->bind(2,1);

                    if($this->count() == 0){

                        $this->query("INSERT INTO Notifications (USER,USER_2,ARTICLE,COMMENT_ID,COMMENT_LIKES,COMMENT_LIKES_COUNT,DATE,VIEWED_OUTER) VALUES (?,?,?,?,?,?,?,?)");
                        $this->bind(1,$user_id_comment);
                        $this->bind(2,$user_id);
                        $this->bind(3,$articles_id);
                        $this->bind(4,$comments_id);
                        $this->bind(5,1);
                        $this->bind(6,1);
                        $this->bind(7,$date);
                        $this->bind(8,0);
                        $noti = $this->execute();


                    }else{

                        $this->query("UPDATE Notifications SET USER_2 = ? , COMMENT_LIKES_COUNT = ? ,  DATE = ?, VIEWED_OUTER = ? WHERE COMMENT_ID = ? AND COMMENT_LIKES = ?");
                        $this->bind(1,$user_id);
                        $this->bind(2,$comments_likes);
                        $this->bind(3,$date);
                        $this->bind(4,0);
                        $this->bind(5,$comments_id);
                        $this->bind(6,1);
                        $noti = $this->execute();

                    }


                    if($noti){
                        $likes->like = 1;
                    }


                }

            }
            else
            {
                $this->query("DELETE FROM Article_Comment_Likes WHERE COMMENT = ? AND USER = ?");
                $this->bind(1,$comments_id);
                $this->bind(2,$user_id);
                $delete = $this->execute();

                if($delete){

                    $this->query("SELECT * FROM Article_Comment_Likes WHERE COMMENT = ?");
                    $this->bind(1,$comments_id);
                    $comments_likes = (int)$this->count();

                    if($this->count() == 0){

                        $this->query("DELETE FROM Notifications WHERE COMMENT_ID = ? AND COMMENT_LIKES = ?");
                        $this->bind(1,$comments_id);
                        $this->bind(2,1);
                        $noti = $this->execute();

                    }else{

                        $this->query("SELECT * FROM Article_Comment_Likes WHERE  COMMENT = ? ORDER BY ID DESC");
                        $this->bind(1,$comments_id);
                        $row_likes = $this->single();
                        $last_user_id = $row_likes['USER'];

                        $this->query("UPDATE Notifications SET USER_2 = ?,  COMMENT_LIKES_COUNT = ? WHERE COMMENT_ID = ? AND COMMENT_LIKES = ?");
                        $this->bind(1,$last_user_id);
                        $this->bind(2,$comments_likes);
                        $this->bind(3,$comments_id);
                        $this->bind(4,1);
                        $noti = $this->execute();

                    }

                    if($noti){
                        $likes->like = 2;
                    }


                }

            }






            $comments_likes =  $comments_likes >= 1000000   ?  number_format($comments_likes/1000000,1) . " m"  :  $comments_likes;
            $comments_likes =  $comments_likes >= 1000      ?  number_format($comments_likes/1000,1) . " k"     :  $comments_likes;
                    
            $c_l = strstr($comments_likes, '.');
            $c_l = strstr($c_l, ' ', true);
        
            $likes->like_count = $c_l == '.0' ? str_replace(".0","","$comments_likes") : $comments_likes;


            
        }

        
        $this->closeConnection();

        return $likes;

    }
}