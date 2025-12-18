<?php

// Enable verbose errors for debugging (remove or disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class AddPlace extends Db{
    public function add_place($user_id, $place_id, $place_url = '0123', $place = 'asdfasd', $county = '2345', $province = '3456', $country = '4567', $formatted_address = '5678', $latitude = 0, $longitude = 0, $latitude_delta = 0, $longitude_delta = 0){

            global $date;

            // Debugging: log incoming parameters (helpful to diagnose HTTP 500)
            // if(function_exists('error_log')){
            //     error_log('AddPlace::add_place called with user_id='.var_export($user_id,true).', place_id='.var_export($place_id,true));
            //     error_log('place param type='.gettype($place).', value='.var_export($place, true));
            // }

           
            // If a place with the same PLACE_ID exists, return that place
            // if(!empty($place_id)){
           
            //     if(!empty($existing) && isset($existing['ID'])){
            //         $placeObj = new Place();
            //         return $placeObj->get_place($user_id, (int)$existing['ID']);
            //     }

            // }


            // Normalize values before saving (follow repository convention: replace ' with + and , with < )
            // $place_db = $place !== '' ? str_ireplace("'", "+", $place) : '';
            // $place_db = $place_db !== '' ? str_ireplace(",", "<", $place_db) : $place_db;
            // $county_db = $county !== '' ? str_ireplace("'", "+", $county) : '';
            // $county_db = $county_db !== '' ? str_ireplace(",", "<", $county_db) : $county_db;
            // $province_db = $province !== '' ? str_ireplace("'", "+", $province) : '';
            // $province_db = $province_db !== '' ? str_ireplace(",", "<", $province_db) : $province_db;
            // $country_db = $country !== '' ? str_ireplace("'", "+", $country) : '';
            // $country_db = $country_db !== '' ? str_ireplace(",", "<", $country_db) : $country_db;
            // $formatted_db = $formatted_address !== '' ? str_ireplace("'", "+", $formatted_address) : '';
            // $formatted_db = $formatted_db !== '' ? str_ireplace(",", "<", $formatted_db) : $formatted_db;
           
           
        $this->query("SELECT * FROM Places WHERE PLACE_ID = ? LIMIT 1");
        $this->bind(1, $place_id);
        $existing = $this->single();
            // update table Places if place exists
        if(!empty($existing) && isset($existing['ID'])){
            $this->query("UPDATE Places SET PLACE_ID = ?, URL = ?, PLACE = ?, COUNTY = ?, PROVINCE = ?, COUNTRY = ?, FORMATTED_ADDRESS = ?, LATITUDE = ?, LONGITUDE = ?, LATITUDE_DELTA = ?, LONGITUDE_DELTA = ?, DATE_ADDED = ? WHERE ID = ?");
            $this->bind(13, $existing['ID']);
        }else{
            $this->query("INSERT INTO Places (PLACE_ID, URL, PLACE, COUNTY, PROVINCE, COUNTRY, FORMATTED_ADDRESS, LATITUDE, LONGITUDE, LATITUDE_DELTA, LONGITUDE_DELTA, DATE_ADDED) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");

        }
        // Insert into Places table
            $this->bind(1, $place_id);
            $this->bind(2, $place_url);
            $this->bind(3, $place);
            $this->bind(4, $county);
            $this->bind(5, $province);
            $this->bind(6, $country);
            $this->bind(7, $formatted_address);
            $this->bind(8, $latitude);
            $this->bind(9, $longitude);
            $this->bind(10, $latitude_delta);
            $this->bind(11, $longitude_delta);
            $this->bind(12, $date);

        // if(!empty($existing) && isset($existing['ID'])){
        // }

        if($this->execute()){
                // get last insert id
                $new_id = (int)$this->dbh->lastInsertId();

                // Trigger timezone fill script asynchronously so insert caller isn't blocked.
                // Use XAMPP php binary if available; fall back to `php` in PATH.
                $phpBin = '/Applications/XAMPP/xamppfiles/bin/php';
                if(!file_exists($phpBin)){
                    $phpBin = 'php';
                }
                $tzScript = __DIR__ . '/../UpdateTimezone/fill_timezones.php';
                if(file_exists($tzScript)){
                    // pass the new place ID as an argument so the timezone script processes only this row
                    // run synchronously and capture exit code so we only return after TZ resolved
                    $cmd = escapeshellcmd($phpBin) . ' -f ' . escapeshellarg($tzScript) . ' ' . escapeshellarg($new_id) . ' 2>&1';
                    $output = array();
                    $return_var = 1;
                    @exec($cmd, $output, $return_var);
                    if(function_exists('error_log')){
                        error_log("Ran timezone fill script: $cmd, exit=$return_var, output=" . implode("\n", $output));
                    }

                    // Only proceed to return the place object if timezone script succeeded (exit code 0)
                    if($return_var !== 0){
                        if(function_exists('error_log')){
                            error_log("Timezone fill script failed for place ID $new_id (exit=$return_var)");
                        }
                        // deletion/rollback could be considered here; we'll return NULL to indicate failure
                        return NULL;
                    }
                } else {
                    if(function_exists('error_log')){
                        error_log("Timezone fill script not found at: $tzScript");
                    }
                    return NULL;
                }


             $placeObj = new Place();
            if(!empty($existing) && isset($existing['ID'])){
                return $placeObj->get_place($user_id, $existing['ID']);
            }else{
                return $placeObj->get_place($user_id, $new_id);
            }

        } else {
            return NULL;
        }

      
                

        }
}
