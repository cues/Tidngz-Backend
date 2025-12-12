<?php

Class TagData extends Db{
    public function all_tags($tag){

        $ids = array();

        $this->query("SELECT * FROM Article_Tags WHERE TAG = ?");
        $this->bind(1,$tag);

        if($this->count() > 0){
          $array_tags = $this->result();
          foreach($array_tags as $tag){
            array_push($ids, (int)$tag['ARTICLE']);
          }
        }

        $ids = implode(',',$ids);
        $ids = !$ids ? 0 : $ids;

          return $ids;

    }
}