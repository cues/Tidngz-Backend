<?php

class User {
    public $user_id;     
    public $username;
    public $user_name;
    public $user_name_initial;
    public $user_sex;
    public $user_verified;
    public $user_image_oauth;
    public $user_image;
    public $user_image_2;
    public $user_image_3;
    public $user_dark_mode;
    public $user_active; 
    public $user_timezone; 
    public $display; 
    public $profile_display; 
    public $user_theme_id;          
    public $user_theme_blur_id;        
    public $user_theme_color;          
    public $user_theme_type;     
    public $user_theme_color_type; 

    public $user_posts;
    public $user_followers;
    public $user_following;  
    public $token; 
}



class UserData extends Db {
    public function get_user($this_user){

        $user           =    New User();
        $image_data     =    New ImageData(); 


        $users_name = strtolower($this_user['NAME']);

        $user->user_id                 =   (int)$this_user['ID'];
        $user->username                =   $this_user['USERNAME'];
        $user->user_name               =   ucwords($users_name);
        $user->user_name_initial       =   ucfirst($users_name[0]);
        $user->user_sex                =   (int)$this_user['SEX'];
        $user->user_verified           =   (int)$this_user['VERIFIED'];
        $user->user_image_oauth        =   $this_user['OAUTH_IMAGE'];
        $user->user_image              =   $image_data->get_user_image($this_user['IMAGE']);
        $user->user_image_2            =   $image_data->get_user_image($this_user['IMAGE_2']);
        $user->user_image_3            =   $image_data->get_user_image($this_user['IMAGE_3']);
        $user->user_active             =   $this_user['ACTIVE'] == 1 ? true : false;
        $user->user_timezone           =   $this_user['TIMEZONE'];
        $user->display                 =   (int)$this_user['ARTICLES_DISPLAY'];
        $user->profile_display         =   (int)$this_user['ARTICLES_PROFILE_DISPLAY'];
        $user->user_dark_mode          =   $this_user['DARK_MODE'] == 1 ? true : false;
        $user->user_theme_id           =   (int)$this_user['THEMES'];
        $user->user_theme_blur_id      =   (int)$this_user['THEMES_BLUR'];
        $user->user_theme_color        =   (int)$this_user['THEMES_COLOR'];
        $user->user_theme_type         =   (int)$this_user['THEME_TYPE'];
        $user->user_theme_color_type   =   (int)$this_user['THEME_COLOR_TYPE'];
        $user->token                   =   $this_user['TOKEN'];

        $this->query("SELECT * FROM Articles WHERE USER_ID = ? AND ACCEPTED='1' AND FAKE_PN='0'");
        $this->bind(1, $user->user_id);
        $user->user_posts = (int)$this->count();

        $this->query("SELECT * FROM User_Follow WHERE USER_FOLLOWING_ID = ?");
        $this->bind(1, $user->user_id);
        $user->user_followers = (int)$this->count();

        $this->query("SELECT * FROM User_Follow WHERE USER_ID = ?");
        $this->bind(1, $user->user_id);
        $user->user_following = (int)$this->count();


        return $user;
    }
}