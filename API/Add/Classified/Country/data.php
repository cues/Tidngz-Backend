<?php


class Country {
    public $countriesCount;
    public $countryName = array();
}

class CountryData {
    public $id;
    public $name;
    public $flag;
}



class AddClassifiedCountry extends Db{

    public function get_country(){

        $countries   =   New Country();
        $image_data  =   New ImageData();

        $this->query("SELECT DISTINCT COUNTRY FROM Places WHERE COUNTRY NOT LIKE '1'  ORDER BY COUNTRY ASC");
        $countries->countriesCount = $this->count();
        $rowCountries  = $this->result();

        $i = 0;

        foreach ($rowCountries as $row_country){

            $countries->countryName[$i]  =  New CountryData();

            $country = $row_country['COUNTRY'];

            $this->query("SELECT * from Places WHERE PLACE = '$country' AND COUNTRY = '$country'");
            $each_country = $this->single();

            $country_id =   (int)$each_country['ID'];
            $province   =   $each_country['PROVINCE'];

            $province   =   str_ireplace('<', ',', $province);
            $country    =   str_ireplace('<', ',', $country);
            $province   =   str_ireplace('+', "'", $province);
            $country    =   str_ireplace('+', "'", $country);
            
            $countries->countryName[$i]->id    =  $country_id;
            $countries->countryName[$i]->name  =  $country;
            $countries->countryName[$i]->flag  =  $image_data->get_country_flag($country, $province);

            $i++;
        
        }
        
        return $countries; 

    }

} 



class SearchCountry extends Db{
    public function search_country($search){

        $countries   =   New Country();
        $image_data  =   New ImageData();

        $this->query("SELECT DISTINCT COUNTRY FROM Places WHERE LEFT (COUNTRY, 1) LIKE '%$search%' AND COUNTRY NOT LIKE '1' ORDER BY COUNTRY ASC");
        $countries->countriesCount = $this->count();
        $rowCountries  = $this->result();

        $i = 0;

        foreach ($rowCountries as $row_country){

            $countries->countryName[$i]  =  New CountryData();

            $country = $row_country['COUNTRY'];

            $this->query("SELECT * from Places WHERE PLACE = '$country' AND COUNTRY = '$country'");
            $each_country = $this->single();

            $country_id =   (int)$each_country['ID'];
            $province   =   $each_country['PROVINCE'];

            $province   =   str_ireplace('<', ',', $province);
            $country    =   str_ireplace('<', ',', $country);
            $province   =   str_ireplace('+', "'", $province);
            $country    =   str_ireplace('+', "'", $country);

            
            $countries->countryName[$i]->id    =  $country_id;
            $countries->countryName[$i]->name  =  $country;
            $countries->countryName[$i]->flag  =  $image_data->get_country_flag($country, $province);

            $i++;
        
        }
        


        return $countries; 

    }
}                                                                                                                                                                                         