<?php

class ImageData {
    public function get_article_image($image){
        if($image == '' || $image == null){
          return '';
        }else{
          // return "https://www.wedngz.com/Tidngz/Article_Images/$image";
          return "$image";
        }
    }
    
    public function get_classified_image($image){
        if($image == '' || $image == null){
          return '';
        }else{
          // return "https://www.wedngz.com/Tidngz/Classified_Images/$image";
          return "$image";
        }
    }
    
    public function get_user_image($image){
      if($image == '' || $image == null){
        return '';
      }else{
        // return "https://www.wedngz.com/Tidngz/User_Images/$image";
        return "$image";
      }
    }


    public function get_country_flag($country, $province){
            $flag = $this->flag($country, $province);
            // return "https://www.wedngz.com/Tidngz/Country_Flags/$flag";
            return "$flag";
        
    }
    


    public function flag($country, $province){
        if($country == "Abkhazia"){
            $flag = 'Abkhazia.png';
          }
          else if($country == "Afghanistan"){
            $flag = 'Afghanistan.png';
          }
          else if($country == "Åland Islands"){
            $flag = 'Aland Island.png';
          }
          else if($country == "Albania"){
            $flag = 'Albania.png';
          }
          else if($country == "Algeria"){
            $flag = 'Algeria.png';
          }
          else if($country == "American Samoa"){
            $flag = 'American Samoa.png';
          }
          else if($country == "Andorra"){
            $flag = 'Andorra.png';
          }
          else if($country == "Anguilla"){
            $flag = 'Anguilla.png';
          }
          else if($country == "Angola"){
            $flag = 'Angola.png';
          }
          else if($country == "Antigua and Barbuda"){
            $flag = 'Antigua and Barbuda.png';
          }
          else if($country == "Argentina"){
            $flag = 'Argentina.png';
          }
          else if($country == "Armenia"){
            $flag = 'Armenia.png';
          }
          else if($country == "Aruba"){
            $flag = 'Aruba.png';
          }
          else if($country == "Australia"){
            $flag = 'Australia.png';
          }
          else if($country == "Austria"){
            $flag = 'Austria.png';
          }
          else if($country == "Azerbaijan"){
            $flag = 'Azerbaijan.png';
          }
          else if($country == "Bahrain"){
            $flag = 'Bahrain.png';
          }
          else if($country == "Bangladesh"){
            $flag = 'Bangladesh.png';
          }
          else if($country == "Barbados"){
            $flag = 'Barbados.png';
          }
          else if($country == "Belarus"){
            $flag = 'Belarus.png';
          }
          else if($country == "Belgium"){
            $flag = 'Belgium.png';
          }
          else if($country == "Belize"){
            $flag = 'Belize.png';
          }
          else if($country == "Benin"){
            $flag = 'Benin.png';
          }
          else if($country == "Bermuda"){
            $flag = 'Bermuda.png';
          }
          else if($country == "Bhutan"){
            $flag = 'Bhutan.png';
          }
          else if($country == "Bolivia"){
            $flag = 'Bolivia.png';
          }
          else if($country == "Bosnia and Herzegovina"){
            $flag = 'Bosnia and Herzegovina.png';
          }
          else if($country == "Botswana"){
            $flag = 'Botswana.png';
          }
          else if($country == "Brazil"){
            $flag = 'Brazil.png';
          }
          else if($country == "British Indian Ocean Territory"){
            $flag = 'British Indian Ocean Territory.gif';
          }
          else if($country == "British Virgin Islands"){
            $flag = 'British Virgin Islands.png';
          }
          else if($country == "Brunei"){
            $flag = 'Brunei.png';
          }
          else if($country == "Bulgaria"){
            $flag = 'Bulgaria.png';
          }
          else if($country == "Burkina Faso"){
            $flag = 'Burkina Faso.png';
          }
          else if($country == "Burundi"){
            $flag = 'Burundi.png';
          }
          else if($country == "Cambodia"){
            $flag = 'Cambodia.png';
          }
          else if($country == "Cameroon"){
            $flag = 'Cameroon.png';
          }
          else if($country == "Canada"){
            $flag = 'Canada.png';
          }
          else if($country == "Cape Verde"){
            $flag = 'Cape Verde.png';
          }
          else if($country == "Caribbean Netherlands"){
            $flag = 'Caribbean Netherlands.png';
          }
          else if($country == "Cayman Islands"){
            $flag = 'Cayman Islands.png';
          }
          else if($country == "Central African Republic"){
            $flag = 'Central African Republic.png';
          }
          else if($country == "Chad"){
            $flag = 'Chad.png';
          }
          else if($country == "Chile"){
            $flag = 'Chile.png';
          }
          else if($country == "China"){
            $flag = 'China.png';
          }
          else if($country == "Christmas Island"){
            $flag = 'Christmas Island.png';
          }
          else if($country == "Cocos (Keeling) Islands"){
            $flag = 'Cocos (Keeling) Islands.png';
          }
          else if($country == "Colombia"){
            $flag = 'Colombia.png';
          }
          else if($country == "Comoros"){
            $flag = 'Comoros.png';
          }
          else if($country == "Cook Islands"){
            $flag = 'Cook Islands.png';
          }
          else if($country == "Costa Rica"){
            $flag = 'Costa Rica.png';
          }
          else if($country == "Côte d'Ivoire"){
            $flag = 'Cote d_Ivoire.png';
          }
          else if($country == "Croatia"){
            $flag = 'Croatia.png';
          }
          else if($country == "Cuba"){
            $flag = 'Cuba.png';
          }
          else if($country == "Curaçao"){
            $flag = 'Curacao.png';
          }
          else if($country == "Cyprus"){
            $flag = 'Cyprus.png';
          }
          else if($country == "Czechia"){
            $flag = 'Czech Republic.png';
          }
          else if($country == "Democratic Republic of the Congo"){
            $flag = 'Democratic Republic of the Congo.png';
          }
          else if($country == "Denmark"){
            $flag = 'Denmark.png';
          }
          else if($country == "Djibouti"){
            $flag = 'Djibouti.png';
          }
          else if($country == "Dominica"){
            $flag = 'Dominica.png';
          }
          else if($country == "Dominican Republic"){
            $flag = 'Dominican Republic.png';
          }
          else if($country == "Ecuador"){
            $flag = 'Ecuador.png';
          }
          else if($country == "Egypt"){
            $flag = 'Egypt.png';
          }
          else if($country == "El Salvador"){
            $flag = 'El Salvador.png';
          }
          else if($country == "Equatorial Guinea"){
            $flag = 'Equatorial Guinea.png';
          }
          else if($country == "Eritrea"){
            $flag = 'Eritrea.png';
          }
          else if($country == "Estonia"){
            $flag = 'Estonia.png';
          }
          else if($country == "Ethiopia"){
            $flag = 'Ethiopia.png';
          }
          else if($country == "Falkland Islands (Islas Malvinas)"){
            $flag = 'Falkland Islands.png';
          }
          else if($country == "Faroe Islands"){
            $flag = 'Faroe Islands.png';
          }
          else if($country == "Fiji"){
            $flag = 'Fiji.png';
          }
          else if($country == "Finland"){
            $flag = 'Finland.png';
          }
          else if($country == "France"){
            $flag = 'France.png';
          }
          else if($country == "French Guiana"){
            $flag = 'French Guiana.png';
          }
          else if($country == "French Polynesia"){
            $flag = 'French Polynesia.png';
          }
          else if($country == "Gabon"){
            $flag = 'Gabon.png';
          }
          else if($country == "Georgia"){
            $flag = 'Georgia.png';
          }
          else if($country == "Germany"){
            $flag = 'Germany.png';
          }
          else if($country == "Ghana"){
            $flag = 'Ghana.png';
          }
          else if($country == "Gibraltar"){
            $flag = 'Gibraltar.png';
          }
          else if($country == "Greece"){
            $flag = 'Greece.png';
          }
          else if($country == "Greenland"){
            $flag = 'Greenland.png';
          }
          else if($country == "Grenada"){
            $flag = 'Grenada.png';
          }
          else if($country == "Guadeloupe"){
            $flag = 'Guadeloupe.png';
          }
          else if($country == "Guam"){
            $flag = 'Guam.png';
          }
          else if($country == "Guatemala"){
            $flag = 'Guatemala.png';
          }
          else if($country == "Guernsey"){
            $flag = 'Guernsey.png';
          }
          else if($country == "Guinea-Bissau"){
            $flag = 'Guinea-Bissau.png';
          }
          else if($country == "Guinea"){
            $flag = 'Guinea.png';
          }
          else if($country == "Guyana"){
            $flag = 'Guyana.png';
          }
          else if($country == "Haiti"){
            $flag = 'Haiti.png';
          }
          else if($country == "Honduras"){
            $flag = 'Honduras.png';
          }
          else if($country == "Hong Kong"){
            $flag = 'Hong Kong.png';
          }
          else if($country == "Hungary"){
            $flag = 'Hungary.png';
          }
          else if($country == "Iceland"){
            $flag = 'Iceland.png';
          }
          else if($country == "India"){
            $flag = 'India.png';
          }
          else if($country == "Indonesia"){
            $flag = 'Indonesia.png';
          }
          else if($country == "Iran"){
            $flag = 'Iran.png';
          }
          else if($country == "Iraq"){
            $flag = 'Iraq.png';
          }
          else if($country == "Ireland"){
            $flag = 'Ireland.png';
          }
          else if($country == "Isle of Man"){
            $flag = 'Isle of Man.png';
          }
          else if($country == "Israel"){
            $flag = 'Israel.png';
          }
          else if($country == "Italy"){
            $flag = 'Italy.png';
          }
          else if($country == "Jamaica"){
            $flag = 'Jamaica.png';
          }
          else if($country == "Japan"){
            $flag = 'Japan.png';
          }
          else if($country == "Jersey"){
            $flag = 'Jersey.png';
          }
          else if($country == "Jordan"){
            $flag = 'Jordan.png';
          }
          else if($country == "Kazakhstan"){
            $flag = 'Kazakhstan.png';
          }
          else if($country == "Kenya"){
            $flag = 'Kenya.png';
          }
          else if($country == "Kiribati"){
            $flag = 'Kiribati.png';
          }
          else if($country == "Kosovo"){
            $flag = 'Kosovo.png';
          }
          else if($country == "Kuwait"){
            $flag = 'Kuwait.png';
          }
          else if($country == "Kyrgyzstan"){
            $flag = 'Kyrgyzstan.png';
          }
          else if($country == "Laos"){
            $flag = 'Laos.png';
          }
          else if($country == "Latvia"){
            $flag = 'Latvia.png';
          }
          else if($country == "Lebanon"){
            $flag = 'Lebanon.png';
          }
          else if($country == "Lesotho"){
            $flag = 'Lesotho.png';
          }
          else if($country == "Liberia"){
            $flag = 'Liberia.png';
          }
          else if($country == "Libya"){
            $flag = 'Libya.png';
          }
          else if($country == "Liechtenstein"){
            $flag = 'Liechtenstein.png';
          }
          else if($country == "Lithuania"){
            $flag = 'Lithuania.png';
          }
          else if($country == "Luxembourg"){
            $flag = 'Luxembourg.png';
          }
          else if($country == "Macau"){
            $flag = 'Macau.png';
          }
          else if($country == "Macedonia (FYROM)"){
            $flag = 'Macedonia.png';
          }
          else if($country == "Madagascar"){
            $flag = 'Madagascar.png';
          }
          else if($country == "Malawi"){
            $flag = 'Malawi.png';
          }
          else if($country == "Malaysia"){
            $flag = 'Malaysia.png';
          }
          else if($country == "Maldives"){
            $flag = 'Maldives.png';
          }
          else if($country == "Mali"){
            $flag = 'Mali.png';
          }
          else if($country == "Malta"){
            $flag = 'Malta.png';
          }
          else if($country == "Marshall Islands"){
            $flag = 'Marshall Islands.png';
          }
          else if($country == "Martinique"){
            $flag = 'France.png';
          }
          else if($country == "Mauritania"){
            $flag = 'Mauritania.png';
          }
          else if($country == "Mauritius"){
            $flag = 'Mauritius.png';
          }
          else if($country == "Mayotte"){
            $flag = 'Mayotte.png';
          }
          else if($country == "Mexico"){
            $flag = 'Mexico.png';
          }
          else if($country == "Federated States of Micronesia"){
            $flag = 'Micronesia.png';
          }
          else if($country == "Moldova"){
            $flag = 'Moldova.png';
          }
          else if($country == "Monaco"){
            $flag = 'Monaco.png';
          }
          else if($country == "Mongolia"){
            $flag = 'Mongolia.png';
          }
          else if($country == "Montserrat"){
            $flag = 'Montserrat.png';
          }
          else if($country == "Montenegro"){
            $flag = 'Montenegro.png';
          }
          else if($country == "Morocco"){
            $flag = 'Morocco.png';
          }
          else if($country == "Mozambique"){
            $flag = 'Mozambique.png';
          }
          else if($country == "Myanmar (Burma)"){
            $flag = 'Myanmar.png';
          }
          else if($country == "Namibia"){
            $flag = 'Namibia.png';
          }
          else if($country == "Nauru"){
            $flag = 'Nauru.png';
          }
          else if($country == "Nepal"){
            $flag = 'Nepal.png';
          }
          else if($country == "Netherlands"){
            $flag = 'Netherlands.png';
          }
          else if($country == "New Caledonia"){
            $flag = 'New Caledonia.png';
          }
          else if($country == "New Zealand"){
            $flag = 'New Zealand.jpg';
          }
          else if($country == "Nicaragua"){
            $flag = 'Nicaragua.png';
          }
          else if($country == "Niger"){
            $flag = 'Niger.png';
          }
          else if($country == "Nigeria"){
            $flag = 'Nigeria.png';
          }
          else if($country == "Niue"){
            $flag = 'Niue.png';
          }
          else if($country == "Norfolk Island"){
            $flag = 'Norfolk Island.png';
          }
          else if($country == "North Korea"){
            $flag = 'North Korea.png';
          }
          else if($country == "Northern Mariana Islands"){
            $flag = 'Northern Mariana Islands.png';
          }
          else if($country == "Norway"){
            $flag = 'Norway.png';
          }
          else if($country == "Oman"){
            $flag = 'Oman.png';
          }
          else if($country == "Pakistan"){
            $flag = 'Pakistan.png';
          }
          else if($country == "Palau"){
            $flag = 'Palau.png';
          }
          else if($country == "Palestine"){
            $flag = 'Palestine.png';
          }
          else if($country == "Panama"){
            $flag = 'Panama.png';
          }
          else if($country == "Papua New Guinea"){
            $flag = 'Papua New Guinea.png';
          }
          else if($country == "Paraguay"){
            $flag = 'Paraguay.png';
          }
          else if($country == "Peru"){
            $flag = 'Peru.png';
          }
          else if($country == "Philippines"){
            $flag = 'Philippines.png';
          }
          else if($country == "Pitcairn Islands"){
            $flag = 'Pitcairn Islands.png';
          }
          else if($country == "Poland"){
            $flag = 'Poland.png';
          }
          else if($country == "Portugal"){
            $flag = 'Portugal.png';
          }
          else if($country == "Puerto Rico"){
            $flag = 'Puerto Rico.png';
          }
          else if($country == "Qatar"){
            $flag = 'Qatar.png';
          }
          else if($country == "Republic of the Congo"){
            $flag = 'Republic of the Congo.png';
          }
          else if($country == "Reunion"){
            $flag = 'Reunion.png';
          }
          else if($country == "Romania"){
            $flag = 'Romania.png';
          }
          else if($country == "Russia"){
            $flag = 'Russia.png';
          }
          else if($country == "Rwanda"){
            $flag = 'Rwanda.png';
          }
          else if($country == "Saint-Barthélemy"){
            $flag = 'Saint Barthelemy.png';
          }
          else if($country == "Saint Kitts and Nevis"){
            $flag = 'Saint Kitts And Nevis.png';
          }
          else if($country == "Saint Lucia"){
            $flag = 'Saint Lucia.png';
          }
          else if($country == "Saint Martin"){
            $flag = 'Saint Martin.png';
          }
          else if($country == "Saint Pierre and Miquelon"){
            $flag = 'Saint Pierre and Miquelon.png';
          }
          else if($country == "Saint Vincent and the Grenadines"){
            $flag = 'Saint Vincent and the Grenadines.png';
          }
          else if($country == "Samoa"){
            $flag = 'Samoa.png';
          }
          else if($country == "San Marino"){
            $flag = 'San Marino.png';
          }
          else if($country == "São Tomé and Príncipe"){
            $flag = 'Sao Tome and Principe.png';
          }
          else if($country == "Saudi Arabia"){
            $flag = 'Saudi Arabia.png';
          }
          else if($country == "Senegal"){
            $flag = 'Senegal.png';
          }
          else if($country == "Serbia"){
            $flag = 'Serbia.png';
          }
          else if($country == "Seychelles"){
            $flag = 'Sychelles.png';
          }
          else if($country == "Sierra Leone"){
            $flag = 'Sierra Leone.png';
          }
          else if($country == "Singapore"){
            $flag = 'Singapore.png';
          }
          else if($country == "Sint Maarten"){
            $flag = 'Sint Maarten.jpeg';
          }
          else if($country == "Slovakia"){
            $flag = 'Slovakia.png';
          }
          else if($country == "Slovenia"){
            $flag = 'Slovenia.png';
          }
          else if($country == "Solomon Islands"){
            $flag = 'Solomon Islands.png';
          }
          else if($country == "Somalia"){
            $flag = 'Somalia.png';
          }
          else if($country == "South Africa"){
            $flag = 'South Africa.png';
          }
          else if($country == "South Korea"){
            $flag = 'South Korea.png';
          }
          else if($country == "South Georgia and the South Sandwich Islands"){
            $flag = 'South Georgia and the South Sandwich Islands.png';
          }
          else if($country == "South Sudan"){
            $flag = 'South Sudan.png';
          }
          else if($country == "Spain"){
            $flag = 'Spain.png';
          }
          else if($country == "Sri Lanka"){
            $flag = 'Sri Lanka.png';
          }
          else if($country == "Sudan"){
            $flag = 'Sudan.png';
          }
          else if($country == "Suriname"){
            $flag = 'Suriname.png';
          }
          else if($country == "Svalbard and Jan Mayen"){
            $flag = 'Svalbard and Jan Mayen.png';
          }
          else if($country == "Swaziland"){
            $flag = 'Swaziland.png';
          }
          else if($country == "Sweden"){
            $flag = 'Sweden.png';
          }
          else if($country == "Switzerland"){
            $flag = 'Switzerland.png';
          }
          else if($country == "Syria"){
            $flag = 'Syria.png';
          }
          else if($country == "Taiwan"){
            $flag = 'Taiwan.png';
          }
          else if($country == "Tajikistan"){
            $flag = 'Tajikistan.png';
          }
          else if($country == "Tanzania"){
            $flag = 'Tanzania.png';
          }
          else if($country == "Thailand"){
            $flag = 'Thailand.png';
          }
          else if($country == "The Bahamas"){
            $flag = 'The Bahamas.png';
          }
          else if($country == "The Gambia"){
            $flag = 'The Gambia.png';
          }
          else if($country == "Timor-Leste"){
            $flag = 'Timor-Leste.png';
          }
          else if($country == "Togo"){
            $flag = 'Togo.png';
          }
          else if($country == "Tokelau"){
            $flag = 'Tokelau.png';
          }
          else if($country == "Tonga"){
            $flag = 'Tonga.png';
          }
          else if($country == "Trinidad and Tobago"){
            $flag = 'Trinidad and Tobago.png';
          }
          else if($country == "Tunisia"){
            $flag = 'Tunisia.png';
          }
          else if($country == "Turkey"){
            $flag = 'Turkey.png';
          }
          else if($country == "Turkmenistan"){
            $flag = 'Turkmenistan.png';
          }
          else if($country == "Turks and Caicos Islands"){
            $flag = 'Turks and Caicos Islands.png';
          }
          else if($country == "Tuvalu"){
            $flag = 'Tuvalu.png';
          }
          else if($country == "Uganda"){
            $flag = 'Uganda.png';
          }
          else if($country == "Ukraine"){
            $flag = 'Ukraine.png';
          }
          else if($country == "United Arab Emirates"){
            $flag = 'United Arab Emirates.png';
          }
          else if($country == "United Kingdom"){
            $flag = 'United Kingdom.png';
          }
          else if($country == "Saint Helena, Ascension and Tristan da Cunha"){
            $flag = 'United Kingdom.png';
          }
          else if($country == "United States of America" || $country == "United States" || $country == "USA"){
            $flag = 'United States.png';
          }
          else if($country == "Uruguay"){
            $flag = 'Uruguay.png';
          }
          else if($country == "Uzbekistan"){
            $flag = 'Uzbekistan.png';
          }
          else if($country == "Vanuatu"){
            $flag = 'Vanuatu.png';
          }
          else if($country == "Vatican City"){
            $flag = 'Vatican City.png';
          }
          else if($country == "Venezuela"){
            $flag = 'Venezuela.png';
          }
          else if($country == "Vietnam"){
            $flag = 'Vietnam.png';
          }
          else if($country == "U.S. Virgin Islands"){
            $flag = 'Virgin Islands.png';
          }
          else if($country == "Wallis and Futuna"){
            $flag = 'Wallis and Futuna.png';
          }
          else if($country == "Western Sahara"){
            $flag = 'Western Sahara.png';
          }
          else if($country == "Yemen"){
            $flag = 'Yemen.png';
          }
          else if($country == "Zambia"){
            $flag = 'Zambia.png';
          }
          else if($country == "Zimbabwe"){
            $flag = 'Zimbabwe.png';
          }
          else if($country == "Antarctica"){
            $flag = 'Antarctica.png';
          }
          else if($country == "Bouvet Island"){
            $flag = 'Norway.png';
          }
          else if($country =="Heard Island and McDonald Islands"){
            $flag = 'Australia.png';
          }
          else if($country =="French Southern and Antarctic Lands"){
            $flag = 'French Southern and Antarctic Lands.png';
          }
          else if($country =="United States Minor Outlying Islands"){
            $flag = 'United States.png';
          }
          else{
            $flag = "tidngz-logo.png";
          }
          if($province == 'England'){
            $flag = 'England.png';
          }
          if($province == 'Scotland'){
            $flag = 'Scotland.png';
          }
          if($province == 'Wales'){
            $flag = 'Wales.png';
          }
          if($province == 'Northern Ireland'){
            $flag = 'Northern Ireland.png';
          }
          if($province == 'Ascension'){
            $flag = 'Ascension.png';
          }
          if($province == 'Saint Helena'){
            $flag = 'Saint Helena.png';
          }
          if($province == 'Tristan da Cunha'){
            $flag = 'Tristan da Cunha.png';
          }

          return $flag;
    }
}
