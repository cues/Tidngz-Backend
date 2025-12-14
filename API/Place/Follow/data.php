<?php



class FollowPlace extends Db{
    public function follow_place($user_id, $place_id){

    // Use backticks for `USER` column (reserved word) and check existing follow
    $this->query("SELECT * from Places_Following WHERE PLACE_ID = ? AND `USER` = ?");
    $this->bind(1, $place_id);
    $this->bind(2, $user_id);
        $row_place = $this->single();


        if($this->count() > 0){
            
            $this->query("DELETE FROM Places_Following WHERE PLACE_ID = ? AND `USER` = ?");
            $this->bind(1, $place_id);
            $this->bind(2, $user_id);
            $this->execute();
            
            // return 1;

        }else{  
            
            // Make insert robust against duplicates by creating a unique index (if missing)
            // and using INSERT IGNORE so concurrent inserts don't fail.
            try{
                // Check if unique index exists
                $this->query("SELECT COUNT(*) as cnt FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = 'Places_Following' AND index_name = 'uniq_place_user'");
                $idx_row = $this->single();
                if(isset($idx_row['cnt']) && (int)$idx_row['cnt'] == 0){
                    // Add unique index on (PLACE_ID, USER)
                    $this->query("ALTER TABLE Places_Following ADD UNIQUE INDEX uniq_place_user (PLACE_ID, `USER`)");
                    $this->execute();
                }
            }catch(Exception $e){
                // ignore index creation errors; proceed to insert
            }

            // Use INSERT IGNORE to avoid duplicate-key errors if another process inserted concurrently
            $this->query("INSERT IGNORE INTO Places_Following (PLACE_ID, `USER`) VALUES (?, ?)");
            $this->bind(1, $place_id);
            $this->bind(2, $user_id);
            $this->execute();
            // return 2;
        }

        $place = new Place();
        $updated_place = $place->get_place($user_id, $place_id);

        // Return the refreshed place object/array after follow/unfollow
        return $updated_place;

    }
}