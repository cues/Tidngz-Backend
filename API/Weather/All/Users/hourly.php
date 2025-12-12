<?php

class HourlyData {
    public $source              ;
    public $icon                ;
    public $content             ;      
    public $temp                ;                  
    public $temp_2              ;           
    public $feels               ;              
    public $feels_2             ;                  
    public $dew                 ;                  
    public $dew_2               ;                   
    public $humidity            ;              
    public $wind_degree         ;               
    public $wind_speed_km       ;                        
    public $wind_speed_mph      ;                 
    public $precipitation_mm    ;                  
    public $precipitation_in    ;           

}


class Hourly extends Db{
   public function get_hourly($type, $user_id, $api, $lat, $long){
        global $date;

        date_default_timezone_set('GMT');

        $date_1 =  date('Y-m-d H:i:s', strtotime($date) - 1 * 3600);
        $date_weather_inserted = 0;


        $new_hourly = New HourlyData();
        $new_icon   = New WeatherIcon();


        $this->query("SELECT * FROM user_weather_hourly WHERE USER = ?");
        $this->bind(1,$user_id);


        if($this->count() > 0){

            $row_weather_forecast = $this->single();
            $date_weather_inserted = $row_weather_forecast['DATE'];
             
        }
      
  
        if($date_weather_inserted <= $date_1 && $type == 2){

                $url = 'https://api.aerisapi.com/observations/?client_id=nGkGPjvI7RQygwKgG3mSq&client_secret=EpLSfBI5IeTWM4HaSXeakjqVJMjdmjhWalSDFlFq&p='.$lat .','. $long .'';
                $client = curl_init($url);
                curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
                $response = curl_exec($client);
                $result = json_decode($response);
                
                

                if($result->error == null){

                    $url = 'http://api.openweathermap.org/data/2.5/weather?lat='.$lat.'&lon='.$long.'&appid=5044a064047b2e4eda625e7fac363785&units=metric';
                    $client = curl_init($url);
                    curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
                    $response = curl_exec($client);
                    $result = json_decode($response);

                    $source             =   'OPEN WEATHER';
                    $icon               =   $result->weather[0]->id;
                    $content            =   $result->weather[0]->description;
                    $temp               =   $result->main->temp;
                    $temp_2             =   $result->main->temp * 1.8 + 32;
                    $feels              =   null;
                    $feels_2            =   null;
                    $dew                =   null;
                    $dew_2              =   null;
                    $humidity           =   $result->main->humidity;
                    $wind_degree        =   $result->wind->deg;
                    $wind_speed_km      =   round($result->wind->speed, 1);
                    $wind_speed_mph     =   round(0.6214 * $result->wind->speed, 1);
                    $precipitation_mm   =   null;
                    $precipitation_in   =   null;

                    $icon = $new_icon->icon($icon);


                }else{

    
                    $source             =   'ARIES';
                    $icon               =   $result->response->ob->icon;
                    $content            =   $result->response->ob->weather;
                    $temp               =   $result->response->ob->tempC;
                    $temp_2             =   $result->response->ob->tempF;
                    $feels              =   $result->response->ob->feelslikeC;
                    $feels_2            =   $result->response->ob->feelslikeF;
                    $dew                =   $result->response->ob->dewpointC;
                    $dew_2              =   $result->response->ob->dewpointF;
                    $humidity           =   $result->response->ob->humidity;
                    $wind_degree        =   $result->response->ob->windDirDEG;
                    $wind_speed_km      =   round($result->response->ob->windSpeedKPH, 1);
                    $wind_speed_mph     =   round($result->response->ob->windSpeedMPH, 1);
                    $precipitation_mm   =   $result->response->ob->precipMM;
                    $precipitation_in   =   $result->response->ob->precipIN;


                    $icon = $new_icon->icon_2($icon);

    
                }


 
                // return $result;

                $sanitize_1 = array($icon, $content, $temp, $temp_2, $feels, $feels_2, $dew, $dew_2, $humidity, $wind_degree,
                                    $wind_speed_km, $wind_speed_mph, $precipitation_mm, $precipitation_in, $source);

                if(Sanitize::check_sanitize($sanitize_1, 1)){
                    $this->closeConnection();
                    $new_hourly = '';
                    return $new_hourly;
                }



               if($this->count() > 0){

                    $this->query("UPDATE user_weather_hourly SET  
                        DATE = ?, ICON = ?, CONTENT = ? ,TEMP = ?, TEMP_2 = ?, FEELS = ?, FEELS_2 = ? ,
                        DEW = ?, DEW_2 = ?, HUMIDITY = ?, WIND_DEGREE = ?, WIND_SPEED_KM = ?, WIND_SPEED_MPH = ?,
                        PRECIPITATION_MM = ?, PRECIPITATION_IN = ? , SOURCE = ? WHERE USER = ?");

                }else{

                    $this->query("INSERT INTO user_weather_hourly 
                        (DATE, ICON, CONTENT, TEMP, TEMP_2, FEELS, FEELS_2,
                        DEW, DEW_2, HUMIDITY, WIND_DEGREE, WIND_SPEED_KM, WIND_SPEED_MPH,
                        PRECIPITATION_MM, PRECIPITATION_IN, SOURCE, USER) values 
                        (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                          
                }


                          $this->bind(1,$date);
                          $this->bind(2,$icon);
                          $this->bind(3,$content);
                          $this->bind(4,$temp);
                          $this->bind(5,$temp_2);
                          $this->bind(6,$feels);
                          $this->bind(7,$feels_2);
                          $this->bind(8,$dew);
                          $this->bind(9,$dew_2);
                          $this->bind(10,$humidity);
                          $this->bind(11,$wind_degree);
                          $this->bind(12,$wind_speed_km);
                          $this->bind(13,$wind_speed_mph);
                          $this->bind(14,$precipitation_mm);
                          $this->bind(15,$precipitation_in);
                          $this->bind(16,$source);
                          $this->bind(17,$user_id);
                          $this->execute();
        
        }



            $this->query("SELECT * FROM user_weather_hourly WHERE USER = ?");
            $this->bind(1,$user_id);
            $row_weather_forecast = $this->single();
            $date_weather_inserted = $row_weather_forecast['DATE'];

            if($date_weather_inserted > $date_1){

                $new_hourly->source             =   $row_weather_forecast['SOURCE'];
                $new_hourly->icon               =   $row_weather_forecast['ICON'];
                $new_hourly->content            =   $row_weather_forecast['CONTENT'];
                $new_hourly->temp               =   $row_weather_forecast['TEMP'];   
                $new_hourly->temp_2             =   $row_weather_forecast['TEMP_2'];     
                $new_hourly->feels              =   $row_weather_forecast['FEELS'];    
                $new_hourly->feels_2            =   $row_weather_forecast['FEELS_2'];      
                $new_hourly->dew                =   $row_weather_forecast['DEW'];    
                $new_hourly->dew_2              =   $row_weather_forecast['DEW_2'];          
                $new_hourly->humidity           =   $row_weather_forecast['HUMIDITY'];    
                $new_hourly->wind_degree        =   $row_weather_forecast['WIND_DEGREE'];
                $new_hourly->wind_speed_km      =   $row_weather_forecast['WIND_SPEED_KM'];            
                $new_hourly->wind_speed_mph     =   $row_weather_forecast['WIND_SPEED_MPH'];       
                $new_hourly->precipitation_mm   =   $row_weather_forecast['PRECIPITATION_MM'];               
                $new_hourly->precipitation_in   =   $row_weather_forecast['PRECIPITATION_IN'];         

            } 
            else{
                $new_hourly = '';
            }

            $this->closeConnection();
            return $new_hourly;
    }
}