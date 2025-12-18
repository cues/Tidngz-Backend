<?php

class SearchUsers {
    public $count;
    public $items = array();
}

class Items_Users {
    public $type = 'USER';
    public $item;
}



class Search_User extends Db {

    public function get_search($key, $user_id, $search, $start, $total){

        $new_search        =    New SearchUsers();

        $image_data        =    New ImageData();

        $user_data         =    New UserData();
        $users_blocked     =    $user_data->users_blocked($user_id);



        $this->query("SELECT * from users where (NAME LIKE '$search%' OR USERNAME LIKE '$search%') AND ID NOT LIKE '$user_id'  AND ID NOT IN ($users_blocked) order by NAME LIMIT $start, $total");
        $row_users = $this->result();

        $new_search->count = (int)$this->count();

        if($new_search->count > 0){

            $u = 0;

            foreach ($row_users as $row_user){

                $new_search->items[$u] = New Items_Users();

                // $users_name = strtolower($row_user['NAME']);

                $user = New User();
                $new_search->items[$u]->item = $user->get_user($user_id, (int)$row_user['ID']);


                // $new_search->items[$u]->item->key               = (int)$row_user['ID'] . $key;
                // $new_search->items[$u]->item->user_id           = (int)$row_user['ID'];
                // $new_search->items[$u]->item->username          = $row_user['USERNAME'];
                // $new_search->items[$u]->item->user_name         = ucwords($users_name);
                // $new_search->items[$u]->item->user_name_initial = ucfirst($users_name[0]);
                // $new_search->items[$u]->item->user_image        = $image_data->get_user_image($row_user['IMAGE_3']);
                // $new_search->items[$u]->item->user_verified     = (int)$row_user['VERIFIED'];
                // $new_search->items[$u]->item->user_sex          = $row_user['SEX'];
            

                // $this->query("SELECT * FROM User_Follow WHERE USER_ID = ? AND USER_FOLLOWING_ID = ?");
                // $this->bind(1, $user_id);
                // $this->bind(2, $new_search->items[$u]->item->user_id);


                // $new_search->items[$u]->item->user_following = $this->count() == 1 ? true : false;

                $u++;
            }

        }

        $this->closeConnection();
        return $new_search;

    }
}