<?php

class Themes extends Db {
    public function dark_theme($user_id, $theme){
        // return $theme;

        $dark = New stdClass;

        $this->query("UPDATE users SET DARK_MODE = ? WHERE ID = ?");
        $this->bind(1, $theme);
        $this->bind(2, $user_id);
        $this->execute();

        $this->query("SELECT * FROM users WHERE ID = ?");
        $this->bind(1, $user_id);
        $this_user       =  $this->single();
        $dark->username        =  $this_user['USERNAME'];
        $dark->user_dark_mode  =  $this_user['DARK_MODE'] == 2 ? true : false;

        return $dark;
    }
}