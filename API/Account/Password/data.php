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

        $user_id           = $userData['user_id'];
        $password          = trim($userData['password']);
        $new_password      = trim($userData['new_password']);
        $confirm_password  = trim($userData['confirm_password']);
        $token             = $userData['token'];

        if($password == $new_password){
            $userUpdateData->error = true;
            $userUpdateData->errorType = "same_password";
            $userUpdateData->errorReason =  'Please enter a different passowrd to update';
            return $userUpdateData;
        }
      
        if($new_password != $confirm_password){
            $userUpdateData->error = true;
            $userUpdateData->errorType = "password_incorrect";
            $userUpdateData->errorReason =  'Please confirm your new password';
            return $userUpdateData;
        }


        if($new_password == '' || strlen($new_password) < 5 || !preg_match("#[0-9]+#", $new_password) || !preg_match("#[a-zA-Z]+#", $new_password) || strlen($new_password) > 20){
            $newUser->error = true;
            $newUser->errorType = "password";
            $newUser->errorReason = $new_password == '' ? 'Please enter a password' : $newUser->errorReason;
            $newUser->errorReason = strlen($new_password) < 5  ? 'Password is too short, minimum of 5 characters' : $newUser->errorReason;
            $newUser->errorReason = strlen($new_password) > 20  ? 'Password is too long, maximum of 20 characters' : $newUser->errorReason;
            $newUser->errorReason = !preg_match("#[0-9]+#", $new_password) || !preg_match("#[a-zA-Z]+#", $new_password) ? 'Please enter a combination of alphabeth and numbers allowed' : $newUser->errorReason;
            return $newUser;
        }


        $password           =   md5($password);
        $new_password       =   md5($new_password);
        $confirm_password   =   md5($confirm_password);

        $this->query("SELECT * FROM users WHERE TOKEN = ? AND ID = ?");
        $this->bind(1,$token);
        $this->bind(2,$user_id);
        $this_user = $this->single();
        $old_password  =  $this_user['PASSWORD'];
      
        if($old_password != $password){
            $userUpdateData->error = true;
            $userUpdateData->errorType = "password_mismatch";
            $userUpdateData->errorReason =  'Incorrect password';
            return $userUpdateData;
        }



        if($new_password != $password && $new_password == $confirm_password){
            $this->query("UPDATE users SET  PASSWORD = ? WHERE TOKEN = ? AND ID = ?");
            $this->bind(1,$new_password);
            $this->bind(2,$token);
            $this->bind(3,$user_id);
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