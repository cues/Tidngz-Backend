<?php

Class BookmarkData extends Db{
    public function all_bookmarks($user_id){

        $ids = array();

        $this->query("SELECT * FROM Article_Bookmarks WHERE USER = ?");
        $this->bind(1,$user_id);

        if($this->count() > 0){
          $array_bookmarks = $this->result();
          foreach($array_bookmarks as $bookmark){
            array_push($ids, (int)$bookmark['ARTICLE']);
          }
        }

        $ids = implode(',',$ids);
        $ids = !$ids ? 0 : $ids;

          return $ids;

    }
}