<?php


class userFollow extends Db{

    public function follow($user_id, $user_id_follow){

        global $date;
    
        $this->query("SELECT * FROM User_Follow WHERE USER_ID = ?");
        $this->bind(1,$user_id);

            if($this->count() < 3000){

                    $this->query("SELECT * FROM User_Follow WHERE USER_ID = ? AND USER_FOLLOWING_ID = ?");
                    $this->bind(1,$user_id);
                    $this->bind(2,$user_id_follow);
                    
                        if($this->count() == 0){

                                $this->query("INSERT INTO User_Follow (USER_ID,USER_FOLLOWING_ID,DATE) VALUES (?,?,?)");
                                $this->bind(1,$user_id);
                                $this->bind(2,$user_id_follow);
                                $this->bind(3,$date);
                                $this->execute();
            
                                $this->query("SELECT * FROM Notifications WHERE USER = ? AND USER_2 = ? AND FOLLOWERS = '1'");
                                $this->bind(1,$user_id_follow);
                                $this->bind(2,$user_id);
            
                                    if($this->count() == 0){
                
                                            $this->query("INSERT INTO Notifications (USER,USER_2,FOLLOWERS,DATE,VIEWED_OUTER) VALUES (?,?,?,?,?)");
                                            $this->bind(1,$user_id_follow);
                                            $this->bind(2,$user_id);
                                            $this->bind(3,1);
                                            $this->bind(4,$date);
                                            $this->bind(5,0);
                                            $this->execute();
                    
                                            $this->closeConnection();
                    
                                            return true;
                
                                    }

                        }
            

            }


        $this->closeConnection();

    }
}






class userUnfollow extends Db{

    public function unfollow($user_id, $user_id_follow){

        $this->query("SELECT * FROM User_Follow WHERE USER_ID = ? AND USER_FOLLOWING_ID = ?");
        $this->bind(1,$user_id);
        $this->bind(2,$user_id_follow);
        
            if($this->count() == 1){

                $this->query("DELETE FROM User_Follow WHERE USER_ID = ? AND USER_FOLLOWING_ID = ?");
                $this->bind(1,$user_id);
                $this->bind(2,$user_id_follow);
                $this->execute();

                $this->query("DELETE FROM Notifications WHERE USER = ? AND USER_2 = ? AND FOLLOWERS = ?");
                $this->bind(1,$user_id_follow);
                $this->bind(2,$user_id);
                $this->bind(3,1);
                $this->execute();

                $this->closeConnection();
                    
                return true;


            }

        $this->closeConnection();

    }
}