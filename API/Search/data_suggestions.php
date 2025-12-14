<?php

class SearchSuggestions {
    public $count;
    public $items = array();
}

class Items_Suggestions {
    public $type;
    public $item;
}

class Search_Suggestions extends Db{

    public function get_search($user_id){

        
        $new_search = New SearchSuggestions();

        // Enhanced query: Combines frequency (COUNT) with recency (DATE-based scoring)
        // Uses a weighted formula: (COUNT * 2) + recency bonus
        // Recent searches within 7 days get +10 points, within 30 days get +5 points
        $this->query("
            SELECT 
                sh.*,
                (sh.COUNT * 2 + 
                    CASE 
                        WHEN DATEDIFF(NOW(), sh.DATE) <= 7 THEN 10
                        WHEN DATEDIFF(NOW(), sh.DATE) <= 30 THEN 5
                        ELSE 0 
                    END
                ) as RELEVANCE_SCORE
            FROM Search_History sh
            WHERE sh.USER = ? 
                AND sh.SEARCH_ITEM_ID NOT LIKE ? 
                AND sh.ACTIVE = '0' 
                AND sh.ID IN(
                    SELECT MAX(ID) 
                    FROM Search_History 
                    GROUP BY SEARCH_ITEM_ID, USER
                )
            ORDER BY RELEVANCE_SCORE DESC, sh.ID DESC 
            LIMIT 10
        ");
        $this->bind(1, $user_id);
        $this->bind(2, $user_id);
        $row_suggestions = $this->result();

        $new_search->count =  $this->count();

        if($new_search->count > 0){

            $i = 0;
            foreach($row_suggestions as $suggestions){
                $search_item     =  $suggestions['SEARCH_ITEM'];
                $search_item_id  =  $suggestions['SEARCH_ITEM_ID'];


                    $new_search->items[$i] = New Items_Suggestions();

                    $new_search->items[$i]->type = $search_item;

                    $new_search_suggestions = New Search_Type();

                    if($search_item == 'PLACE'){
                        $new_search->items[$i]->item = $new_search_suggestions->search_place_type(0, $user_id, $search_item_id);
                    }
                    if($search_item == 'USER'){
                        $new_search->items[$i]->item = $new_search_suggestions->search_user_type(0, $user_id, $search_item_id);
                    }
                    if($search_item == 'TAG'){
                        $new_search->items[$i]->item = $new_search_suggestions->search_tag_type(0, $search_item_id);
                    }

                    $i++;


            }

        }

        $this->closeConnection();
        return $new_search;

    }


    

    // Get combined suggestions (personalized + trending + similar users)
    public function get_combined_suggestions($user_id){

        $new_search = New SearchSuggestions();
        $all_items = array();
        $seen_ids = array(); // Track what we've already added

        // 1. Get personalized suggestions (up to 10)
        $this->query("
            SELECT 
                sh.*,
                (sh.COUNT * 2 + 
                    CASE 
                        WHEN DATEDIFF(NOW(), sh.DATE) <= 7 THEN 10
                        WHEN DATEDIFF(NOW(), sh.DATE) <= 30 THEN 5
                        ELSE 0 
                    END
                ) as RELEVANCE_SCORE
            FROM Search_History sh
            WHERE sh.USER = ? 
                AND sh.SEARCH_ITEM_ID NOT LIKE ? 
                AND sh.ACTIVE = '0' 
                AND sh.ID IN(
                    SELECT MAX(ID) 
                    FROM Search_History 
                    GROUP BY SEARCH_ITEM_ID, USER
                )
            ORDER BY RELEVANCE_SCORE DESC, sh.ID DESC 
            LIMIT 10
        ");
        $this->bind(1, $user_id);
        $this->bind(2, $user_id);
        $row_suggestions = $this->result();

        if($this->count() > 0){
            foreach($row_suggestions as $suggestions){
                $search_item     =  $suggestions['SEARCH_ITEM'];
                $search_item_id  =  $suggestions['SEARCH_ITEM_ID'];
                $item_key = $search_item . '_' . $search_item_id;

                if(!isset($seen_ids[$item_key])){
                    $new_item = New Items_Suggestions();
                    $new_item->type = $search_item;

                    $new_search_suggestions = New Search_Type();

                    if($search_item == 'PLACE'){
                        $place = New Place();
                        $new_item->item = $place->get_place($user_id, (int)$search_item_id);
                    }
                    else if($search_item == 'USER'){
                        $user = New User();
                        $new_item->item = $user->get_user($user_id, (int)$search_item_id);
                        // $new_item->item = $new_search_suggestions->search_user_type(0, $user_id, $search_item_id);
                    }
                    else if($search_item == 'TAG'){
                        $tag = New Tag();
                        $new_item->item = $tag->get_tag($user_id, (int)$search_item_id);
                    }

                    if($new_item->item != null){
                        $all_items[] = $new_item;
                        $seen_ids[$item_key] = true;
                    }
                }
            }
        }

        // 2. If we have less than 10, add trending suggestions
        if(count($all_items) < 10){
            $this->query("
                SELECT 
                    SEARCH_ITEM, 
                    SEARCH_ITEM_ID, 
                    COUNT(*) as SEARCH_COUNT,
                    COUNT(DISTINCT USER) as UNIQUE_USERS
                FROM Search_History 
                WHERE USER != ? 
                    AND ACTIVE = '0'
                    AND DATE >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY SEARCH_ITEM_ID, SEARCH_ITEM
                HAVING UNIQUE_USERS >= 3
                ORDER BY SEARCH_COUNT DESC, UNIQUE_USERS DESC
                LIMIT 5
            ");
            $this->bind(1, $user_id);
            $row_trending = $this->result();

            if($this->count() > 0){
                foreach($row_trending as $trending){
                    if(count($all_items) >= 10) break;
                    
                    $search_item     =  $trending['SEARCH_ITEM'];
                    $search_item_id  =  $trending['SEARCH_ITEM_ID'];
                    $item_key = $search_item . '_' . $search_item_id;

                    if(!isset($seen_ids[$item_key])){
                        $new_item = New Items_Suggestions();
                        $new_item->type = $search_item;

                        $new_search_suggestions = New Search_Type();

                        if($search_item == 'PLACE'){
                            $place = New Place();
                            $new_item->item = $place->get_place($user_id, (int)$search_item_id);
                        }
                        else if($search_item == 'USER'){
                            $user = New User();
                            $new_item->item = $user->get_user($user_id, (int)$search_item_id);
                            // $new_item->item = $new_search_suggestions->search_user_type(0, $user_id, $search_item_id);
                        }
                        else if($search_item == 'TAG'){
                            $tag = New Tag();
                            $new_item->item = $tag->get_tag($user_id, (int)$search_item_id);
                        }

                        if($new_item->item != null){
                            $all_items[] = $new_item;
                            $seen_ids[$item_key] = true;
                        }
                    }
                }
            }
        }

        // 3. If still less than 10, add similar users' suggestions
        if(count($all_items) < 10){
            $this->query("
                SELECT 
                    sh2.SEARCH_ITEM, 
                    sh2.SEARCH_ITEM_ID,
                    COUNT(*) as RELEVANCE
                FROM Search_History sh1
                INNER JOIN Search_History sh2 
                    ON sh1.SEARCH_ITEM_ID = sh2.SEARCH_ITEM_ID 
                    AND sh1.USER != sh2.USER
                WHERE sh1.USER = ?
                    AND sh2.USER != ?
                    AND sh2.ACTIVE = '0'
                    AND sh2.SEARCH_ITEM_ID NOT IN (
                        SELECT SEARCH_ITEM_ID 
                        FROM Search_History 
                        WHERE USER = ? AND ACTIVE = '0'
                    )
                GROUP BY sh2.SEARCH_ITEM_ID, sh2.SEARCH_ITEM
                ORDER BY RELEVANCE DESC, MAX(sh2.ID) DESC
                LIMIT 5
            ");
            $this->bind(1, $user_id);
            $this->bind(2, $user_id);
            $this->bind(3, $user_id);
            $row_similar = $this->result();

            if($this->count() > 0){
                foreach($row_similar as $similar){
                    if(count($all_items) >= 10) break;
                    
                    $search_item     =  $similar['SEARCH_ITEM'];
                    $search_item_id  =  $similar['SEARCH_ITEM_ID'];
                    $item_key = $search_item . '_' . $search_item_id;

                    if(!isset($seen_ids[$item_key])){
                        $new_item = New Items_Suggestions();
                        $new_item->type = $search_item;

                        $new_search_suggestions = New Search_Type();

                        if($search_item == 'PLACE'){
                            $place = New Place();
                            $new_item->item = $place->get_place($user_id, $search_item_id);
                        }
                        else if($search_item == 'USER'){
                            $user = New User();
                            $new_item->item = $user->get_user($user_id, (int)$search_item_id);
                        }
                        else if($search_item == 'TAG'){
                            $tag = New Tag();
                            $new_item->item = $tag->get_tag($user_id, (int)$search_item_id);
                        }

                        if($new_item->item != null){
                            $all_items[] = $new_item;
                            $seen_ids[$item_key] = true;
                        }
                    }
                }
            }
        }

        $new_search->items = $all_items;
        $new_search->count = count($all_items);

        $this->closeConnection();
        return $new_search;
    }

}




