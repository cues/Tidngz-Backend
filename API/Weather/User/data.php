<?php

class Weather {
    public $isEmpty;
    public $weatherTime;
    public $astro;
    public $hourly;
}





class UserWeather extends Db {
    public function weather($type, $user_id, $lat, $long){



        $this->query('SELECT * FROM users WHERE ID = ?');
        $this->bind(1,$user_id);
        $row_user = $this->single();
        $user_timezone = $row_user['TIMEZONE'];
        date_default_timezone_set($user_timezone);
        $weatherTime = date('Gi');
        date_default_timezone_set('GMT');


        // $this->query("SELECT * FROM Weather_Api WHERE ID = '1'");
        // $row_weather_api = $this->single();
        // $api = $row_weather_api['API'];


    
        $new_weather                 =   New Weather();

        $new_weather->isEmpty        =   false;
        $new_weather->weatherTime    =   $weatherTime;

        $astro                       =   New Astro();
        $new_weather->astro          =   $astro->get_astro($type, $user_id, $user_timezone, $lat, $long);

        $hourly                      =   New Hourly();
        $new_weather->hourly         =   $hourly->get_hourly($type, $user_id, $lat, $long);


        // return $new_weather->astro;

        if($new_weather->astro == '' || $new_weather->hourly == ''){
            $new_weather->isEmpty    =   true;
        }
     
        $this->closeConnection();
        return $new_weather;

    }
}