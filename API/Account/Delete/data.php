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

        $user_id = $userData['user_id'];
        $token   = $userData['token'];
        $delete  = $userData['delete'];

        $this->query("UPDATE users SET DELETE_USER = ? WHERE TOKEN = ? AND ID = ?");
        $this->bind(1,$delete);
        $this->bind(2,$token);
        $this->bind(3,$user_id);
        $this->execute();

        $userUpdateData->success = true;

        $this->closeConnection();
        return $userUpdateData;
        
    }
}