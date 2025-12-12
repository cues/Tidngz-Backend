<?php

class NotificationIds {
    public $total_records;
    public $last_notifications_id;
    public $notification_ids;
}



Class AllNotifications extends Db{

    public function all_notifications($user_id){

        $all_notifications = new NotificationIds();


        $this->query("SELECT * FROM Notifications WHERE USER = ? ORDER BY ID DESC");
        $this->bind(1,$user_id);                                                                                                               
        $all_notifications->total_records =(int)$this->count();

        if($all_notifications->total_records > 0){

            $last_array = $this->single();
            $all_notifications->last_notifications_id = (int)$last_array['ID'];

            $ids = '';
            $array_notifications = $this->result();
            foreach($array_notifications as $notification){
                $ids = $ids == '' ? (int)$notification['ID'] : $ids . ',' . (int)$notification['ID'];
            } 

            $all_notifications->notification_ids = $ids;                                                                                                
        }
    
        $this->closeConnection();

        return $all_notifications;
    
    }
}