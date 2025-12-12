<?php

class Comments extends Db {
    public function report_comment($comments_id , $user_id, $report_number){
        global $date;

   
        $this->query("SELECT * FROM Article_Comments WHERE ID = ?");
        $this->bind(1,$comments_id);
        $count_comment = (int)$this->count();
        $row_comment = $this->single();
        $user_id_comment = (int)$row_comment['USER'];
        $articles_id = (int)$row_comment['ARTICLE'];

        $this->query("SELECT * from Articles WHERE ID = ?");
        $this->bind(1,$articles_id);
        $row_article = $this->single();
        $user_id_article = $row_article['USER_ID'];

        if($count_comment == 1 && $user_id != $user_id_comment && $user_id == $user_id_article){
           
                $report     =   '';
                $report     =      $report_number == 1     ?    'Spam'              :   $report;
                $report     =      $report_number == 2     ?    'Sexual content'    :   $report;
                $report     =      $report_number == 3     ?    'Racism'            :   $report;
                $report     =      $report_number == 4     ?    'Discrimination'    :   $report;
                $report     =      $report_number == 5     ?    'Harassment'        :   $report;
                $report     =      $report_number == 6     ?    'Self Harm'         :   $report;

                $this->query("SELECT * from Article_Comment_Report where USER = ? AND COMMENT = ?");
                $this->bind(1,$user_id);
                $this->bind(2,$comments_id);

                if($this->count() == 0){

                    $this->query("INSERT INTO Article_Comment_Report (USER,USER_REPORTED,COMMENT,TOPIC,DATE) VALUES (?,?,?,?,?)");
                    $this->bind(1,$user_id);
                    $this->bind(2,$user_id_comment);
                    $this->bind(3,$comments_id);
                    $this->bind(4,$report);
                    $this->bind(5,$date);
                    $report = $this->execute();

                    if($report){
                        $this->closeConnection();
                        return '1';
                    }

                }
        
        }
            
        $this->closeConnection();

    }
}