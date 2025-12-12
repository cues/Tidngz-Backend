<?php


class SignUpData{
    public $error;
    public $errorType;
    public $errorReason;
    public $score;
    public $success;
    public $user;
}




class SignUp extends Db {
    public function insertUser($userData = array()){
        global $recaptcha_secretKey;
        global $date;

        $newUser = New SignUpData();
        $newUser->error = false;
        $newUser->success = false;

        $userData = filter_var_array($userData, FILTER_SANITIZE_STRING);

        $type = $userData['type'];
        $name = trim($userData['name']);
        $email = trim($userData['email']);
        $username = trim($userData['username']);
        $password = trim($userData['password']);
        $gender = trim($userData['gender']);
        $timezone = $userData['timezone'];
        $captcha = $userData['captcha'];
        $ip = $userData['ip'];


        if($name == '' || strlen($name) < 3 || !preg_match("/^[a-zA-Z ]*$/",$name) || strlen($name) > 30){
            $newUser->error = true;
            $newUser->errorType = "name";
            $newUser->errorReason = $name == '' ? 'Please enter an name' : $newUser->errorReason;
            $newUser->errorReason = strlen($name)<3 ? 'Name is too short, minimum of 3 characters' :  $newUser->errorReason;
            $newUser->errorReason = strlen($name)>30 ? 'Name is too long, maximum of 30 characters' : $newUser->errorReason;
            $newUser->errorReason = !preg_match("/^[a-zA-Z ]*$/",$name) ? 'Please check you name' : $newUser->errorReason;
            return $newUser;
        }
  
        $this->query("SELECT USERNAME FROM users WHERE USERNAME= ?");
        $this->bind(1,$username);
        $row_checkUsername = $this->count();
        if($username == "" || $row_checkUsername == 1 || strlen($username) < 3 ||  strlen($username) > 25){
            $newUser->error = true;
            $newUser->errorType = "username";
            $newUser->errorReason = $username == '' ? 'Please enter a username' : $newUser->errorReason;
            $newUser->errorReason = $row_checkUsername == 1 ? 'Username already taken' :  $newUser->errorReason;
            $newUser->errorReason = strlen($username)<3 ? 'Username is too short, minimum of 3 characters' :  $newUser->errorReason;
            $newUser->errorReason = strlen($username)>30 ? 'Username is too long, maximum of 25 characters' : $newUser->errorReason;
            return $newUser;
        }
  
      
        $this->query("SELECT EMAIL FROM users WHERE EMAIL= ?");
        $this->bind(1,$email);
        $row_checkuserEmail = $this->count();
        if($email == "" || $row_checkuserEmail == 1 || !filter_var($email, FILTER_VALIDATE_EMAIL)){
            $newUser->error = true;
            $newUser->errorType = "email";
            $newUser->errorReason = $email == '' ? 'Please enter an email' : $newUser->errorReason;
            $newUser->errorReason = $row_checkuserEmail == 1 ? 'Email already in use' :  $newUser->errorReason;
            $newUser->errorReason = !filter_var($email, FILTER_VALIDATE_EMAIL) ? 'Please check your email' :  $newUser->errorReason;
            return $newUser;
        }


        if($password == '' || strlen($password) < 5 || !preg_match("#[0-9]+#", $password) || !preg_match("#[a-zA-Z]+#", $password) || strlen($password) > 20){
            $newUser->error = true;
            $newUser->errorType = "password";
            $newUser->errorReason = $password == '' ? 'Please enter a password' : $newUser->errorReason;
            $newUser->errorReason = strlen($password) < 5  ? 'Password is too short, minimum of 5 characters' : $newUser->errorReason;
            $newUser->errorReason = strlen($password) > 20  ? 'Password is too long, maximum of 20 characters' : $newUser->errorReason;
            $newUser->errorReason = !preg_match("#[0-9]+#", $password) || !preg_match("#[a-zA-Z]+#", $password) ? 'Only alphabeth and numbers allowed' : $newUser->errorReason;
            return $newUser;
        }

        if($gender == ''){
            $newUser->error = true;
            $newUser->errorType = "gender";
            $newUser->errorReason = 'Please select your gender';
        }
  
        $username = strtolower($username);
        $email = strtolower($email);
        $password = md5($password);


            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$recaptcha_secretKey.'&response='.$captcha);
            $responseData = json_decode($verifyResponse);

