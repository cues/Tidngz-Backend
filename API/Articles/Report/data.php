<?php

class Article_Report extends Db {
    public function report_info($user_id, $articles_id, $report_number){
        global $date;

        $this->query("SELECT * from Articles WHERE ID = ?");
        $this->bind(1,$articles_id);
        $row_article = $this->single();
        $user_id_article = $row_article['USER_ID'];

        if($this->count() == 1 && $user_id != $user_id_article){
           
                $report = '';
                $report     =      $report_number == 1     ?    'Fake News'         :   $report;
                $report     =      $report_number == 2     ?    'Sexual content'    :   $report;
                $report     =      $report_number == 3     ?    'Racism'            :   $report;
                $report     =      $report_number == 4     ?    'Discrimination'    :   $report;
                $report     =      $report_number == 5     ?    'Gore'              :   $report;
                $report     =      $report_number == 6     ?    'Self Harm'         :   $report;
                $report     =      $report_number == 7     ?    'Selfie'            :   $report;

                $this->query("SELECT * from Articles_Reported where USER = ? AND ARTICLE = ?");
                $this->bind(1,$user_id);
                $this->bind(2,$articles_id);

                if($this->count() == 0){

                    $this->query("INSERT INTO Articles_Reported (USER,USER_REPORTED,ARTICLE,TOPIC,DATE,VIEWED) VALUES (?,?,?,?,?,?)");
                    $this->bind(1,$user_id);
                    $this->bind(2,$user_id_article);
                    $this->bind(3,$articles_id);
                    $this->bind(4,$report);
                    $this->bind(5,$date);
                    $this->bind(6,0);
                    $report = $this->execute();

                    $this->query("DELETE FROM Article_Bookmarks WHERE USER = ? AND ARTICLE = ? ");
                    $this->bind(1,$user_id);
                    $this->bind(2,$articles_id);
                    $bookmark = $this->execute();

                    if($report && $bookmark){
                        $this->closeConnection();
                        return '1';
                    }

                }
        
        }
            
        $this->closeConnection();

    }
}