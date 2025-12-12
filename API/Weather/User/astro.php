<?php


class AstroData {
    public $sunrise_hour;
    public $sunrise_minutes;
    public $sunset_hour;
    public $sunset_minutes;
}


class Astro extends Db{

    public function get_astro($type, $user_id, $user_timezone, $lat, $long){

        date_default_timezone_set($user_timezone);

        $date_weather_forecast_inserted = 0;
        $date_forecast = date('Y-m-d');

        $new_astro = New AstroData();

        $this->query("SELECT * FROM user_weather_astronomy WHERE USER = ?");
        $this->bind(1,$user_id);
      
      
        if($this->count() > 0){
         
            $row_weather_forecast = $this->single();
            $date_weather_forecast_inserted = $row_weather_forecast['DATE'];
      
        }

        if($date_weather_forecast_inserted != $date_forecast && $type == 2){

            $url = 'https://api.sunrise-sunset.org/json?lat='.$lat.'&lng='.$long.'';
            // $url = 'https://api.wunderground.com/api/'.$api.'/astronomy/q/'.$lat.','.$long.'.json';
            $client = curl_init($url);
            curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
            $response = curl_exec($client);
            $result = json_decode($response);



            date_default_timezone_set('GMT');

            $timestamp = strtotime($result->results->sunrise) ;
            $dt = new DateTime();
            $dt->setTimestamp($timestamp);
            $dt->setTimezone(new DateTimeZone($user_timezone));
            $sunrise_hour   = $dt->format('g');
            $sunrise_minute = $dt->format('i');


            $timestamp = strtotime($result->results->sunset) ;
            $dt = new DateTime();
            $dt->setTimestamp($timestamp);
            $dt->setTimezone(new DateTimeZone($user_timezone));
            $sunset_hour    = $dt->format('G');
            $sunset_minute  = $dt->format('i');

            // return $result->results->sunrise;

            // return $is;

            // $sunrise = $result->response->ob->sunrise;
            // $sunset  = $result->response->ob->sunset;

            // $sun = date('Y-m-d h:i:s',$sunrise);

         
     

            $sanitize_1 = array($sunrise_hour, $sunrise_minute, $sunset_hour, $sunset_minute);

            if(Sanitize::check_sanitize($sanitize_1, 1) || $sunrise_hour == '' || $sunrise_minute == '' || $sunset_hour == '' || $sunset_minute  == ''){
                $this->closeConnection();
                $new_astro = '';
                return $new_astro;
            }
            
            // return $date_forecast .':' . $sunrise_hour .':' . $sunrise_minute .':' . $sunset_hour .':' . $sunset_minute .':' . $user_timezone .':' . $user_id;
  
            if($this->count() > 0){

                $this->query("UPDATE user_weather_astronomy SET DATE = ?, SUNRISE_HOUR = ?, SUNRISE_MINUTE = ?, SUNSET_HOUR = ?, SUNSET_MINUTE = ? WHERE USER = ?");
                $this->bind(1,$date_forecast);
                $this->bind(2,$sunrise_hour);
                $this->bind(3,$sunrise_minute);
                $this->bind(4,$sunset_hour);
                $this->bind(5,$sunset_minute);
                $this->bind(6,$user_id);
                $this->execute();

            }
            else
            {
            //    try {
                    $this->query("INSERT INTO user_weather_astronomy (USER,DATE,SUNRISE_HOUR,SUNRISE_MINUTE,SUNSET_HOUR,SUNSET_MINUTE) values (?,?,?,?,?,?)");
                    $this->bind(1,$user_id);
                    $this->bind(2,$date_forecast);
                    $this->bind(3,$sunrise_hour);
                    $this->bind(4,$sunrise_minute);
                    $this->bind(5,$sunset_hour);
                    $this->bind(6,$sunset_minute);
                    $this->execute();
                // }
                // catch (Exception $e)
                // {
                //     return $e->getMessage();
                // }
                 

            }


            
  
    
        }


            $this->query("SELECT * FROM user_weather_astronomy WHERE USER = ?");
            $this->bind(1,$user_id);
            $row_weather_forecast = $this->single();
            $date_weather_forecast_inserted = $row_weather_forecast['DATE'];

            if($date_weather_forecast_inserted == $date_forecast){

                $new_astro->sunrise_hour      =  $row_weather_forecast['SUNRISE_HOUR'];
                $new_astro->sunrise_minutes   =  $row_weather_forecast['SUNRISE_MINUTE'];
                $new_astro->sunset_hour       =  $row_weather_forecast['SUNSET_HOUR'];
                $new_astro->sunset_minutes    =  $row_weather_forecast['SUNSET_MINUTE'];
                
            }
            else{
                $new_astro = '';
            }
            $this->closeConnection();
            return $new_astro;
    }

}

