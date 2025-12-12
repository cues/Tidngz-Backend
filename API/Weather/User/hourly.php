<?php

class HourlyData {
    public $source              ;
    public $aqi                 ;
    public $aqi_desc            ;
    public $aqi_health          ;
    public $icon                ;
    public $content             ;      
    public $temp                ;                  
    public $temp_2              ;           
    public $feels               ;              
    public $feels_2             ;                  
    public $dew                 ;                  
    public $dew_2               ;                   
    public $humidity            ;              
    public $pressure            ;              
    public $uvi                 ;              
    public $wind_degree         ;               
    public $wind_speed_km       ;                        
    public $wind_speed_mph      ;                 
    public $precipitation_prob_type     ;             
    public $precipitation_prob_percent  ;             
    public $precipitation_mm    ;             
    public $snow_mm             ;
    public $visibility_km       ;
    public $visibility_mph      ;
}


class Hourly extends Db{
   public function get_hourly($type, $user_id, $lat, $long){
        global $date;
        global $google_api_key;

        date_default_timezone_set('GMT');

        $date_1 =  date('Y-m-d H:i:s', strtotime($date) - 1/4 * 3600);
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



                $url = 'https://airquality.googleapis.com/v1/currentConditions:lookup?key=' . $google_api_key;
                $data = json_encode([
                    'location' => [
                        'latitude'  => $lat,
                        'longitude' => $long
                    ],
                    'extraComputations' => [
                        'HEALTH_RECOMMENDATIONS',
                        'LOCAL_AQI',
                    ]
                ]);
                
                $client = curl_init($url);
                curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($client, CURLOPT_POST, true);
                curl_setopt($client, CURLOPT_POSTFIELDS, $data);
                curl_setopt($client, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                $response = curl_exec($client);
                $result_air = json_decode($response);

                $aqi = null;
                $aqi_desc = null;
                $aqi_health = null;

                // Safely extract AQI info if present
                if(isset($result_air->indexes) && isset($result_air->indexes[1])){
                    $aqi = isset($result_air->indexes[1]->aqi) ? $result_air->indexes[1]->aqi : null;
                    $aqi_desc = isset($result_air->indexes[1]->category) ? $result_air->indexes[1]->category : null;
                    $aqi_health = isset($result_air->healthRecommendations->generalPopulation) ? $result_air->healthRecommendations->generalPopulation : null;
                }


            $url = 'https://weather.googleapis.com/v1/currentConditions:lookup?key=' . $google_api_key . '&location.latitude=' . $lat . '&location.longitude=' . $long;
            $client = curl_init($url);
            curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
            $response = curl_exec($client);
            $result = json_decode($response);
                
            if($result->error == null){

                        $source             =   'GOOGLE WEATHER';
                        $icon               =   $result->weatherCondition->type;
                        $content            =   $result->weatherCondition->description->text;
                        $temp               =   $result->temperature->degrees;
                        $temp_2             =   $result->temperature->degrees * 1.8 + 32;
                        $feels              =   $result->feelsLikeTemperature->degrees;
                        $feels_2            =   $result->feelsLikeTemperature->degrees * 1.8 + 32;
                        $dew                =   $result->dewPoint->degrees;
                        $dew_2              =   $result->dewPoint->degrees * 1.8 + 32;
                        $humidity           =   $result->relativeHumidity;
                        $pressure           =   $result->airPressure->meanSeaLevelMillibars;
                        $uvi                =   $result->uvIndex;
                        $wind_degree        =   $result->wind->direction->degrees;
                        $wind_speed_km      =   round($result->wind->speed->value, 1);
                        $wind_speed_mph     =   round(0.6214 * $result->wind->speed->value, 1);
                        $precipitation_mm   =   $result->precipitation->qpf->quantity;
                        $precipitation_prob_percent =   $result->precipitation->probability->percent;
                        $precipitation_prob_type    =   $result->precipitation->probability->type  ;
                        $snow_mm            =   $result->precipitation->snowQpf->quantity;
                        $visibility_km      =   round($result->visibility->distance, 1);
                        $visibility_mph     =   round(0.6214 * $result->visibility->distance, 1);


                        $icon = $new_icon->google_icon($icon);

                    
                // else{
                

                //         $url = 'https://api.aerisapi.com/observations/?client_id=cesF9CjyKgRqGNIbBMavK&client_secret=IvSSp5Zz0u1yReSIh3ZHQTQ2KKcX8U1glvcxECJq&p='.$lat .','. $long .'';
                //         $client = curl_init($url);
                //         curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
                //         $response = curl_exec($client);
                //         $result = json_decode($response);
                        
                        

                //         if($result->error == null){


                //             $source             =   'ARIES';
                //             $icon               =   $result->response->ob->icon;
                //             $content            =   $result->response->ob->weather;
                //             $temp               =   $result->response->ob->tempC;
                //             $temp_2             =   $result->response->ob->tempF;
                //             $feels              =   $result->response->ob->feelslikeC;
                //             $feels_2            =   $result->response->ob->feelslikeF;
                //             $dew                =   $result->response->ob->dewpointC;
                //             $dew_2              =   $result->response->ob->dewpointF;
                //             $humidity           =   $result->response->ob->humidity;
                //             $pressure           =   $result->response->ob->pressureMB;
                //             $wind_degree        =   $result->response->ob->windDirDEG;
                //             $wind_speed_km      =   round($result->response->ob->windSpeedKPH, 1);
                //             $wind_speed_mph     =   round($result->response->ob->windSpeedMPH, 1);
                //             $precipitation_mm   =   $result->response->ob->precipMM;
                //             $precipitation_in   =   $result->response->ob->precipIN;


                //             $icon = $new_icon->icon_2($icon);

        

                //         }else{


                //             $url = 'http://api.openweathermap.org/data/2.5/weather?lat='.$lat.'&lon='.$long.'&appid=ad47b479d276bc401871e54234142226&units=metric';
                //             $client = curl_init($url);
                //             curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
                //             $response = curl_exec($client);
                //             $result = json_decode($response);

                //             $source             =   'OPEN WEATHER';
                //             $icon               =   $result->weather[0]->id;
                //             $content            =   $result->weather[0]->description;
                //             $temp               =   $result->main->temp;
                //             $temp_2             =   $result->main->temp * 1.8 + 32;
                //             $feels              =   null;
                //             $feels_2            =   null;
                //             $dew                =   null;
                //             $dew_2              =   null;
                //             $humidity           =   $result->main->humidity;
                //             $pressure           =   $result->main->pressure;
                //             $wind_degree        =   $result->wind->deg;
                //             $wind_speed_km      =   round($result->wind->speed, 1);
                //             $wind_speed_mph     =   round(0.6214 * $result->wind->speed, 1);
                //             $precipitation_mm   =   null;
                //             $precipitation_in   =   null;

                //             $icon = $new_icon->icon($icon);

            
                //         }
                // }


 

                // Validate core numeric/string values - ensure variable names and commas are correct
                $sanitize_1 = array($aqi, $aqi_desc, $icon, $temp, $temp_2, $feels, $feels_2,
                                    $dew, $dew_2, $humidity, $pressure, $uvi, $wind_degree,
                                    $wind_speed_km, $wind_speed_mph, $precipitation_prob_percent, $precipitation_prob_type,
                                    $precipitation_mm, $snow_mm, $visibility_km, $visibility_mph, $source);

                if(Sanitize::check_sanitize($sanitize_1, 1)){
                    $this->closeConnection();
                    $new_hourly = '';
                    return $new_hourly;
                }

                $sanitize_2 = array($aqi_health, $content);

                if(Sanitize::check_sanitize($sanitize_2, 3)){
                    $this->closeConnection();
                    $new_hourly = '';
                    return $new_hourly;
                }


               if($this->count() > 0){

                    $this->query("UPDATE user_weather_hourly SET  
                        DATE = ?, AQI = ?, AQI_DESC = ?, AQI_HEALTH = ?,  ICON = ?, CONTENT = ? ,TEMP = ?, TEMP_2 = ?, FEELS = ?, FEELS_2 = ? ,
                        DEW = ?, DEW_2 = ?, HUMIDITY = ?, PRESSURE = ?, UVI = ? , WIND_DEGREE = ?, WIND_SPEED_KM = ?, WIND_SPEED_MPH = ?,
                        PRECIPITATION_PROB_PERCENT = ? , PRECIPITATION_PROB_TYPE = ? , PRECIPITATION_MM = ? , 
                        SNOW_MM = ? , VISIBILITY_KM = ? , VISIBILITY_MPH = ? , SOURCE = ? WHERE USER = ?");

                }else{

                    $this->query("INSERT INTO user_weather_hourly 
                        (DATE, AQI, AQI_DESC, AQI_HEALTH, ICON, CONTENT, TEMP, TEMP_2, FEELS, FEELS_2,
                        DEW, DEW_2, HUMIDITY, PRESSURE, UVI, WIND_DEGREE, WIND_SPEED_KM, WIND_SPEED_MPH,
                        PRECIPITATION_PROB_PERCENT, PRECIPITATION_PROB_TYPE, PRECIPITATION_MM, SNOW_MM,
                        VISIBILITY_KM, VISIBILITY_MPH, SOURCE, USER) values 
                        (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                          
                }

                          $this->bind(1,$date);
                          $this->bind(2,$aqi);
                          $this->bind(3,$aqi_desc);
                          $this->bind(4,$aqi_health);
                          $this->bind(5,$icon);
                          $this->bind(6,$content);
                          $this->bind(7,$temp);
                          $this->bind(8,$temp_2);
                          $this->bind(9,$feels);
                          $this->bind(10,$feels_2);
                          $this->bind(11,$dew);
                          $this->bind(12,$dew_2);
                          $this->bind(13,$humidity);
                          $this->bind(14,$pressure);
                          $this->bind(15,$uvi);
                          $this->bind(16,$wind_degree);
                          $this->bind(17,$wind_speed_km);
                          $this->bind(18,$wind_speed_mph);
                          $this->bind(19,$precipitation_prob_percent);
                          $this->bind(20,$precipitation_prob_type);
                          $this->bind(21,$precipitation_mm);
                          $this->bind(22,$snow_mm);
                          $this->bind(23,$visibility_km);
                          $this->bind(24,$visibility_mph);
                          $this->bind(25,$source);
                          $this->bind(26,$user_id);
                          $this->execute();

            }
                
        
        }



            $this->query("SELECT * FROM user_weather_hourly WHERE USER = ?");
            $this->bind(1,$user_id);
            $row_weather_forecast = $this->single();
            $date_weather_inserted = $row_weather_forecast['DATE'];

            if($date_weather_inserted > $date_1){

                $new_hourly->source             =   $row_weather_forecast['SOURCE'];
                $new_hourly->aqi                =   $row_weather_forecast['AQI'];
                $new_hourly->aqi_desc           =   $row_weather_forecast['AQI_DESC'];
                $new_hourly->aqi_health         =   $row_weather_forecast['AQI_HEALTH'];
                $new_hourly->icon               =   $row_weather_forecast['ICON'];
                $new_hourly->content            =   $row_weather_forecast['CONTENT'];
                $new_hourly->temp               =   $row_weather_forecast['TEMP'];   
                $new_hourly->temp_2             =   $row_weather_forecast['TEMP_2'];     
                $new_hourly->feels              =   $row_weather_forecast['FEELS'];    
                $new_hourly->feels_2            =   $row_weather_forecast['FEELS_2'];      
                $new_hourly->dew                =   $row_weather_forecast['DEW'];    
                $new_hourly->dew_2              =   $row_weather_forecast['DEW_2'];          
                $new_hourly->humidity           =   $row_weather_forecast['HUMIDITY'];    
                $new_hourly->pressure           =   $row_weather_forecast['PRESSURE'];    
                $new_hourly->uvi                =   $row_weather_forecast['UVI'];    
                $new_hourly->wind_degree        =   $row_weather_forecast['WIND_DEGREE'];
                $new_hourly->wind_speed_km      =   $row_weather_forecast['WIND_SPEED_KM'];            
                $new_hourly->wind_speed_mph     =   $row_weather_forecast['WIND_SPEED_MPH'];       
                $new_hourly->precipitation_prob_percent   =   $row_weather_forecast['PRECIPITATION_PROB_PERCENT'];         
                $new_hourly->precipitation_prob_type   =   $row_weather_forecast['PRECIPITATION_PROB_TYPE'];  
                $new_hourly->precipitation_mm   =   $row_weather_forecast['PRECIPITATION_MM'];
                // $new_hourly->precipitation_mm   =   20;
                $new_hourly->snow_mm            =   $row_weather_forecast['SNOW_MM'];
                // $new_hourly->snow_mm            =   20;
                $new_hourly->visibility_km      =   $row_weather_forecast['VISIBILITY_KM'];
                $new_hourly->visibility_mph     =   $row_weather_forecast['VISIBILITY_MPH'];

            } 
            else{
                $new_hourly = '';
            }

            $this->closeConnection();
            return $new_hourly;
    }
}