<?php


class WeatherIcon{

    public function google_icon($icon){
        switch ($icon){
            case 'CLEAR' : 
                return 1;
                break;
            case 'MOSTLY_CLEAR' : 
                return 2;
                break;
            case 'PARTLY_CLOUDY' : 
                return 2;
                break;
            case 'MOSTLY_CLOUDY' : 
                return 3;
                break;
            case 'CLOUDY' : 
                return 3;
                break;
            // case 'CLOUDY' : 
            //     return 3;
            //     break;
            // case 'RAIN' : 
            //     return 11;
            //     break;
            // case 'SLEET' : 
            //     return 16;
            //     break;
            // case 'SNOW' : 
            //     return 16;
            //     break;
            // case 'WIND' : 
            //     return 5;
            //     break;
            // case 'FOG' : 
            //     return 6;
            //     break;    
            default:
                return 1;
        }
    }

    public function icon($icon){

        switch ($icon){
            case 800 : 
                return 1;
                break;
            case 801 : 
                return 2;
                break;
            case 802 : 
                return 3;
                break;
            case 803 : 
                return 4;
                break;
            case 804 : 
                return 4;
                break;

            case 701 : 
                return 6;
                break;
            case 711 : 
                return 6;
                break;
            case 721 : 
                return 5;
                break;
            case 731 : 
                return 5;
                break;
            case 741 : 
                return 5;
                break;
            case 751 : 
                return 5;
                break;
            case 761 : 
                return 5;
                break;
            case 762 : 
                return 5;
                break;
            case 771 : 
                return 5;
                break;
            case 781 : 
                return 15;
                break;
                
            case 600 : 
                return 16;
                break;
            case 601 : 
                return 19;
                break;
            case 602 : 
                return 21;
                break;
            case 611 : 
                return 16;
                break;
            case 612 : 
                return 16;
                break;
            case 613 : 
                return 16;
                break;
            case 615 : 
                return 16;
                break;
            case 616 : 
                return 16;
                break;
            case 620 : 
                return 16;
                break;
            case 621 : 
                return 16;
                break;
            case 622 : 
                return 16;
                break;


            case 300 : 
                return 11;
                break;
            case 301 : 
                return 11;
                break;
            case 302 : 
                return 11;
                break;
            case 310 : 
                return 11;
                break;
            case 311 : 
                return 11;
                break;
            case 312 : 
                return 11;
                break;
            case 313 : 
                return 11;
                break;
            case 314 : 
                return 11;
                break;
            case 321 : 
                return 11;
                break;

            case 500 : 
                return 11;
                break;
            case 501 : 
                return 11;
                break;
            case 502 : 
                return 11;
                break;
            case 503 : 
                return 11;
                break;
            case 504 : 
                return 11;
                break;
            case 511 : 
                return 11;
                break;
            case 520 : 
                return 11;
                break;
            case 521 : 
                return 11;
                break;
            case 522 : 
                return 11;
                break;
            case 531 : 
                return 11;
                break;

            case 200 : 
                return 15;
                break;
            case 201 : 
                return 15;
                break;
            case 202 : 
                return 15;
                break;
            case 210 : 
                return 15;
                break;
            case 211 : 
                return 15;
                break;
            case 212 : 
                return 15;
                break;
            case 221 : 
                return 15;
                break;
            case 230 : 
                return 15;
                break;
            case 231 : 
                return 15;
                break;
            case 232 : 
                return 15;
                break;

            default:
                return 1;
        } 
    }