            $newUser->score = $responseData->score;
            // $responseData->score > 0.2 && 
            if($responseData->action == 'signup'){

                $validation = md5($username . microtime());
                $token = bin2hex(random_bytes(64) . $validation . $username . microtime());

                  $this->query("INSERT INTO users (IP, TOKEN, NAME, USERNAME, EMAIL, PASSWORD, SEX, DATE, ACTIVE, VALIDATION_CODE, TIMEZONE) values (?,?,?,?,?,?,?,?,?,?,?)");
                  $this->bind(1,$ip);
                  $this->bind(2,$token);
                  $this->bind(3,$name);
                  $this->bind(4,$username);
                  $this->bind(5,$email);
                  $this->bind(6,$password);
                  $this->bind(7,$gender);
                  $this->bind(8,$date);
                  $this->bind(9,0);
                  $this->bind(10,$validation);
                  $this->bind(11,$timezone);
                  $add = $this->execute();
        

                  $this->query("SELECT * FROM users WHERE USERNAME = ? ");
                  $this->bind(1,$username);
                  $this_user = $this->single();

                  $newUser->success = true;
                  
                  $user           =  New Users();
                  $newUser->user  =  $user->get_user($this_user);


                  $this->query("INSERT INTO User_Follow (USER_ID,USER_FOLLOWING_ID,DATE) VALUES (?,?,?)");
                  $this->bind(1,$user_id);
                  $this->bind(2,1);
                  $this->bind(3,$date);
                  $this->execute();
                  $this->closeConnection();



                  
            

                    $to = "$email"; 
                    $subject = "Activate your Tidngz account";
                    $message = "
                    <html>
                    <head>
                    </head>
                    <body>
                    <div style='font-family:  Palatino Linotype, Book Antiqua, Palatino, serif;width:100%; height:70px; background-color:rgba(255,255,255,.1); text-align:center;line-height:70px; vertical-align: middle; color:rgba(15,101,141,.9); font-size:23px;'>Tindgz</div>
                    <p style='font-family:  Palatino Linotype, Book Antiqua, Palatino, serif;font-size:16px;color:rgb(23,23,23,.8); width:100%; text-align:center'>$name</p>
                        <p style=' font-family:   Palatino Linotype, Book Antiqua, Palatino, serif;font-size:16px;color:rgb(23,23,23,.8);  width:100%; text-align:center'>Welcome to Tidngz, we are happy you decided to use our service for your daily news feed.</p>
                        <p style=' font-family:   Palatino Linotype, Book Antiqua, Palatino, serif;font-size:16px;color:rgb(23,23,23,.8);  width:100%; text-align:center'>Click on the link below to activate your account</p>
                        <div style='width:100%; text-align:center;'>
                        <div style='display:inline-block; background-color:rgba(15,101,141,.8);height:35px;
                        width:140px; border-radius:5px; text-align:center;line-height:36px; vertical-align: middle;'>
                        <a style='font-family:   Palatino Linotype, Book Antiqua, Palatino, serif;text-decoration: none;color: rgba(255,255,255,1);font-size:18px' href='https://www.wedngz.com/Tidngz/API/LoginSignUp/Activate/activate.php?email=$email&code=$validation'>Click here</a>
                        </div>
                        </div>
            
                    </div>
            
                        <p style='font-family:   Palatino Linotype, Book Antiqua, Palatino, serif;font-size:16px;color:rgb(23,23,23,.8); width:100%; text-align:center'>Tidngz
                        <span style='font-size:19px;line-height:30px;vertical-align:middle'>&#169;</span> 2019</p>
                    </body>
                    </html>
                    ";
            
                    $headers[] = 'MIME-Version: 1.0';
                    $headers[] = 'Content-type: text/html; charset=iso-8859-1';
                    $headers[] = 'From: Tidngz <registration@tidngz.com>';
            
                    mail($to, $subject, $message, implode("\r\n", $headers));
                





   

            }else{
                $newUser->error = true;
                $newUser->errorType = "reject";
                $newUser->errorReason = "sorry can't verify you at the moment, please try again";
            }



            

        return $newUser;


    }
}