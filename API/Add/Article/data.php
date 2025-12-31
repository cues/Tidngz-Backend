<?php



class AddArticle extends Db {
    public function add_article($user_id, $place_id, $category_id, $title, $description, $link, $youtube, $tags, $latitude = null, $longitude = null, $poi = null){ 
                      
            global $date;   
            if (empty($date)) {
                $date = date("Y-m-d H:i:s");
            }

            // Remove backslashes (e.g. \' becomes ')
            $title = stripslashes((string)$title);
            $description = stripslashes((string)$description);

            // Convert emojis to HTML entities to prevent SQL Error 1366
            // This allows saving emojis even if the DB is not utf8mb4
            $convmap = [0x80, 0x10FFFF, 0, 0xFFFFFF];
            $title = mb_encode_numericentity($title, $convmap, "UTF-8");
            $description = mb_encode_numericentity($description, $convmap, "UTF-8");

            $description = $description == '<p>&nbsp;</p>' ? "" : $description;

            // Handle empty values for nullable fields
            // Use 0 for POI if empty, assuming it's an integer ID or flag
            if ($poi === '' || $poi === null) $poi = 0;
            if ($latitude === '') $latitude = null;
            if ($longitude === '') $longitude = null;

            try {
                $this->query("INSERT INTO Articles
                                ( USER_ID, PLACE_ID, CATEGORY_ID, TITLE, CONTENT, LINK, YOUTUBE, LATITUDE, LONGITUDE, POI, DATE  )
                                VALUES (?,?,?,?,?,?,?,?,?,?,?) ");
                $this->bind(1,$user_id);
                $this->bind(2,$place_id);
                $this->bind(3,$category_id);
                $this->bind(4,$title);
                $this->bind(5,$description);
                $this->bind(6,$link);
                $this->bind(7,$youtube);
                $this->bind(8,$latitude);
                $this->bind(9,$longitude);
                $this->bind(10,$poi);
                $this->bind(11,$date);

                if($this->execute()){

                    // Get the ID of the newly created article
                    $article_id = $this->dbh->lastInsertId();

                    // Now insert the tags linked to this article
                    
                    // Handle tags: Input might be JSON string (["tag1","tag2"])
                    $tags_array = json_decode($tags, true);
                    
                    if (!is_array($tags_array)) {
                        // Fallback: Remove JSON symbols (brackets, quotes, backslashes) and explode
                        $clean_tags = str_replace(['[', ']', '"', "'", '\\'], '', (string)$tags);
                        $tags_array = explode(",", $clean_tags);
                    }

                    // Filter empty tags
                    $tags_array = array_filter($tags_array, function($t) { return trim($t) !== ''; });

                    foreach($tags_array as $tag){
                        $tag = trim($tag);
                        if(empty($tag)) continue;

                        $this->query("INSERT INTO Article_Tags(USER,ARTICLE,TAG,DATE)VALUES(?,?,?,?)");
                        $this->bind(1,$user_id);
                        $this->bind(2,$article_id); // Use real ID
                        $this->bind(3,$tag);
                        $this->bind(4,$date);
                        $this->execute();
                    }
                    


                    $new_article = new Articles();
                    $article_data = $new_article->get_article( $user_id, $article_id );
                    return $article_data;

                }else{
                    return 2;
                }
            } catch (Exception $e) {
                // Return the error message to help debug the 500 error
                return "Error: " . $e->getMessage();
            }
    }
}