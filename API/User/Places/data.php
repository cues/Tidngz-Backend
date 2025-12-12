<?php

class Place {
    public $placesCount;
    public $placesSearchCount;
    public $placeName = array();
}

class PlaceData {
    public $id;
    public $name;
    public $county;
    public $province;
    public $country;
    public $flag;
}


class FollowingPlaces extends Db{
    public function following_places($user_id){

        $places   =   New Place();
        $image_data  =   New ImageData();

        $this->query("SELECT * FROM Places_Following WHERE USER = ? ORDER BY PLACE_NAME");
        $this->bind(1, $user_id);
        $places->placesCount = $this->count();
        $suggested_places  = $this->result();

        $i = 0;

        foreach ($suggested_places as $suggested_place){

            $place_id = $suggested_place['PLACE_ID'];

            $this->query("SELECT * FROM Places WHERE ID= ?");
            $this->bind(1, $place_id);
            $row_place  = $this->single();

            $places->placeName[$i]  =  New PlaceData();

            $place_id = $row_place['ID'];
            $place = $row_place['PLACE'];
            $country = $row_place['COUNTRY'];
            $province = $row_place['PROVINCE'];
            $county = $row_place['COUNTY'];

            $place = str_ireplace('<', ',', $place);
            $county = str_ireplace('<', ',', $county);
            $province = str_ireplace('<', ',', $province);
            $country = str_ireplace('<', ',', $country);

            $place = str_ireplace('+', "'", $place);
            $county = str_ireplace('+', "'", $county);
            $province = str_ireplace('+', "'", $province);
            $country = str_ireplace('+', "'", $country);

            $places->placeName[$i]->id          =  $place_id;
            $places->placeName[$i]->name        =  $place;
            $places->placeName[$i]->county      =  $county;
            $places->placeName[$i]->province    =  $province;
            $places->placeName[$i]->country     =  $country;
            $places->placeName[$i]->flag        =  $image_data->get_country_flag($country, $province);

            $i++;
        
        }
        

        return $places; 

    }
}









class SearchPlaces extends Db{
    public function search_places($user_id, $search){


        $places   =   New Place();
        $image_data  =   New ImageData();

        $this->query("SELECT * FROM Places_Following WHERE USER = ? ORDER BY PLACE_NAME");
        $this->bind(1, $user_id);
        $places->placesCount = $this->count();

        $this->query("SELECT * FROM Places_Following WHERE USER= ? AND PLACE_NAME LIKE '$search%' ORDER BY PLACE_NAME ");
        $this->bind(1, $user_id);
        $places->placesSearchCount = $this->count();
        $search_places  = $this->result();

        $i = 0;

        foreach ($search_places as $search_place){

            $place_id = $search_place['PLACE_ID'];

            $this->query("SELECT * FROM Places WHERE ID= ?");
            $this->bind(1, $place_id);
            $row_place  = $this->single();

            $places->placeName[$i]  =  New PlaceData();

            $place_id = $row_place['ID'];
            $place = $row_place['PLACE'];
            $country = $row_place['COUNTRY'];
            $province = $row_place['PROVINCE'];
            $county = $row_place['COUNTY'];

            $place = str_ireplace('<', ',', $place);
            $county = str_ireplace('<', ',', $county);
            $province = str_ireplace('<', ',', $province);
            $country = str_ireplace('<', ',', $country);

            $place = str_ireplace('+', "'", $place);
            $county = str_ireplace('+', "'", $county);
            $province = str_ireplace('+', "'", $province);
            $country = str_ireplace('+', "'", $country);

            $places->placeName[$i]->id          =  $place_id;
            $places->placeName[$i]->name        =  $place;
            $places->placeName[$i]->county      =  $county;
            $places->placeName[$i]->province    =  $province;
            $places->placeName[$i]->country     =  $country;
            $places->placeName[$i]->flag        =  $image_data->get_country_flag($country, $province);

            $i++;
        
        }
        

        return $places; 

    }
}                                                                                                                                                                                         