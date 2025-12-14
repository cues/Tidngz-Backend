<?php


class PlaceData{
    // public $key;   
    public $id;   
    public $google_id;   
    public $name;
    public $county_id;
    public $county;
    public $province_id;
    public $province;
    public $country_id;
    public $country;
    public $flag;
    public $following;
    public $timezone;
    public $lat;
    public $long;
    public $followers;
}




class Place extends Db{
    public function get_place($user_id, $place_id){

        $new_place   =   New PlaceData();
        $image_data   =   New ImageData();

        $this->query("SELECT * from Places WHERE ID = ?");
        $this->bind(1, $place_id);

        $row_place = $this->single();

        $place     =  $row_place['PLACE'];
        $county    =  $row_place['COUNTY'];
        $province  =  $row_place['PROVINCE'];
        $country   =  $row_place['COUNTRY'];
        $timezone  =  $row_place['TIMEZONE'];
        $latitude  =  $row_place['LATITUDE'];
        $longitude =  $row_place['LONGITUDE'];

        $this->query("SELECT * from Places WHERE PLACE = ? AND COUNTY = ? AND COUNTRY = ?");
        $this->bind(1, $county);
        $this->bind(2, $county);
        $this->bind(3, $country);
        $row_county = $this->single();
        $new_place->county_id = (int)$row_county['ID']; 

        $this->query("SELECT * from Places WHERE PLACE = ? AND PROVINCE = ? AND COUNTRY = ?");
        $this->bind(1, $province);
        $this->bind(2, $province);
        $this->bind(3, $country);
        $row_province = $this->single();
        $new_place->province_id = (int)$row_province['ID']; 

        $this->query("SELECT * from Places WHERE PLACE = ? AND COUNTRY = ?");
        $this->bind(1, $country);
        $this->bind(2, $country);
        $row_country = $this->single();
        $new_place->country_id = (int)$row_country['ID']; 

        $place     =  str_ireplace("+" , "'", $place);
        $place     =  str_ireplace("<" , ",", $place);
        $county    =  str_ireplace("+" , "'", $county);
        $county    =  str_ireplace("<" , ",", $county);
        $province  =  str_ireplace("+" , "'", $province);
        $province  =  str_ireplace("<" , ",", $province);
        $country   =  str_ireplace("+" , "'", $country);
        $country   =  str_ireplace("<" , ",", $country);

        // $new_place->key         =  (int)$row_place['ID'];
        $new_place->id          =  (int)$row_place['ID'];
        $new_place->google_id   =  $row_place['PLACE_ID'];
        $new_place->name        =  $place;
        $new_place->county      =  $county;
        $new_place->province    =  $province;
        $new_place->country     =  $country;
        $new_place->flag        =  $image_data->get_country_flag($country, $province);
        $new_place->timezone    =  $timezone;
        $new_place->lat         =  $latitude;
        $new_place->long        =  $longitude;

        $this->query("SELECT * from Places_Following WHERE USER = ?  AND Place_ID = ?");
        $this->bind(1, $user_id);
        $this->bind(2, $new_place->id);
        
        $new_place->following = $this->count() == 1 ? true : false;

        $this->query("SELECT * from Places_Following WHERE Place_ID = ?");
        $this->bind(1, $new_place->id);
        $new_place->followers = (int)$this->count();


        return $new_place;

    }
}

