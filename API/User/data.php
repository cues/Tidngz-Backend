<?php

class UserData_ {
    public $id;     
    public $username;
    public $email;
    public $name;
    public $name_initial;
    public $profile_type;
    public $sex;
    public $verified;
    public $image_oauth;
    public $avatar;
    public $image;
    public $image_2;
    public $image_3;
    public $active; 
    public $timezone; 
    public $place_id; 
    public $place; 
    public $bio; 
    public $website; 
    public $social;
    // public $display; 
    // public $profile_display; 
    public $dark_mode;
    // public $theme_id;          
    // public $theme_blur_id;        
    // public $theme_color;          
    // public $theme_type;     
    // public $theme_color_type; 
    public $posts_count;
    public $followers_count;
    public $following_count; 
    public $following; 
    // public $token; 
}

class Social {
    public $facebook; 
    public $instagram; 
    public $twitter; 
    public $youtube; 
}



class User extends Db {
    // Accept a user ID, fetch the DB row, and return a UserData object
    public function get_user($user_id, $other_user_id){

        $this->query("SELECT * from users where ID = ?");
        $this->bind(1, $other_user_id);
        $row_user = $this->single();

        if($this->count() == 0 || empty($row_user)){
            return '';
        }

        $user           =    New UserData_();
        $image_data     =    New ImageData(); 
        $user->social   =    New Social();

        $users_name = strtolower($row_user['NAME']);

        $user->id                 =   (int)$row_user['ID'];
        $user->username           =   $row_user['USERNAME'];
        $user->email              =   $row_user['EMAIL'];
        $user->name               =   ucwords($users_name);
        $user->name_initial       =   ucfirst($users_name[0]);
        $user->profile_type       =   (int)$row_user['PROFILE_TYPE'];
        $user->sex                =   (int)$row_user['SEX'];
        $user->verified           =   (int)$row_user['VERIFIED'];
        $user->image_oauth        =   $row_user['OAUTH_IMAGE'];
        $user->avatar             =   $image_data->get_user_image($row_user['IMAGE']);
        $user->image              =   $image_data->get_user_image($row_user['IMAGE']);
        $user->image_2            =   $image_data->get_user_image($row_user['IMAGE_2']);
        $user->image_3            =   $image_data->get_user_image($row_user['IMAGE_3']);
        $user->active             =   $row_user['ACTIVE'] == 1 ? true : false;
        $user->timezone           =   $row_user['TIMEZONE'];
        $user->place_id           =   $row_user['PLACE_ID'];
        $user->place              =   $row_user['PLACE'];
        $user->bio                =   $row_user['BIO'];
        $user->website            =   $row_user['WEBSITE'];
        $user->social->facebook           =   $row_user['FACEBOOK'];
        $user->social->instagram          =   $row_user['INSTAGRAM'];
        $user->social->twitter            =   $row_user['TWITTER'];
        $user->social->youtube            =   $row_user['YOUTUBE'];
        // $user->display            =   (int)$row_user['ARTICLES_DISPLAY'];
        // $user->profile_display    =   (int)$row_user['ARTICLES_PROFILE_DISPLAY'];
        // $user->dark_mode          =   $row_user['DARK_MODE'] == 2 ? true : false;
        // $user->theme_id           =   (int)$row_user['THEMES'];
        // $user->theme_blur_id      =   (int)$row_user['THEMES_BLUR'];
        // $user->theme_color        =   (int)$row_user['THEMES_COLOR'];
        // $user->theme_type         =   (int)$row_user['THEME_TYPE'];
        // $user->theme_color_type   =   (int)$row_user['THEME_COLOR_TYPE'];
        // $user->token              =   $row_user['TOKEN'];

        $this->query("SELECT * FROM Articles WHERE USER_ID = ? AND ACCEPTED='1' AND FAKE_PN='0'");
        $this->bind(1, $user->id);
        $user->posts_count = (int)$this->count();

        $this->query("SELECT * FROM User_Following WHERE OTHER_USER_ID = ?");
        $this->bind(1, $user->id);
        $user->followers_count = (int)$this->count();

        $this->query("SELECT * FROM User_Following WHERE USER_ID = ?");
        $this->bind(1, $user->id);
        $user->following_count = (int)$this->count();

        $this->query("SELECT * FROM User_Following WHERE USER_ID = ? AND OTHER_USER_ID = ?");
        $this->bind(1, $user_id);
        $this->bind(2, $other_user_id);
        $user->following = $this->count() == 1 ? true : false;



        $this->closeConnection();
        return $user;
    }
}








// class UserLight_Data {
//     public $id;     
//     public $username;
//     public $email;
//     public $name;
//     public $name_initial;
//     public $sex;
//     public $image;
//     public $image_2;
//     public $image_3;
//     public $verified;
//     public $following;
// }




// class UserLight extends Db {

//     public function user_light($user_id, $user_2){

//         $image_data  =   New ImageData();
        
//         $userLight_Data = New UserLight_Data();
        
//         $this->query("SELECT * from users where ID = ?");
//         $this->bind(1, $user_2);
//         $row_user = $this->single();

//         $userLight_Data->id               = (int)$row_user['ID'];
//         $userLight_Data->username              = strtolower($row_user['USERNAME']);
//         $userLight_Data->email            = $row_user['EMAIL'];
//         $user_name_2                           = strtolower($row_user['NAME']);
//         $userLight_Data->name             = ucwords($user_name_2);
//         $userLight_Data->name_initial     = ucfirst($user_name_2[0]);     
//         $userLight_Data->sex              = (int)$row_user['SEX'];
//         $userLight_Data->image            = $image_data->get_user_image($row_user['IMAGE']);
//         $userLight_Data->image_2          = $image_data->get_user_image($row_user['IMAGE_2']);
//         $userLight_Data->image_3          = $image_data->get_user_image($row_user['IMAGE_3']);
//         $userLight_Data->verified         = (int)$row_user['VERIFIED'];


//         $this->query("SELECT * FROM User_Follow WHERE USER_ID = ? AND USER_FOLLOWING_ID = ?");
//         $this->bind(1, $user_id);
//         $this->bind(2, $user_2);

//         $userLight_Data->following = $this->count() == 1 ? true : false;

//         return $userLight_Data;

//     }
// }
