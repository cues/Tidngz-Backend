<?php

Class PlaceData extends Db{
    public function all_places($place_id){

      // return 1;

        $this->query("SELECT * FROM Places WHERE ID = ?");
        $this->bind(1, $place_id);
        $row_place = $this->single();
        $place_name = $row_place['PLACE'];
        $place_country = $row_place['COUNTRY'];

        $ids = array();

        $this->query("SELECT * FROM Places WHERE (PLACE = ? OR COUNTY  = ? OR PROVINCE  = ? OR COUNTRY = ?) AND COUNTRY = ?");
        $this->bind(1,$place_name);
        $this->bind(2,$place_name);
        $this->bind(3,$place_name);
        $this->bind(4,$place_name);
        $this->bind(5,$place_country);

        if($this->count() > 0){
          $array_places = $this->result();
          foreach($array_places as $place){
            array_push($ids, (int)$place['ID']);
          }
        }

        $ids = implode(',',$ids);
        $ids = !$ids ? 0 : $ids;

          return $ids;

    }
}