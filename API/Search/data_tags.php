<?php

class SearchTags{
    public $count;
    public $items = array();
}

class Items_Tags {
    public $type = 'TAG';
    public $item;
}



class Search_Tag extends Db{
    public function get_search($key, $user, $search, $start, $total){

        $new_search        =    New SearchTags();

        $this->query("SELECT DISTINCT Article_Tags.TAG from Article_Tags where TAG LIKE '$search%' order by TAG LIMIT $start, $total");
        $row_tags = $this->result();

        $new_search->count = (int)$this->count();

        if($new_search->count > 0){
            $t = 0;

            foreach ($row_tags as $row_tag){

                $new_search->items[$t] = New Items_Tags();

                $this->query("SELECT * from Article_Tags where TAG = ?");
                $this->bind(1, $row_tag['TAG']);
                $single_tag = $this->single();
                
                $tag = New Tag();
                $new_search->items[$t]->item = $tag->get_tag($user, (int)$single_tag['ID']);

                // $new_search->items[$t]->item->tag      =  $row_tag['TAG'];

                // $this->query("SELECT * from Article_Tags where TAG = ?");
                // $this->bind(1, $new_search->items[$t]->item->tag);
                // $single_tag = $this->single();
                // $new_search->items[$t]->item->tag_id   =  (int)$single_tag['ID'];
                // $new_search->items[$t]->item->key      =  (int)$single_tag['ID'] . $key;
       
                $t++;

            }

        }

        return $new_search;
    }
}