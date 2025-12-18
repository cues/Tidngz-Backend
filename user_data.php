<?php



Class UserData extends Db{
  public function users_following($user_id){
    global $con;
  
          $ids = array($user_id);
  
          $this->query("SELECT * FROM User_Following WHERE USER_ID = ? ORDER BY (case when OTHER_USER_ID = '1' then 1 else 2 end), ID DESC ");
          $this->bind(1,$user_id);

          if($this->count() > 0){
            $array_users = $this->result();
            foreach($array_users as $user){
              array_push($ids, (int)$user['OTHER_USER_ID']);
            }
          }
 
          $ids = implode(',',$ids);
          $ids = !$ids ? 0 : $ids;

          $this->closeConnection();

          return $ids;
  
  }
  
  
  
  
  
  
  public function places_following($user_id){
    global $con;
  
    $ids = array();
  
    $this->query("SELECT * FROM Places_Following WHERE USER = ? ORDER BY PLACE_ID ASC ");
    $this->bind(1,$user_id);
  
      if($this->count() > 0){

        $array_places = $this->result();
        foreach($array_places as $places){
            $place_id = $places['PLACE_ID'];

            $this->query("SELECT * FROM Places WHERE ID = ?");
            $this->bind(1,$place_id);
            $row_place      =   $this->single();
            $place_name     =   $row_place['PLACE'];
            $place_country  =   $row_place['COUNTRY'];

            $this->query("SELECT * FROM Places WHERE (PLACE= ? OR COUNTY = ? OR PROVINCE = ? OR COUNTRY= ?) AND COUNTRY= ? ORDER BY ID ASC ");
            $this->bind(1,$place_name);
            $this->bind(2,$place_name);
            $this->bind(3,$place_name);
            $this->bind(4,$place_name);
            $this->bind(5,$place_country);

            $array_place = $this->result();
            foreach($array_place as $place){
              array_push($ids, (int)$place['ID']);
            }

        }

      }

          $ids = implode(',',$ids);
          $ids = !$ids ? 0 : $ids;

          $this->closeConnection();

          return $ids;

    }





    public function users_blocked($user_id){
      global $con;
      
      $ids = array();

      $this->query("SELECT * from Users_Blocked where (USER = ? or USER_BLOCKED = ?)");
      $this->bind(1,$user_id);
      $this->bind(2,$user_id);

      if($this->count() > 0){
        $array_users = $this->result();
          foreach($array_users as $user){
              $this_user = $user['USER'];

              if($this_user == $user_id){
                array_push($ids, $user['USER_BLOCKED']);
              }else{
                array_push($ids, $user['USER']);
              }
          }
      }

        $ids = implode(',',$ids);
        $ids = !$ids ? 0 : $ids;

        $this->closeConnection();

        return $ids;
    }
    
       
    
}
  


