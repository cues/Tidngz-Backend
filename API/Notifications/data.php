<?php

class Notifications_Array {
    public $notifications = array();
}

class Notification{
    public $notifications_id;     
    public $user_2;               
    public $articles_id;               
    public $comment_id;           
    public $reply_id;            
    public $likes;               
    public $comment;      
    public $comment_likes;        
    public $comment_reply;        
    public $reply_likes; 
    public $followers;     
    public $data;         
    public $notification_date;   
    public $notification_timestamp;   
    public $viewed;     
    
}

class Notifications extends Db {
    public function get_notifications($user_id, $start, $records_per_page, $notification_ids){

        $notifications                      =  New Notifications_Array();
        $notification_likes                 =  New Notification_Likes();
        $notification_comments              =  New Notification_Comments();
        $notification_comment_likes         =  New Notification_Comment_Likes();
        $notification_comment_reply         =  New Notification_Comment_Reply();
        $notification_comment_reply_likes   =  New Notification_Comment_Reply_Likes();
        $notification_followers             =  New Notification_Followers();

       
        $this->query("SELECT * FROM Notifications WHERE ID IN ($notification_ids) ORDER BY ID DESC LIMIT $start,$records_per_page");                                                                                                 
        $row_notifications = $this->result();


        $this->query("SELECT * FROM users WHERE ID = ?");
        $this->bind(1,$user_id);
        $row_user = $this->single();
        $user_timezone = $row_user['TIMEZONE'];

        $i = 0;
        foreach($row_notifications as $row_notification){

            $notifications->notifications[$i] = New Notification();

            $notifications->notifications[$i]->notifications_id     = (int)$row_notification['ID'];
            $notifications->notifications[$i]->user_2               = (int)$row_notification['USER_2'];
            $notifications->notifications[$i]->articles_id          = (int)$row_notification['ARTICLE'];
            $notifications->notifications[$i]->likes                = (int)$row_notification['LIKES'];
            $notifications->notifications[$i]->comment              = (int)$row_notification['COMMENT'];
            $notifications->notifications[$i]->comment_id           = (int)$row_notification['COMMENT_ID'];
            $notifications->notifications[$i]->comment_likes        = (int)$row_notification['COMMENT_LIKES'];
            $notifications->notifications[$i]->comment_reply        = (int)$row_notification['COMMENT_REPLY'];
            $notifications->notifications[$i]->reply_id             = (int)$row_notification['REPLY_ID'];
            $notifications->notifications[$i]->reply_likes          = (int)$row_notification['REPLY_LIKES'];
            $notifications->notifications[$i]->followers            = (int)$row_notification['FOLLOWERS'];
            $notifications->notifications[$i]->notification_date    = $row_notification['DATE'];
            $notifications->notifications[$i]->viewed               = (int)$row_notification['VIEWED'];


            $timestamp = strtotime($notifications->notifications[$i]->notification_date) ;
            $item_date = $timestamp;

            $dt = new DateTime();
            $dt->setTimestamp($item_date);
            $dt->setTimezone(new DateTimeZone($user_timezone));
            $is = $dt->format('Y-m-d H:i:sP');
            $ts = strtotime($is) ;

            $notifications->notifications[$i]->notification_timestamp = date('c', $ts);


            if($notifications->notifications[$i]->likes == 1){
                $notifications->notifications[$i]->data =  $notification_likes->likes($notifications->notifications[$i]->notifications_id,
                                                                                                    $user_id, 
                                                                                                    $notifications->notifications[$i]->user_2,
                                                                                                    $notifications->notifications[$i]->articles_id);
            }

            if($notifications->notifications[$i]->comment == 1){
                $notifications->notifications[$i]->data =  $notification_comments->comments($notifications->notifications[$i]->notifications_id,
                                                                                                    $user_id, 
                                                                                                    $notifications->notifications[$i]->user_2,
                                                                                                    $notifications->notifications[$i]->articles_id);
            }


            if($notifications->notifications[$i]->comment_likes == 1){
                $notifications->notifications[$i]->data =  $notification_comment_likes->comment_likes($notifications->notifications[$i]->notifications_id,
                                                                                                                    $user_id, 
                                                                                                                    $notifications->notifications[$i]->user_2,
                                                                                                                    $notifications->notifications[$i]->articles_id, 
                                                                                                                    $notifications->notifications[$i]->comment_id);
            }


            if($notifications->notifications[$i]->comment_reply == 1){
                $notifications->notifications[$i]->data =  $notification_comment_reply->comment_reply($notifications->notifications[$i]->notifications_id,
                                                                                                                    $user_id, 
                                                                                                                    $notifications->notifications[$i]->user_2,
                                                                                                                    $notifications->notifications[$i]->articles_id, 
                                                                                                                    $notifications->notifications[$i]->comment_id);
            }


            if($notifications->notifications[$i]->reply_likes == 1){
                $notifications->notifications[$i]->data =  $notification_comment_reply_likes->comment_reply_likes($notifications->notifications[$i]->notifications_id,
                                                                                                                    $user_id, 
                                                                                                                    $notifications->notifications[$i]->user_2,
                                                                                                                    $notifications->notifications[$i]->articles_id, 
                                                                                                                    $notifications->notifications[$i]->reply_id);
            }


            if($notifications->notifications[$i]->followers == 1){
                $notifications->notifications[$i]->data =  $notification_followers->followers($notifications->notifications[$i]->notifications_id,
                                                                                                                    $user_id, 
                                                                                                                    $notifications->notifications[$i]->user_2 );
            }



            $i++;

        }
        
        return $notifications;
    }
}