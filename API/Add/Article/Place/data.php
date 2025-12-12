<?php

class Place {
    public $placesCount;
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


class SuggestedPlaces extends Db{
    public function suggested_places($user_id){

        $places   =   New Place();
        $image_data  =   New ImageData();

        $this->query("SELECT  *  FROM Articles WHERE  USER_ID = ? AND ACCEPTED = '1' AND FAKE_PN = '0' AND ID IN(SELECT MAX(ID) FROM Articles WHERE ACCEPTED = '1' AND FAKE_PN = '0' GROUP BY PLACE) ORDER BY ID DESC LIMIT 20");
        $this->bind(1, $user_id);
        $places->placesCount = $this->count();
        $suggested_places  = $this->result();

        $i = 0;

        foreach ($suggested_places as $suggested_place){

            $place_id = $suggested_place['PLACE'];

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









class SearchPlace extends Db{
    public function search_place($search){


        $places   =   New Place();
        $image_data  =   New ImageData();

        $this->query("SELECT * FROM Places WHERE PLACE LIKE '$search%' ORDER BY PLACE LIMIT 0 ,100");
        $places->placesCount = $this->count();
        $row_places  = $this->result();

        $i = 0;

        foreach ($row_places as $row_place){

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