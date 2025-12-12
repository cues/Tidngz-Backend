<?php

class SearchData{
    public $places_1;
    public $users_1;
    public $tags_1;
    public $places_2;
    public $users_2;
    public $tags_2;
    public $places_3;
    public $users_3;
    public $tags_3;

    public $places;
    public $users;
    public $tags;
}

class Search {

    public function get_search($user_id, $search){

        
        $new_search = New SearchData();

        $places = New Search_Place();
        $new_search->places_1 = $places->get_search(1, $user_id, $search, 0, 3);

        $users = New Search_User();
        $new_search->users_1 = $users->get_search(2, $user_id, $search, 0, 3);

        $tags = New Search_Tag();
        $new_search->tags_1 = $tags->get_search(3, $user_id, $search, 0, 3);

        
        $places = New Search_Place();
        $new_search->places_2 = $places->get_search(1, $user_id, $search, 3, 3);

        $users = New Search_User();
        $new_search->users_2 = $users->get_search(2, $user_id, $search, 3, 3);

        $tags = New Search_Tag();
        $new_search->tags_2 = $tags->get_search(3, $user_id, $search, 3, 3);


        $places = New Search_Place();
        $new_search->places_3 = $places->get_search(1, $user_id, $search, 6, 3);

        $users = New Search_User();
        $new_search->users_3 = $users->get_search(2, $user_id, $search, 6, 3);

        $tags = New Search_Tag();
        $new_search->tags_3 = $tags->get_search(3, $user_id, $search, 6, 3);





        $places = New Search_Place();
        $new_search->places = $places->get_search(11, $user_id, $search, 0, 15);

        $users = New Search_User();
        $new_search->users = $users->get_search(22, $user_id, $search, 0, 15);

        $tags = New Search_Tag();
        $new_search->tags = $tags->get_search(33, $user_id, $search, 0, 20);

        // $this->closeConnection();
        return $new_search;

    }

}