    public function icon_2($icon){

        switch ($icon){
            case 'blizzard.png' || 'blizzardn.png' : 
                return 24;
                break;
            case 'blowingsnow.png' || 'blowingsnown.png' : 
                return 21;
                break;
            case 'clear.png' || 'clearn.png' : 
                return 1;
                break;
            case 'cloudy.png' || 'cloudyn.png' : 
                return 1;
                break;
            case 'cloudyw.png' || 'cloudywn.png' : 
                return 1;
                break;
            case 'cold.png' || 'coldn.png' : 
                return 1;
                break;
            case 'drizzle.png' || 'drizzlen.png' : 
                return 1;
                break;
            case 'dust.png' || 'dustn.png' : 
                return 1;
                break;
            case 'fair.png' || 'fairn.png' : 
                return 1;
                break;
            case 'drizzlef.png' || 'drizzlefn.png' : 
                return 1;
                break;
            case 'flurries.png' || 'flurriesn.png' : 
                return 1;
                break;
            case 'flurriesw.png' || 'flurrieswn.png' : 
                return 1;
                break;
            case 'fog.png' || 'fogn.png' : 
                return 1;
                break;
            case 'freezingrain.png' || 'freezingrainn.png' : 
                return 1;
                break;
            case 'hazy.png' || 'hazyn.png' : 
                return 1;
                break;
            case 'hot.png' || 'hotn.png' : 
                return 1;
                break;
            case 'mcloudy.png' || 'mcloudyn.png' : 
                return 1;
                break;
            case 'mcloudyr.png' || 'mcloudyrn.png' : 
                return 1;
                break;
            case 'mcloudyrw.png' || 'mcloudyrwn.png' : 
                return 1;
                break;
            case 'mcloudys.png' || 'mcloudysn.png' : 
                return 1;
                break;
            case 'mcloudysf.png' || 'mcloudysfn.png' : 
                return 1;
                break;
            case 'mcloudysfw.png' || 'mcloudysfwn.png' : 
                return 1;
                break;
            case 'mcloudysw.png' || 'mcloudyswn.png' : 
                return 1;
                break;
            case 'mcloudyt.png' || 'mcloudytn.png' : 
                return 1;
                break;
            case 'mcloudytw.png' || 'mcloudytwn.png' : 
                return 1;
                break;
            case 'mcloudyw.png' || 'mcloudywn.png' : 
                return 1;
                break;
            case 'pcloudy.png' || 'pcloudyn.png' : 
                return 1;
                break;
            case 'pcloudyr.png' || 'pcloudyrn.png' : 
                return 1;
                break;
            case 'pcloudyrw.png' || 'pcloudyrwn.png' : 
                return 1;
                break;
            case 'pcloudys.png' || 'pcloudysn.png' : 
                return 1;
                break;
            case 'pcloudysf.png' || 'pcloudysfn.png' : 
                return 1;
                break;
            case 'pcloudysfw.png' || 'pcloudysfwn.png' : 
                return 1;
                break;
            case 'pcloudysw.png' || 'pcloudyswn.png' : 
                return 1;
                break;
            case 'pcloudyt.png' || 'pcloudytn.png' : 
                return 1;
                break;
            case 'pcloudytw.png' || 'pcloudytwn.png' : 
                return 1;
                break;
            case 'pcloudyw.png' || 'pcloudywn.png' : 
                return 1;
                break;
            case 'rain.png' || 'rainn.png' : 
                return 1;
                break;
            case 'rainandsnow.png' || 'rainandsnown.png' : 
                return 1;
                break;
            case 'raintosnow.png' || 'raintosnown.png' : 
                return 1;
                break;
            case 'rainw.png' || 'rainwn.png' : 
                return 1;
                break;
            case 'showers.png' || 'showersn.png' : 
                return 1;
                break;
            case 'showersw.png' || 'showerswn.png' : 
                return 1;
                break;
            case 'sleet.png' || 'sleetn.png' : 
                return 1;
                break;
            case 'sleetsnow.png' || 'sleetsnown.png' : 
                return 1;
                break;
            case 'smoke.png' || 'smoken.png' : 
                return 1;
                break;
            case 'snow.png' || 'snown.png' : 
                return 1;
                break;
            case 'snoww.png' || 'snowwn.png' : 
                return 1;
                break;
            case 'snowshowers.png' || 'snowshowersn.png' : 
                return 1;
                break;
            case 'snowshowersw.png' || 'snowshowerswn.png' : 
                return 1;
                break;
            case 'snowtorain.png' || 'snowtorainn.png' : 
                return 1;
                break;
            case 'sunny.png' || 'sunnyn.png' : 
                return 1;
                break;
            case 'sunnyw.png' || 'sunnywn.png' : 
                return 1;
                break;
            case 'tstorm.png' || 'tstormn.png' : 
                return 1;
                break;
            case 'tstorms.png' || 'tstormsn.png' : 
                return 1;
                break;
            case 'tstormsw.png' || 'tstormswn.png' : 
                return 1;
                break;
            case 'wind.png' || 'windn.png' : 
                return 1;
                break;
            case 'wintrymix.png' || 'wintrymixn.png' : 
                return 1;
                break;    
            default:
                return 1;
        } 
    }
} 