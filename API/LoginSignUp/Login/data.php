<?php

class LoginData{
    public $error;
    public $success;
    public $user;
}




class Login extends Db{
    public function get_login($username, $password, $ip){

        $newUser        =    New LoginData();
        $user           =    New Users();

        $username = strtolower($username);
        $password = md5($password);


        $this->query("SELECT * FROM users WHERE USERNAME = ? AND PASSWORD = ? ");
        $this->bind(1,$username);
        $this->bind(2,$password);

        if($this->count() == 1){
            $newUser->error = false;
            $newUser->success = true;
            $this_user = $this->single();
    
            $newUser->user = $user->get_user($this_user);

            $this->query("UPDATE users SET IP = ? WHERE USERNAME = ?");
            $this->bind(1,$ip);
            $this->bind(2,$username);
            $this->execute();

        }else{
            $newUser->error = true;
            $newUser->success = false;
        }
      

        $this->closeConnection();

        return $newUser;


    }
}






class ReLogin extends Db{
    public function get_reLogin($token, $ip){

        $newUser        =    New LoginData();
        $user           =    New Users();

        $this->query("SELECT * FROM users WHERE TOKEN = ? ");
        $this->bind(1,$token);

        if($this->count() == 1){
            $newUser->error = false;
            $newUser->success = true;
            $this_user = $this->single();
          
            $newUser->user = $user->get_user($this_user);
            
            $this->query("UPDATE users SET IP = ? WHERE TOKEN = ?");
            $this->bind(1,$ip);
            $this->bind(2,$token);
            $this->execute();
        }else{
            $newUser->error = true;
            $newUser->success = false;
        }
      

        $this->closeConnection();

        return $newUser;


    }
}



