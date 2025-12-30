<?php



class AddArticle extends Db {
    public function add_article($user_id, $place_id, $category_id, $title, $description, $link, $youtube, $tags, $latitude, $longitude, $poi){ 
                      
            global $date;

            // Remove backslashes (e.g. \' becomes ')
            $title = stripslashes($title);
            $description = stripslashes($description);

            $description = $description == '<p>&nbsp;</p>' ? "" : $description;

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
                // Prepare tags array (but don't insert yet)
                $tags_array = array_filter(explode(",", $tags));

                // Handle tags: Input might be JSON string (["tag1","tag2"])
                $tags_array = json_decode($tags, true);
                
                if (!is_array($tags_array)) {
                    // Fallback: Remove JSON symbols (brackets, quotes, backslashes) and explode
                    $clean_tags = str_replace(['[', ']', '"', "'", '\\'], '', $tags);
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
                
                return $article_id;

            }else{
                return 2;
            }
    }
}