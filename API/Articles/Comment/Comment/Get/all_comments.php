<?php

class CommentIds {
    public $total_records;
    public $last_comment_id;
    public $comment_ids;
}



Class AllComments extends Db{

    

    public function all_comments($articles_id, $user_id){
    
        $all_comments_replies      =    $this->all_comments_replied($articles_id, $user_id);
        $all_comments_following    =    $this->all_comment_following($articles_id, $user_id);
    

        $user_data         =    New UserData();
        $users_following   =    $user_data->users_following($user_id);

        $all_comments = new CommentIds();

        $this->query("SELECT * FROM Articles WHERE ID = ?");
        $this->bind(1,$articles_id);
        $row_comment = $this->single();
        $user_id_article = (int)$row_comment['USER_ID'];


        $this->query("SELECT * from Article_Comments where ARTICLE = '$articles_id' ORDER BY (case when USER = '$user_id' then 1 when USER = '$user_id_article' then 2 when ID in ($all_comments_replies)  then 3 when ID in ($all_comments_following) then 4 else 5 END), FIELD(USER,$users_following),  FIELD(ID,$all_comments_replies), ID DESC " );
        $all_comments->total_records = (int)$this->count();

        if($all_comments->total_records  > 0){
            $ids = '';
            $array_comments = $this->result();
            foreach($array_comments as $comment){
                $ids = $ids == '' ? (int)$comment['ID'] : $ids . ',' . (int)$comment['ID'];
            } 

            $all_comments->comment_ids = $ids;


            $this->query("SELECT * from Article_Comments where ARTICLE = '$articles_id' ORDER BY ID DESC " );
            $last_array = $this->single();
            $all_comments->last_comment_id = (int)$last_array['ID'];
        }
    
        $this->closeConnection();

        return $all_comments;
    
    }




    public function all_comments_replied($articles_id, $user_id){
        global $con;
        
        $ids = array();

        $this->query("SELECT * from Article_Comments_Reply where ARTICLE = ? AND USER = ? GROUP BY COMMENT ORDER BY COUNT(COMMENT) DESC, ID DESC ");
        $this->bind(1,$articles_id);
        $this->bind(2,$user_id);

        if($this->count() > 0){
            $array_comments_reply = $this->result();
            foreach($array_comments_reply as $comments_reply){
                 array_push($ids, (int)$comments_reply['ID']);
            } 
        }
       
        $ids = implode(',',$ids);
        $ids = !$ids ? 0 : $ids;

        $this->closeConnection();

        return $ids;

    }






    function all_comment_following($articles_id, $user_id){
        global $con;
      
        $ids = array();
        $user_data          =   New UserData();
        $users_following    =   $user_data->users_following($user_id);
      
        $this->query("SELECT * from Article_Comments where ARTICLE = ? AND USER NOT LIKE (?) AND USER IN ($users_following) ORDER BY ID DESC ") ;
        $this->bind(1,$articles_id);
        $this->bind(2,$user_id);

        if($this->count() > 0){
            $array_comments_following = $this->result();
            foreach($array_comments_following as $comments_following){
                array_push($ids, (int)$comments_following['ID']);

            }
        }

        $ids = implode(',',$ids);
        $ids = !$ids ? 0 : $ids;

        $this->closeConnection();
         
         return $ids;
      
    }
      
      
}