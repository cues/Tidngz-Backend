<?php

class Article_Ids {
    public $total_records;
    public $last_articles_id;
    public $article_ids;
}

// class IDs {
//     public $article_id;
// }


class AllArticles extends Db{
public function all_articles($user_id, $article_source, $place_id, $place_local_id, $tag, 
    $articles_id, $user_1, $options_id, $top, $calender_1, $date_1, $calender_2, $date_2){

    global $article_limit;
    global $users_articles;
  
    $user_data         =    New UserData();
    $users_following   =    $user_data->users_following($user_id);
    $places_following  =    $user_data->places_following($user_id);
    $users_blocked     =    $user_data->users_blocked($user_id);

    $place_data        =    New PlaceData();
    $all_places        =    $place_data->all_places($place_id);

    // $tag_data          =    New TagData();
    // $all_tags          =    $tag_data->all_tags($tag);

    // $bookmark_data     =    New BookmarkData();
    // $all_bookmarks     =    $bookmark_data->all_bookmarks($user_id);
        
    // return $places_following;
    // return $all_places;


    // HOME
    if($article_source == 11){
        //  $articles = "SELECT * FROM Articles ORDER BY ID ASC LIMIT $article_limit";
        $articles = "SELECT * FROM Articles WHERE ((USER_ID IN ($users_following)) OR (PLACE IN ($places_following))) AND USER_ID NOT IN ($users_blocked) AND ((FAKE_PN = '0' AND ACCEPTED = '1') OR ($users_articles)) ORDER BY ID DESC LIMIT $article_limit";
    }

    if($article_source == 12){
        $articles = "SELECT * FROM Articles WHERE ((USER_ID IN ($users_following)) OR (PLACE IN ($places_following))) AND USER_ID NOT IN ($users_blocked) AND $option ((FAKE_PN = '0' AND ACCEPTED = '1')  OR ($users_articles)) ORDER BY ID DESC LIMIT $article_limit";
    }

    if($article_source == 13){
        $articles = "SELECT * FROM Articles WHERE ((USER_ID IN ($users_following)) OR (PLACE IN ($places_following))) AND USER_ID NOT IN ($users_blocked) AND $option  DATE between '$date_top' and '$date' AND ((FAKE_PN = '0' AND ACCEPTED = '1')  OR ($users_articles)) ORDER BY ID DESC LIMIT $article_limit";
    }

    if($article_source == 14){
        $articles = "SELECT * FROM Articles WHERE ((USER_ID IN ($users_following)) OR (PLACE IN ($places_following))) AND USER_ID NOT IN ($users_blocked) AND $option DATE between '$date_1' and '$date_2' AND ((FAKE_PN = '0' AND ACCEPTED = '1')  OR ($users_articles)) ORDER BY ID DESC LIMIT $article_limit";
    }

    
    // PLACE
    if($article_source == 21){
        $articles = "SELECT * FROM Articles WHERE PLACE IN ($all_places) AND USER_ID NOT IN ($users_blocked) AND ((FAKE_PN = '0' AND ACCEPTED = '1') OR ($users_articles)) ORDER BY ID DESC LIMIT $article_limit";
    }

    if($article_source == 22){
        $articles = "SELECT * FROM Articles WHERE PLACE IN ($all_places) AND $option USER_ID NOT IN ($users_blocked) AND ((FAKE_PN = '0' AND ACCEPTED = '1') OR ($users_articles)) ORDER BY ID DESC LIMIT $article_limit";
    }

    if($article_source == 23){
        $articles = "SELECT * FROM Articles WHERE PLACE IN ($all_places) AND $option DATE between '$date_top' and '$date' AND USER_ID NOT IN ($users_blocked) AND ((FAKE_PN = '0' AND ACCEPTED = '1') OR ($users_articles)) ORDER BY ID DESC LIMIT $article_limit";
    }

    if($article_source == 24){
        $articles = "SELECT * FROM Articles WHERE PLACE IN ($all_places) AND $option DATE between '$date_1' and '$date_2' AND USER_ID NOT IN ($users_blocked) AND ((FAKE_PN = '0' AND ACCEPTED = '1')  OR ($users_articles)) ORDER BY ID DESC LIMIT $article_limit";
    }



    $all_articles = new Article_Ids();

    $this->query($articles);
    $all_articles->total_records = (int)$this->count();

    if($all_articles->total_records > 0){
        $ids = array();
        $array_articles = $this->result();
        foreach($array_articles as $article){
             array_push($ids, (int)$article['ID']);
        } 

        $all_articles->article_ids = $ids;


        $last_array = $this->single();
        $all_articles->last_articles_id = (int)$last_array['ID'];
      }
  
    return $all_articles;
    
    $this->closeConnection();
  
  }


