<?php

class Weather {
    public $isEmpty;
    public $weatherTime;
    public $astro;
    public $hourly;
}





class PlaceWeather extends Db {
    public function weather($type, $user_id, $place_id){



        $this->query('SELECT * FROM Places WHERE ID = ?');
        $this->bind(1,$place_id);
        $row_user = $this->single();
        $place_timezone = $row_user['TIMEZONE'];
        $lat = $row_user['LATITUDE'];
        $long = $row_user['LONGITUDE'];

        // if($place_timezone != 'a' || $place_timezone != ''){
            date_default_timezone_set($place_timezone);
        // }
        $weatherTime = date('Gi');
        date_default_timezone_set('GMT');

        // $this->query("SELECT * FROM Weather_Api WHERE ID = '1'");
        // $row_weather_api = $this->single();
        // $api = $row_weather_api['API'];

    
        $new_weather                 =   New Weather();

        $new_weather->isEmpty        =   false;
        $new_weather->weatherTime    =   $weatherTime;

        $astro                       =   New Astro();
        $new_weather->astro          =   $astro->get_astro($type, $user_id, $place_id, $place_timezone, $lat, $long);

        $hourly                      =   New Hourly();
        $new_weather->hourly         =   $hourly->get_hourly($type, $user_id, $place_id, $lat, $long);

        if($new_weather->astro == '' || $new_weather->hourly == ''){
            $new_weather->isEmpty    =   true;
        }
     
        $this->closeConnection();
        return $new_weather;

    }
}