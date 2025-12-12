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
    public function suggested_places($place_id){

        $places   =   New Place();
        $image_data  =   New ImageData();

        $this->query("SELECT  *  FROM Articles WHERE PLACE = '$place_id' AND PLACE_LOCAL NOT LIKE '0'  AND ACCEPTED = '1' AND FAKE_PN = '0' GROUP BY PLACE_LOCAL ORDER BY COUNT(*) DESC, ID  DESC LIMIT 1500");
        $this->bind(1, $place_id);
        $places->placesCount = $this->count();
        $suggested_places  = $this->result();

        $i = 0;

        foreach ($suggested_places as $suggested_place){

            $place_id = $suggested_place['PLACE_LOCAL'];

            $this->query("SELECT * FROM Places_Landmark WHERE ID= ?");
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