  // Find similar articles based on date, title, description, place, place_local, and article content
  public function find_similar_articles($article_id, $limit = 10){

    $similar_articles = new Article_Ids();

    // First, get the reference article details
    $this->query("SELECT * FROM Articles WHERE ID = ?");
    $this->bind(1, $article_id);
    $reference_article = $this->single();

    if($this->count() == 0){
        $similar_articles->total_records = 0;
        $similar_articles->article_ids = array();
        return $similar_articles;
    }

    // Extract key fields from reference article
    $ref_date         = isset($reference_article['DATE']) ? $reference_article['DATE'] : '';
    $ref_place        = isset($reference_article['PLACE']) ? $reference_article['PLACE'] : 0;
    $ref_place_local  = isset($reference_article['PLACE_LOCAL']) ? $reference_article['PLACE_LOCAL'] : 0;

    // Fixed query without HAVING clause issue
    $this->query("
        SELECT ID, SIMILARITY_SCORE
        FROM (
            SELECT 
                a.ID,
                (
                    CASE WHEN a.DATE = ? THEN 5 ELSE 0 END +
                    CASE WHEN a.PLACE = ? AND ? > 0 THEN 10 ELSE 0 END +
                    CASE WHEN a.PLACE_LOCAL = ? AND ? > 0 THEN 8 ELSE 0 END
                ) as SIMILARITY_SCORE
            FROM Articles a
            WHERE a.ID != ?
        ) as scored_articles
        WHERE SIMILARITY_SCORE > 0
        ORDER BY SIMILARITY_SCORE DESC, ID DESC
        LIMIT ?
    ");

    $this->bind(1, $ref_date);
    $this->bind(2, $ref_place);
    $this->bind(3, $ref_place);
    $this->bind(4, $ref_place_local);
    $this->bind(5, $ref_place_local);
    $this->bind(6, $article_id);
    $this->bind(7, $limit);

    $result = $this->result();
    $similar_articles->total_records = (int)$this->count();

    if($similar_articles->total_records > 0){
        $ids = array();
        foreach($result as $article){
            array_push($ids, (int)$article['ID']);
        }
        $similar_articles->article_ids = $ids;
        $similar_articles->last_articles_id = (int)$result[count($result) - 1]['ID'];
    } else {
        $similar_articles->article_ids = array();
        $similar_articles->last_articles_id = 0;
    }

    $this->closeConnection();
    return $similar_articles;
  }


  // Helper function to extract keywords from text
  private function extract_keywords($text, $word_count = 5){
    if(empty($text)) return '';
    
    // Remove special characters and extra spaces
    $text = preg_replace('/[^a-zA-Z0-9\s]/', ' ', $text);
    $text = preg_replace('/\s+/', ' ', trim($text));
    
    // Split into words and take first N meaningful words (skip common words)
    $words = explode(' ', $text);
    $common_words = array('the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'is', 'was', 'are', 'were', 'be', 'been', 'has', 'have', 'had');
    
    $keywords = array();
    foreach($words as $word){
        $word = strtolower(trim($word));
        if(strlen($word) > 2 && !in_array($word, $common_words)){
            $keywords[] = $word;
            if(count($keywords) >= $word_count) break;
        }
    }
    
    return implode(' ', $keywords);
  }


  // Find similar articles with simpler approach (faster query)
  public function find_similar_articles_simple($article_id, $limit = 10){

    $similar_articles = new Article_Ids();
    
    // Ensure limit is an integer
    $limit = (int)$limit;
    if($limit <= 0) $limit = 10;

    // Get the reference article
    $this->query("SELECT DATE, PLACE, PLACE_LOCAL FROM Articles WHERE ID = ?");
    $this->bind(1, $article_id);
    $reference = $this->single();

    if($this->count() == 0){
        $similar_articles->total_records = 0;
        $similar_articles->article_ids = array();
        return $similar_articles;
    }

    $ref_date = isset($reference['DATE']) ? $reference['DATE'] : '';
    $ref_place = isset($reference['PLACE']) ? (int)$reference['PLACE'] : 0;
    $ref_place_local = isset($reference['PLACE_LOCAL']) ? (int)$reference['PLACE_LOCAL'] : 0;

    // Simpler query: Find articles with same date, place, or place_local
    $this->query("
        SELECT a.ID
        FROM Articles a
        WHERE a.ID != ?
            AND (
                a.DATE = ?
                OR (? > 0 AND a.PLACE = ?)
                OR (? > 0 AND a.PLACE_LOCAL = ?)
            )
        ORDER BY 
            CASE WHEN a.DATE = ? THEN 0 ELSE 1 END,
            CASE WHEN ? > 0 AND a.PLACE = ? THEN 0 ELSE 1 END,
            CASE WHEN ? > 0 AND a.PLACE_LOCAL = ? THEN 0 ELSE 1 END,
            a.ID DESC
        LIMIT ?
    ");

    $this->bind(1, $article_id);
    $this->bind(2, $ref_date);
    $this->bind(3, $ref_place);
    $this->bind(4, $ref_place);
    $this->bind(5, $ref_place_local);
    $this->bind(6, $ref_place_local);
    $this->bind(7, $ref_date);
    $this->bind(8, $ref_place);
    $this->bind(9, $ref_place);
    $this->bind(10, $ref_place_local);
    $this->bind(11, $ref_place_local);
    $this->bind(12, $limit);

    $result = $this->result();
    $similar_articles->total_records = (int)$this->count();

    if($similar_articles->total_records > 0){
        $ids = array();
        foreach($result as $article){
            array_push($ids, (int)$article['ID']);
        }
        $similar_articles->article_ids = $ids;
        $similar_articles->last_articles_id = (int)$result[count($result) - 1]['ID'];
    } else {
        $similar_articles->article_ids = array();
        $similar_articles->last_articles_id = 0;
    }

    $this->closeConnection();
    return $similar_articles;
  }


  // Merge similar articles into one consolidated article
  public function merge_similar_articles($article_id, $user_id = 0){

    $merge_result = new stdClass();
    $merge_result->success = false;
    $merge_result->new_article_id = 0;
    $merge_result->merged_count = 0;
    $merge_result->message = '';

    try {
        // Find similar articles
        $similar = $this->find_similar_articles_simple($article_id, 50);

        if($similar->total_records == 0){
            $merge_result->message = 'No similar articles found to merge';
            return $merge_result;
        }

        // Add the original article ID to the list
        $all_article_ids = array_merge(array($article_id), $similar->article_ids);
        $ids_string = implode(',', $all_article_ids);

        // Get all article details ordered by ID ASC (oldest first)
        $this->query("SELECT * FROM Articles WHERE ID IN ($ids_string) ORDER BY ID ASC");
        $articles = $this->result();

        if(count($articles) == 0){
            $merge_result->message = 'Could not retrieve articles for merging';
            return $merge_result;
        }

        // Use the oldest article (lowest ID) as the base
        $base_article = $articles[0];
        
        // Extract base information
        $merged_title = isset($base_article['TITLE']) ? $base_article['TITLE'] : '';
        $merged_description = isset($base_article['DESCRIPTION']) ? $base_article['DESCRIPTION'] : '';
        $merged_article = isset($base_article['ARTICLE']) ? $base_article['ARTICLE'] : '';
        $merged_date = isset($base_article['DATE']) ? $base_article['DATE'] : date('Y-m-d H:i:s');
        $merged_place = isset($base_article['PLACE']) ? (int)$base_article['PLACE'] : 0;
        $merged_place_local = isset($base_article['PLACE_LOCAL']) ? (int)$base_article['PLACE_LOCAL'] : 0;
        $merged_user_id = $user_id > 0 ? $user_id : (isset($base_article['USER_ID']) ? (int)$base_article['USER_ID'] : 0);

        // Merge content from all articles
        $merged_content_parts = array();
        $merged_descriptions = array();
        $source_ids = array();

        foreach($articles as $article){
            $article_text = isset($article['ARTICLE']) ? trim($article['ARTICLE']) : '';
            $description_text = isset($article['DESCRIPTION']) ? trim($article['DESCRIPTION']) : '';
            
            if(!empty($article_text) && !in_array($article_text, $merged_content_parts)){
                $merged_content_parts[] = $article_text;
            }
            
            if(!empty($description_text) && !in_array($description_text, $merged_descriptions)){
                $merged_descriptions[] = $description_text;
            }
            
            $source_ids[] = $article['ID'];
        }

        // Combine all content with separators
        $merged_article_content = implode("\n\n--- Merged Content ---\n\n", $merged_content_parts);
        $merged_description_content = implode(" | ", $merged_descriptions);
        
        // Combine descriptions and article content into single ARTICLE field
        $final_article_content = "";
        if(!empty($merged_description_content)){
            $final_article_content = $merged_description_content . "\n\n--- Full Article Content ---\n\n";
        }
        $final_article_content .= $merged_article_content;
        
        // Add source information to title
        $merged_title_final = $merged_title . " (Merged from " . count($articles) . " articles)";
        $source_note = "\n\n[Source Article IDs: " . implode(', ', $source_ids) . "]";
        
        // Simple direct INSERT without DESCRIPTION column
        $this->query("
            INSERT INTO Articles (TITLE, ARTICLE, DATE, PLACE, PLACE_LOCAL, USER_ID, FAKE_PN, ACCEPTED) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $this->bind(1, $merged_title_final);
        $this->bind(2, $final_article_content . $source_note);
        $this->bind(3, $merged_date);
        $this->bind(4, $merged_place);
        $this->bind(5, $merged_place_local);
        $this->bind(6, $merged_user_id);
        $this->bind(7, 0);
        $this->bind(8, 1);

        $this->execute();
        
        // Get the last inserted ID using different method
        $this->query("SELECT LAST_INSERT_ID() as last_id");
        $last_id_result = $this->single();
        $new_article_id = isset($last_id_result['last_id']) ? (int)$last_id_result['last_id'] : 0;
        
        if($new_article_id > 0){
            $merge_result->success = true;
            $merge_result->new_article_id = $new_article_id;
            $merge_result->merged_count = count($articles);
            $merge_result->source_ids = $source_ids;
            $merge_result->message = "Successfully merged " . count($articles) . " articles into new article ID: " . $new_article_id;
        } else {
            $merge_result->message = 'Failed to get new article ID after insert';
        }

    } catch (Exception $e) {
        $merge_result->message = 'Error: ' . $e->getMessage();
    }

    $this->closeConnection();
    return $merge_result;
  }


  // Auto-merge all similar articles in database (batch operation)
  public function auto_merge_all_similar_articles($user_id = 0, $min_similarity_count = 2){

    $merge_results = array();
    $processed_ids = array();

    // Get all articles
    $this->query("SELECT ID FROM Articles WHERE ID NOT IN (" . implode(',', $processed_ids ?: array(0)) . ") ORDER BY ID ASC LIMIT 100");
    $all_articles = $this->result();

    foreach($all_articles as $article){
        $article_id = $article['ID'];
        
        // Skip if already processed
        if(in_array($article_id, $processed_ids)){
            continue;
        }

        // Find similar articles
        $similar = $this->find_similar_articles_simple($article_id, 50);

        // If enough similar articles found, merge them
        if($similar->total_records >= $min_similarity_count - 1){
            $merge_result = $this->merge_similar_articles($article_id, $user_id);
            
            if($merge_result->success){
                $merge_results[] = $merge_result;
                
                // Mark all source articles as processed
                $processed_ids[] = $article_id;
                foreach($similar->article_ids as $sid){
                    $processed_ids[] = $sid;
                }
            }
        } else {
            $processed_ids[] = $article_id;
        }
    }

    $this->closeConnection();
    
    $summary = new stdClass();
    $summary->total_merged = count($merge_results);
    $summary->merge_details = $merge_results;
    $summary->message = "Auto-merged " . count($merge_results) . " sets of similar articles";
    
    return $summary;
  }

}
  