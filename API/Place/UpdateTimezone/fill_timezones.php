<?php
// Script to populate missing TIMEZONE values in the Places table.
// Usage (from browser or CLI): visit this file or run with PHP CLI.
// Requires db_pdo.php in the project root which contains $google_api_key and Db class.

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../../db_pdo.php';

// Preferred project log, fallback to /tmp which is usually world-writable on macOS
$logFile = __DIR__ . '/timezone_fill.log';
$altLogFile = '/tmp/timezone_fill.log';
function logMsg($msg){
    global $logFile, $altLogFile;
    $date = date('Y-m-d H:i:s');
    $line = "[$date] $msg\n";
    // echo to stdout (useful in browser/CLI)
    echo $line;

    // Try primary location; suppress warnings and check return value
    $res = @file_put_contents($logFile, $line, FILE_APPEND);
    if($res === false){
        // Primary failed — try /tmp which is usually writable
        $res2 = @file_put_contents($altLogFile, $line, FILE_APPEND);
        if($res2 === false){
            // Both failed — write to PHP error log (no warnings) so admin can inspect
            error_log("[fill_timezones] Could not write log to $logFile or $altLogFile. Message: $msg");
            // Also echo a concise warning for immediate feedback
            echo "[WARN] Could not write log to project or /tmp; message sent to error_log()\n";
        } else {
            echo "[INFO] Logged to $altLogFile\n";
        }
    }
}

$db = new Db();

// Fetch places with missing timezone
$db->query("SELECT ID, PLACE_ID, LATITUDE, LONGITUDE, PLACE FROM Places WHERE TIMEZONE IS NULL OR TIMEZONE = ''");
$places = $db->result();
$total = count($places);
logMsg("Found $total places with empty TIMEZONE.");

if($total == 0){
    exit;
}

foreach($places as $i => $p){
    $id = $p['ID'];
    $place_name = isset($p['PLACE']) ? $p['PLACE'] : '';
    $place_id = isset($p['PLACE_ID']) ? $p['PLACE_ID'] : '';
    $lat = isset($p['LATITUDE']) ? trim($p['LATITUDE']) : '';
    $lng = isset($p['LONGITUDE']) ? trim($p['LONGITUDE']) : '';

    logMsg("Processing ID=$id, PLACE='$place_name', PLACE_ID='$place_id', lat='$lat', lng='$lng'");

    $tz = null;
    $timestamp = time();

    // Helper: call timezone API with lat,lng
    $call_timezone = function($latval, $lngval) use ($timestamp){
        global $google_api_key;
        $loc = urlencode($latval . ',' . $lngval);
        $url = "https://maps.googleapis.com/maps/api/timezone/json?location={$loc}&timestamp={$timestamp}&key={$google_api_key}";
        $opts = array(
            'http' => array('timeout' => 10)
        );
        $context = stream_context_create($opts);
        $json = @file_get_contents($url, false, $context);
        if($json === false) return array('status' => 'REQUEST_FAILED', 'body' => null);
        $data = json_decode($json, true);
        return array('status' => isset($data['status']) ? $data['status'] : 'NO_STATUS', 'body' => $data);
    };

    // If lat/lng available, try timezone API directly
    if($lat !== '' && $lng !== '' && is_numeric($lat) && is_numeric($lng)){
        $res = $call_timezone($lat, $lng);
        if($res['status'] === 'OK' && isset($res['body']['timeZoneId'])){
            $tz = $res['body']['timeZoneId'];
            logMsg("Got timezone via coords: $tz");
        } else {
            logMsg("Timezone API (coords) returned status={$res['status']}");
        }
        // be polite with rate limits
        sleep(1);
    }

    // If still not found and we have a Google Place ID, try Place Details to get lat/lng
    if($tz === null && !empty($place_id)){
        global $google_api_key;
        $placeid = urlencode($place_id);
        $fields = urlencode('geometry');
        $url_pd = "https://maps.googleapis.com/maps/api/place/details/json?place_id={$placeid}&fields={$fields}&key={$google_api_key}";
        $pd_json = @file_get_contents($url_pd);
        if($pd_json !== false){
            $pd = json_decode($pd_json, true);
            if(isset($pd['status']) && $pd['status'] === 'OK' && isset($pd['result']['geometry']['location'])){
                $lat2 = $pd['result']['geometry']['location']['lat'];
                $lng2 = $pd['result']['geometry']['location']['lng'];
                logMsg("Place Details returned lat=$lat2 lng=$lng2");
                // call timezone API
                $res2 = $call_timezone($lat2, $lng2);
                if($res2['status'] === 'OK' && isset($res2['body']['timeZoneId'])){
                    $tz = $res2['body']['timeZoneId'];
                    logMsg("Got timezone via place details: $tz");
                } else {
                    logMsg("Timezone API (from place details) returned status={$res2['status']}");
                }
            } else {
                $stat = isset($pd['status']) ? $pd['status'] : 'NO_STATUS';
                logMsg("Place Details returned status=$stat or missing geometry");
            }
        } else {
            logMsg("Place Details request failed for place_id=$place_id");
        }
        // be polite
        sleep(1);
    }

    if($tz !== null){
        try{
            $db->query("UPDATE Places SET TIMEZONE = ? WHERE ID = ?");
            $db->bind(1, $tz);
            $db->bind(2, $id);
            $db->execute();
            logMsg("Updated ID=$id with TIMEZONE='$tz'");
        } catch(Exception $e){
            logMsg("DB update failed for ID=$id: " . $e->getMessage());
        }
    } else {
        logMsg("Could not determine timezone for ID=$id");
    }

    // short sleep to avoid bursts
    usleep(250000);
}

logMsg("Done processing $total places.");

?>
