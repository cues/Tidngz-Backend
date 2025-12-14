<?php

class SearchHistory {
    public $count;
    public $items = array();
}

class Items {
    public $type;
    public $item;
}

class Search_History extends Db{

    public function get_search($user_id){

        
        $new_search = New SearchHistory();


        $this->query("SELECT DISTINCT SEARCH_ITEM, SEARCH_ITEM_ID FROM Search_History WHERE USER = ? AND ACTIVE = '0' ORDER BY ID DESC LIMIT 50");
        $this->bind(1, $user_id);
        $row_history = $this->result();

        $new_search->count =  $this->count();

        if($new_search->count > 0){

            $i = 0;
            foreach($row_history as $history){
                $search_item     =  $history['SEARCH_ITEM'];
                $search_item_id  =  $history['SEARCH_ITEM_ID'];

                if($search_item == 'USER' &&   $search_item_id == $user_id ){
                }else{

                        $new_search->items[$i] = New Items();

                        $new_search->items[$i]->type = $search_item;

                        $new_search_history = New Search_Type();

                        if($search_item == 'PLACE'){
                            $place = New Place();
                            $new_search->items[$i]->item = $place->get_place($user_id, (int)$search_item_id);
                        }
                        if($search_item == 'USER'){
                            $user = New User();
                            $new_search->items[$i]->item = $user->get_user($user_id, (int)$search_item_id);
                        }
                        if($search_item == 'TAG'){
                            $tag = New Tag();
                            $new_search->items[$i]->item = $tag->get_tag($user_id, (int)$search_item_id);
                        }

                        $i++;
                 }
            }

        }

        $this->closeConnection();
        return $new_search;

    }

}




class Search_Type extends Db {

    // public function search_place_type($key, $user_id, $search_item_id){

    //     $new_place    =   New PlaceData();
    //     $image_data   =   New ImageData();

    //     $this->query("SELECT * from Places  WHERE ID = ?");
    //     $this->bind(1, $search_item_id);

    //     $row_place = $this->single();

    //     $place     =  $row_place['PLACE'];
    //     $county    =  $row_place['COUNTY'];
    //     $province  =  $row_place['PROVINCE'];
    //     $country   =  $row_place['COUNTRY'];
    //     $timezone  =  $row_place['TIMEZONE'];
    //     $latitude  =  $row_place['LATITUDE'];
    //     $longitude =  $row_place['LONGITUDE'];

    //     $place     =  str_ireplace("+" , "'", $place);
    //     $place     =  str_ireplace("<" , ",", $place);
    //     $county    =  str_ireplace("+" , "'", $county);
    //     $county    =  str_ireplace("<" , ",", $county);
    //     $province  =  str_ireplace("+" , "'", $province);
    //     $province  =  str_ireplace("<" , ",", $province);
    //     $country   =  str_ireplace("+" , "'", $country);
    //     $country   =  str_ireplace("<" , ",", $country);


    //     $new_place->key               =  (int)$row_place['ID'] . $key;
    //     $new_place->id          =  (int)$row_place['ID'];
    //     $new_place->google_id   =  $row_place['PLACE_ID'];
    //     $new_place->name        =  $place;
    //     $new_place->county      =  $county;
    //     $new_place->province    =  $province;
    //     $new_place->country     =  $country;
    //     $new_place->flag        =  $image_data->get_country_flag($country, $province);
    //     $new_place->timezone    =  $timezone;
    //     $new_place->lat         =  $latitude;
    //     $new_place->long        =  $longitude;

    //     $this->query("SELECT * from Places_Following WHERE USER = ?  AND Place_ID = ?");
    //     $this->bind(1, $user_id);
    //     $this->bind(2, $new_place->id);
        
    //     $new_place->following = $this->count() == 1 ? true : false;

    //     return $new_place;

    // }



    // public function search_user_type($key, $user_id, $search_item_id){

    //     $user           =   New User();
    //     $image_data     =   New ImageData();
    //     $user_data      =   New UserData();
    //     $users_blocked  =   $user_data->users_blocked($user_id);


    //     $this->query("SELECT * from users where ID = ? AND ID NOT IN ($users_blocked)");
    //     $this->bind(1, $search_item_id);
    //     $row_user = $this->single();

    //     if($this->count() > 0){
            
    //         $users_name = strtolower($row_user['NAME']);

    //         $user->key               = (int)$row_user['ID'] . $key;
    //         $user->user_id           = (int)$row_user['ID'];
    //         $user->username          = $row_user['USERNAME'];
    //         $user->user_name         = ucwords($users_name);
    //         $user->user_name_initial = ucfirst($users_name[0]);
    //         $user->user_image        = $image_data->get_user_image($row_user['IMAGE_3']);
    //         $user->user_verified     = (int)$row_user['VERIFIED'];
    //         $user->user_sex          = $row_user['SEX'];
        

    //         $this->query("SELECT * FROM User_Follow WHERE USER_ID = ? AND USER_FOLLOWING_ID = ?");
    //         $this->bind(1, $user_id);
    //         $this->bind(2, $user->user_id);


    //         $user->user_following = $this->count() == 1 ? true : false;


    //     }


    //     return $user;

    // }




    // public function search_tag_type($key, $search_item_id){

    //     $tag = New Tag();

    //     $this->query("SELECT * from Article_Tags where ID = ? ");
    //     $this->bind(1, $search_item_id);
    //     $row_tag = $this->single();

    //     $tag->tag      =  $row_tag['TAG'];

    //     $this->query("SELECT * from Article_Tags where TAG = ?");
    //     $this->bind(1, $tag->tag);
    //     $single_tag = $this->single();
    //     $tag->tag_id   =  (int)$single_tag['ID'];
    //     $tag->key      =  (int)$single_tag['ID'] . $key;


    //     return $tag;

    // }

}