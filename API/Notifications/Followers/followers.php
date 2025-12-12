<?php

class Followers {
    public $user;
}



class Notification_Followers extends Db {

    public function followers($id, $user_id, $user_2){

        $followers = New Followers();

        $user_data   =  New UserLight();
        $followers->user = $user_data->user_light($user_id, $user_2);

        return $followers;

    }
}