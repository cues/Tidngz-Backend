<?php


class Activate extends Db{
    public function activate_account($email, $code){

        $this->query("SELECT * FROM users WHERE EMAIL = ? AND VALIDATION_CODE = ?");
        $this->bind(1, $email);
        $this->bind(2, $code);

        if($this->count() == 1){
            $this->query("UPDATE users SET ACTIVE = ? , VALIDATION_CODE = ? WHERE EMAIL = ?");
            $this->bind(1, 1);
            $this->bind(2, 0);
            $this->bind(3, $email);
            $this->execute();
            return 1;
        }else{
            return 2;
        }
    }
}