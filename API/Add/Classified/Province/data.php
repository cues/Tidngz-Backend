<?php


class Province {
    public $provinceCount;
    public $provinceName = array();
}

class ProvinceData {
    public $id;
    public $name;
}



class AddClassifiedProvince extends Db{

    public function get_province($country_id){

        $provinces   =   New Province();
        
        $this->query("SELECT * FROM Places WHERE ID = ?");
        $this->bind(1, $country_id);
        $row_country = $this->single();
        $country = $row_country ['COUNTRY'];


        if($country == 'United Kingdom' ){
            $this->query("SELECT DISTINCT COUNTY FROM Places WHERE COUNTRY = '$country' ORDER BY PROVINCE, COUNTY ");
            $provinces->provinceCount = $this->count();
            $rowProvinces = $this->result();
          }
          else if($country == 'Azerbaijan' || $country == 'Belgium' || $country == 'France' || $country == 'Serbia'){
            $this->query("SELECT DISTINCT COUNTY FROM Places WHERE COUNTRY = '$country' ORDER BY COUNTY ");
            $provinces->provinceCount = $this->count();
            $rowProvinces = $this->result();
          }
          else if($country == 'Anguilla' || $country == 'Antarctica' || $country == 'Aruba' || $country == 'Åland Islands' || $country == 'Bouvet Island'
            || $country == 'British Indian Ocean Territory' || $country == 'Caribbean Netherlands' || $country == 'Christmas Island'
            || $country == 'Cocos (Keeling) Islands' || $country == 'Curaçao'  || $country == 'Falkland Islands (Islas Malvinas)'
            || $country == 'Faroe Islands' || $country == 'Federated States of Micronesia' || $country == 'French Guiana'
            || $country == 'French Polynesia' || $country == 'French Southern and Antarctic Lands' || $country == 'Gibraltar'
            || $country == 'Guadeloupe' || $country == 'Guam' || $country == 'Heard Island and McDonald Islands'
            || $country == 'Isle of Man' || $country == 'Kosovo' || $country == 'Macau' || $country == 'Malta'
            || $country == 'Mayotte' || $country == 'Monaco' || $country == 'Montserrat' || $country == 'New Caledonia'
            || $country == 'Niue' || $country == 'Norfolk Island' || $country == 'Northern Mariana Islands'
            || $country == 'Pitcairn Islands' || $country == 'Reunion' || $country == 'Saint Martin'
            || $country == 'Saint Pierre and Miquelon' || $country == 'Saint-Barthélemy' || $country == 'South Georgia and the South Sandwich Islands'
            || $country == 'Svalbard and Jan Mayen' || $country == 'The Bahamas' || $country == 'The Gambia' || $country == 'Timor-Leste'
            || $country == 'Tokelau' || $country == 'Turks and Caicos Islands' || $country == 'U.S. Virgin Islands'
            || $country == 'United States Minor Outlying Islands' || $country == 'Vatican City'
            || $country == 'Singapore'){
            $this->query("SELECT DISTINCT COUNTRY FROM Places WHERE COUNTRY = '$country'");
            $provinces->provinceCount = $this->count();
            $rowProvinces = $this->result();
          }
          else{
            $this->query("SELECT DISTINCT PROVINCE FROM Places WHERE COUNTRY = '$country' ORDER BY PROVINCE ");
            $provinces->provinceCount = $this->count();
            $rowProvinces = $this->result();
          }


            $i = 0;
                    foreach($rowProvinces as $province){


                        if($country == 'United Kingdom' || $country == 'Azerbaijan' || $country == 'Belgium' || $country == 'France' || $country == 'Serbia' ){
                            $county = $province['COUNTY'];
                            $this->query("SELECT * FROM Places WHERE PLACE = '$county' AND COUNTY ='$county' AND COUNTRY='$country' ");
                            $eachProvinces = $this->result();
                        }
                        else if($country == 'Anguilla' || $country == 'Antarctica' || $country == 'Aruba' || $country == 'Åland Islands' || $country == 'Bouvet Island'
                            || $country == 'British Indian Ocean Territory' || $country == 'Caribbean Netherlands'  || $country == 'Christmas Island'
                            || $country == 'Cocos (Keeling) Islands' || $country == 'Curaçao' || $country == 'Falkland Islands (Islas Malvinas)'
                            || $country == 'Faroe Islands' || $country == 'Federated States of Micronesia'  || $country == 'French Guiana'
                            || $country == 'French Polynesia' || $country == 'French Southern and Antarctic Lands' || $country == 'Gibraltar'
                            || $country == 'Guadeloupe' || $country == 'Guam' || $country == 'Heard Island and McDonald Islands'
                            || $country == 'Isle of Man' || $country == 'Kosovo' || $country == 'Macau' || $country == 'Malta'
                            || $country == 'Mayotte' || $country == 'Monaco' || $country == 'Montserrat' || $country == 'New Caledonia'
                            || $country == 'Niue' || $country == 'Norfolk Island' || $country == 'Northern Mariana Islands'
                            || $country == 'Pitcairn Islands' || $country == 'Reunion' || $country == 'Saint Martin'
                            || $country == 'Saint Pierre and Miquelon' || $country == 'Saint-Barthélemy' || $country == 'South Georgia and the South Sandwich Islands'
                            || $country == 'Svalbard and Jan Mayen' || $country == 'The Bahamas' || $country == 'The Gambia' || $country == 'Timor-Leste'
                            || $country == 'Tokelau' || $country == 'Turks and Caicos Islands' || $country == 'U.S. Virgin Islands'
                            || $country == 'United States Minor Outlying Islands' || $country == 'Vatican City'
                            || $country == 'Singapore'){
                            $this->query("SELECT * FROM Places WHERE PLACE = '$country' AND COUNTRY='$country' ");
                            $eachProvinces = $this->result();
                        }
                        else if($country == 'Guinea'){
                            $province = $province['PROVINCE'];
                            $this->query("SELECT * FROM Places WHERE PLACE = '$province' AND COUNTY = '' AND PROVINCE='$province' AND COUNTRY='$country'");
                            $eachProvinces = $this->result();
                        }
                        else{
                            $province = $province['PROVINCE'];
                            $this->query("SELECT * FROM Places WHERE PLACE = '$province' AND PROVINCE='$province' AND COUNTRY='$country'");
                            $eachProvinces = $this->result();
                        }
                    

                            foreach($eachProvinces as $eachProvince){

                                $provinces->provinceName[$i]  =  New ProvinceData();

                                $place_id = $eachProvince['ID'];
                                $place = $eachProvince['PLACE'];
                                $province = $eachProvince['PROVINCE'];
        
                                $place = str_ireplace('<', ',', $place);
                                $place = str_ireplace('+', "'", $place);
        
                                $province = str_ireplace('<', ',', $province);
                                $province = str_ireplace('+', "'", $province);

                                $provinces->provinceName[$i]->id    =  $place_id;
                                $provinces->provinceName[$i]->name  =  $province;

                            }

                                $i++;
                        }

        return $provinces; 

    }

} 



