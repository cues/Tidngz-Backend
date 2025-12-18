<?php



class FollowUser extends Db{
    public function follow_user($user_id, $other_user_id){

    // Use backticks for `USER` column (reserved word) and check existing follow
    $this->query("SELECT * from User_Following WHERE USER_ID = ? AND OTHER_USER_ID = ?");
    $this->bind(1, $user_id);
    $this->bind(2, $other_user_id);
        $row_place = $this->single();


        if($this->count() > 0){
            
            $this->query("DELETE FROM User_Following WHERE USER_ID = ? AND OTHER_USER_ID = ?");
            $this->bind(1, $user_id);
            $this->bind(2, $other_user_id);
            $this->execute();
            
            // return 1;

        }else{  
            
            // Make insert robust against duplicates by creating a unique index (if missing)
            // and using INSERT IGNORE so concurrent inserts don't fail.
            try{
                // Check if unique index exists
                $this->query("SELECT COUNT(*) as cnt FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = 'User_Following' AND index_name = 'uniq_user_follow'");
                $idx_row = $this->single();
                if(isset($idx_row['cnt']) && (int)$idx_row['cnt'] == 0){
                    // Add unique index on (USER_ID, OTHER_USER_ID)
                    $this->query("ALTER TABLE User_Following ADD UNIQUE INDEX uniq_user_follow (USER_ID, OTHER_USER_ID)");
                    $this->execute();
                }
            }catch(Exception $e){
                // ignore index creation errors; proceed to insert
            }

            // Use INSERT IGNORE to avoid duplicate-key errors if another process inserted concurrently
            $this->query("INSERT IGNORE INTO User_Following (USER_ID, OTHER_USER_ID) VALUES (?, ?)");
            $this->bind(1, $user_id);
            $this->bind(2, $other_user_id);
            $this->execute();
            // return 2;
        }

        $user = new User();
        $updated_user = $user->get_user($user_id, $other_user_id);

        // Return the refreshed user object/array after follow/unfollow
        $this->closeConnection();
        return $updated_user;
    }
}