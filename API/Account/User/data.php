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

        $user_id  = $userData['user_id'];
        $name     = trim($userData['name']);
        $username = trim($userData['username']);
        $email    = trim($userData['email']);
        $place_id = $userData['place'];
        $token    = $userData['token'];




        if($name == '' || strlen($name) < 3 || !preg_match("/^[a-zA-Z ]*$/",$name) || strlen($name) > 30){
            $userUpdateData->error = true;
            $userUpdateData->errorType = "name";
            $userUpdateData->errorReason = $name == '' ? 'Please enter a name' : $userUpdateData->errorReason;
            $userUpdateData->errorReason = strlen($name)<3 ? 'Name is too short, minimum of 3 characters' :  $userUpdateData->errorReason;
            $userUpdateData->errorReason = strlen($name)>30 ? 'Name is too long, maximum of 30 characters' : $userUpdateData->errorReason;
            $userUpdateData->errorReason = !preg_match("/^[a-zA-Z ]*$/",$name) ? 'Please check you name' : $userUpdateData->errorReason;
            return $userUpdateData;
        }


        $this->query("SELECT USERNAME FROM users WHERE USERNAME = ?");
        $this->bind(1,$username);
        $row_checkUsername = $this->count();
        $this->query("SELECT USERNAME FROM users WHERE USERNAME = ? AND TOKEN = ? AND ID = ?");
        $this->bind(1,$username);
        $this->bind(2,$token);
        $this->bind(3,$user_id);
        $row_checkUsername_this_user = $this->count();
        if($username == "" || ($row_checkUsername == 1 && $row_checkUsername_this_user == 0)|| strlen($username) < 3 ||  strlen($username) > 25){
            $userUpdateData->error = true;
            $userUpdateData->errorType = "username";
            $userUpdateData->errorReason = $username == '' ? 'Please enter a username' : $userUpdateData->errorReason;
            $userUpdateData->errorReason = $row_checkUsername == 1 && $row_checkUsername_this_user == 0 ? 'Username already taken' :  $userUpdateData->errorReason;
            $userUpdateData->errorReason = strlen($username)<3 ? 'Username is too short, minimum of 3 characters' :  $userUpdateData->errorReason;
            $userUpdateData->errorReason = strlen($username)>30 ? 'Username is too long, maximum of 25 characters' : $userUpdateData->errorReason;
            return $userUpdateData;
        }
  
      
        $this->query("SELECT EMAIL FROM users WHERE EMAIL= ?");
        $this->bind(1,$email);
        $row_checkuserEmail = $this->count();
        if($email == "" || !filter_var($email, FILTER_VALIDATE_EMAIL)){
            $userUpdateData->error = true;
            $userUpdateData->errorType = "email";
            $userUpdateData->errorReason = $email == '' ? 'Please enter an email' : $userUpdateData->errorReason;
            $userUpdateData->errorReason = $row_checkuserEmail == 1 ? 'Email already in use' :  $userUpdateData->errorReason;
            $userUpdateData->errorReason = !filter_var($email, FILTER_VALIDATE_EMAIL) ? 'Please check your email' :  $userUpdateData->errorReason;
            return $userUpdateData;
        }


        $username = strtolower($username);
        $email = strtolower($email);
       
        $this->query("SELECT * FROM users WHERE TOKEN = ? AND ID = ?");
        $this->bind(1,$token);
        $this->bind(2,$user_id);
        $this_user = $this->single();
        $username_user  =  $this_user['USERNAME'];
        $name_user      =  $this_user['NAME'];
        $email_user     =  $this_user['EMAIL'];
        $place_id_user  =  $this_user['PLACE_ID'];



        $this->query("SELECT * FROM Places WHERE ID = ?");
        $this->bind(1,$place_id);
        $this_place= $this->single();
        $place = $this_place['PLACE'];


        if($name_user != $name || $username_user != $username || $email_user != $email || $place_id_user != $place_id){

            $this->query("UPDATE users SET  NAME = ?, USERNAME = ? , EMAIL = ? , PLACE = ? , PLACE_ID = ? WHERE TOKEN = ? AND ID = ?");
            $this->bind(1,$name);
            $this->bind(2,$username);
            $this->bind(3,$email);
            $this->bind(4,$place);
            $this->bind(5,$place_id);
            $this->bind(6,$token);
            $this->bind(7,$user_id);
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