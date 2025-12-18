<?php

class SearchPlaces{
    public $count;
    public $items = array();
}

class Items_Places {
    public $type = 'PLACE';
    public $item;
}



class Search_Place extends Db{
    public function get_search($key, $user_id, $search, $start, $total){

        $new_search        =    New SearchPlaces();

        $image_data        =    New ImageData();

        $user_data         =    New UserData();


        $this->query("SELECT * from Places where PLACE LIKE '$search%' order by PLACE LIMIT $start, $total");
        $row_places = $this->result();

        $new_search->count = (int)$this->count();

        if($new_search->count > 0){
            $p = 0;

            foreach ($row_places as $row_place){

                $new_search->items[$p] = New Items_Places();

                $new_search->items[$p]->item = New PlaceData(); 

                $place = New Place();

                $new_search->items[$p]->item = $place->get_place($user_id, (int)$row_place['ID']);
       
                $p++;

            }

        }
        $this->closeConnection();
        return $new_search;
    }
}