<?php

class TagData{
    public $tag_id;   
    public $name;   
}


class Tag extends Db{
    public function get_tag($user_id, $tag_id){

        $tag = New Tag();

        $this->query("SELECT * from Article_Tags where ID = ? ");
        $this->bind(1, $tag_id);
        $row_tag = $this->single();

        $tag->tag_id   =  (int)$row_tag['ID'];
        $tag->name     =  $row_tag['TAG'];

        $this->closeConnection();
        return $tag;

    }
}

