<?php
class UpdateData{
    public $error;
    public $errorType;
    public $errorReason;
    public $success;
    public $user;
}


class UpdateUser extends Db{
    public function update_user($userData){
        
        $userUpdateData = New UpdateData();
        $userUpdateData->error = false;
        $userUpdateData->success = false;

        $userData = filter_var_array($userData, FILTER_SANITIZE_STRING);

        $user_id    =  $userData['user_id'];
        $bio        =  $userData['bio'];
        $website    =  $userData['website'];
        $facebook   =  $userData['facebook'];
        $instagram  =  $userData['instagram']; 
        $twitter    =  $userData['twitter'];
        $youtube    =  $userData['youtube'];
        $token      =  $userData['token'];


          if(strlen($bio)>150){
                $userUpdateData->error = true;
                $userUpdateData->errorType = "email";
                $userUpdateData->errorReason = "Bio is too long, maximum of 150 characters allowed";
                exit();
          }
          if ($facebook != '') {
            if (strpos($facebook, 'https://www.facebook.com/') === false) {
                $userUpdateData->error = true;
                $userUpdateData->errorType = "facebook";
                $userUpdateData->errorReason = "Please check your facebook profile link";
                exit();
            }
          }
          if ($instagram != '') {
            if (strpos($instagram, 'https://www.instagram.com/') === false) {
                $userUpdateData->error = true;
                $userUpdateData->errorType = "instagram";
                $userUpdateData->errorReason = "Please check your instagram profile link";
                exit();
            }
          }
          if ($twitter != '') {
            if (strpos($twitter, 'https://twitter.com/') === false) {
                $userUpdateData->error = true;
                $userUpdateData->errorType = "twitter";
                $userUpdateData->errorReason = "Please check your twitter profile link";
                exit();
            }
          }
          if ($youtube != '') {
            if (strpos($youtube, 'https://www.youtube.com/channel/') === false) {
                $userUpdateData->error = true;
                $userUpdateData->errorType = "youtube";
                $userUpdateData->errorReason = "Please check your youtube profile link";
                exit();
            }
          }
      

        $this->query("SELECT * FROM users WHERE TOKEN = ? AND ID = ?");
        $this->bind(1,$token);
        $this->bind(2,$user_id);
        $this_user      = $this->single();
        $bio_user       = $this_user['BIO'];
        $website_user   = $this_user['WEBSITE'];
        $facebook_user  = $this_user['FACEBOOK'];
        $instagram_user = $this_user['INSTAGRAM'];
        $twitter_user   = $this_user['TWITTER'];
        $youtube_user   = $this_user['YOUTUBE'];
    

        if($bio_user != $bio || $website_user != $website || $facebook_user != $facebook || $instagram_user != $instagram || $twitter_user != $twitter || $youtube_user != $youtube){

                $this->query("UPDATE users SET BIO = ?, WEBSITE = ?, FACEBOOK = ?, INSTAGRAM = ?, TWITTER = ?, YOUTUBE = ? WHERE TOKEN = ? AND ID = ?");
                $this->bind(1,$bio);
                $this->bind(2,$website);
                $this->bind(3,$facebook);
                $this->bind(4,$instagram);
                $this->bind(5,$twitter);
                $this->bind(6,$youtube);
                $this->bind(7,$token);
                $this->bind(8,$user_id);
                $this->execute();


                $this->query("SELECT * FROM users WHERE TOKEN = ? AND ID = ?");
                $this->bind(1,$token);
                $this->bind(2,$user_id);
                $this_user = $this->single();

                $userUpdateData->success = true;
                
                $user           =  New Users();
                $userUpdateData->user  =  $user->get_user($this_user);

        }else{ 

                $userUpdateData->error = true;
                $userUpdateData->errorType = "reject";
                $userUpdateData->errorReason = "sorry!! nothing to update";
        
        }

        $this->closeConnection();
        return $userUpdateData;

    }
}