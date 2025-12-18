<?php


class PlaceData{
    // public $key;   
    public $id;   
    public $google_id;   
    public $google_url;   
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
    public $lat_delta;
    public $long_delta;
    public $followers;
}




class Place extends Db{
    public function get_place($user_id, $place_id){

        $new_place   =   New PlaceData();
        $image_data   =   New ImageData();

        $this->query("SELECT * from Places WHERE ID = ?");
        $this->bind(1, $place_id);

        $row_place = $this->single();

        // single() returns false when no row found — guard against that to avoid PHP warnings
        if(!$row_place){
            return NULL;
        }

    // Normalize missing fields to safe defaults to avoid deprecated warnings
    $place     =  isset($row_place['PLACE']) ? $row_place['PLACE'] : '';
    $county    =  isset($row_place['COUNTY']) ? $row_place['COUNTY'] : '';
    $province  =  isset($row_place['PROVINCE']) ? $row_place['PROVINCE'] : '';
    $country   =  isset($row_place['COUNTRY']) ? $row_place['COUNTRY'] : '';
    $timezone  =  isset($row_place['TIMEZONE']) ? $row_place['TIMEZONE'] : '';
    $latitude  =  isset($row_place['LATITUDE']) ? $row_place['LATITUDE'] : 0;
    $longitude =  isset($row_place['LONGITUDE']) ? $row_place['LONGITUDE'] : 0;

        $this->query("SELECT * from Places WHERE PLACE = ? AND COUNTY = ? AND COUNTRY = ?");
        $this->bind(1, $county);
        $this->bind(2, $county);
        $this->bind(3, $country);
    $row_county = $this->single();
    $new_place->county_id = isset($row_county['ID']) ? (int)$row_county['ID'] : 0;

        $this->query("SELECT * from Places WHERE PLACE = ? AND PROVINCE = ? AND COUNTRY = ?");
        $this->bind(1, $province);
        $this->bind(2, $province);
        $this->bind(3, $country);
    $row_province = $this->single();
    $new_place->province_id = isset($row_province['ID']) ? (int)$row_province['ID'] : 0;

        $this->query("SELECT * from Places WHERE PLACE = ? AND COUNTRY = ?");
        $this->bind(1, $country);
        $this->bind(2, $country);
    $row_country = $this->single();
    $new_place->country_id = isset($row_country['ID']) ? (int)$row_country['ID'] : 0;

        // $place     =  str_ireplace("+" , "'", $place);
        // $place     =  str_ireplace("<" , ",", $place);
        // $county    =  str_ireplace("+" , "'", $county);
        // $county    =  str_ireplace("<" , ",", $county);
        // $province  =  str_ireplace("+" , "'", $province);
        // $province  =  str_ireplace("<" , ",", $province);
        // $country   =  str_ireplace("+" , "'", $country);
        // $country   =  str_ireplace("<" , ",", $country);

        // $new_place->key         =  (int)$row_place['ID'];
        $new_place->id          =  (int)$row_place['ID'];
        $new_place->google_id   =  $row_place['PLACE_ID'];
        $new_place->google_url  =  $row_place['URL'];
        $new_place->name        =  $place;
        $new_place->county      =  $county;
        $new_place->province    =  $province;
        $new_place->country     =  $country;
        $new_place->flag        =  $image_data->get_country_flag($country, $province);
        $new_place->timezone    =  $timezone;
        $new_place->lat         =  $latitude;
        $new_place->long        =  $longitude;
        $new_place->lat_delta   =  $row_place['LATITUDE_DELTA'];
        $new_place->long_delta  =  $row_place['LONGITUDE_DELTA'];

        $this->query("SELECT * from Places_Following WHERE USER = ?  AND Place_ID = ?");
        $this->bind(1, $user_id);
        $this->bind(2, $new_place->id);
        
        $new_place->following = $this->count() == 1 ? true : false;

        $this->query("SELECT * from Places_Following WHERE Place_ID = ?");
        $this->bind(1, $new_place->id);
        $new_place->followers = (int)$this->count();


        $this->closeConnection();
        return $new_place;

    }
}

