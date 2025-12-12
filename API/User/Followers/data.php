<?php

class Users {
    public $usersCount;
    public $user = array();
}

class User {
    public $user_id;     
    public $username;
    public $user_name;
    public $user_name_initial;
    public $user_image;
    public $user_image_2;
    public $user_image_3;
    public $user_verified;
}

class FollowersUsers extends Db{
    public function followers($user_id){

        $users   =   New Users();
        $image_data  =   New ImageData();

        $following = New UserData();
        $users_following = $following->users_following($user_id);

        $this->query("SELECT * from User_Follow  where USER_FOLLOWING_ID = ? AND USER_ID NOT LIKE ? ORDER BY (case when USER_ID = '1' then 1  when USER_ID IN ($users_following) then 2 else 3 END), FIELD(USER_ID,$users_following), ID DESC");
        $this->bind(1, $user_id);
        $this->bind(2, $user_id);

        $users->usersCount = $this->count();

        $all_users = $this->result();
 
        $i = 0;

        foreach($all_users as $all_user){

            $users_id = $all_user['USER_ID'];

            $users->user[$i] = New User();

            $this->query("SELECT * FROM users WHERE ID = ?");
            $this->bind(1,$users_id);
            $row_user = $this->single();
            $users_name = strtolower($row_user['NAME']);
            $users->user[$i]->user_id = (int)$users_id;
            $users->user[$i]->username = $row_user['USERNAME'];
            $users->user[$i]->user_name = ucwords($users_name);
            $users->user[$i]->user_name_initial = ucfirst($users_name[0]);
            $users->user[$i]->user_verified = (int)$row_user['VERIFIED'];
            $users->user[$i]->user_image    = $image_data->get_user_image($row_user['IMAGE']);
            $users->user[$i]->user_image_2  = $image_data->get_user_image($row_user['IMAGE_2']);
            $users->user[$i]->user_image_3  = $image_data->get_user_image($row_user['IMAGE_3']);

            $i++;

        }

        $this->closeConnection();

        return $users;

    }
}






