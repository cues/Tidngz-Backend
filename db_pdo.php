<?php
//$con = mysqli_connect("localhost","erroll","erroll","Tidngz");
//$con_c = mysqli_connect("localhost","erroll","erroll","Tidngz");

$con = mysqli_connect("localhost","Erroll","Cues@1707","Tidngz");
if (mysqli_connect_errno())
{
  echo "Failed to connect to MYSQL:" .mysqli_connect_error();
}


date_default_timezone_set("GMT");
$date = date('Y-m-d H:i:s');
$date_today = date('Y-m-d');

// reCaptcha 
// $recaptcha_secretKey = "6LdIbHoUAAAAAC2BP2xEA1BdrUN_k7c2LU-cg-cY";
$recaptcha_secretKey = "6LcKjSUsAAAAANit5CwEABXg1Uon4d3DSEyVE_oo";

// Google API Key
$google_api_key = "AIzaSyByKeN4udg5wvJwfTF6HEIgzCvTKPoW6ZY";

// Define user class
class Db {
	protected $dbHost     = "localhost";
    protected $dbUsername = "Erroll";
    protected $dbPassword = "Cues@1707";
    protected $dbName     = "Tidngz";
    protected $userTbl    = 'users';
    protected $dbh;
    protected $stmt;
    


    public function __construct(){
        $this->dbh = new PDO('mysql:host='.$this->dbHost.';dbname='.$this->dbName,$this->dbUsername,$this->dbPassword);
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function query($query){
        $this->stmt =  $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null){
        if(is_null($type)){
            switch(true){
                case is_int($value) :
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value) :
                    $type = PDO::PARAM_INT;
                    break;
                case is_null($value) :
                    $type = PDO::PARAM_INT;
                    break;
                default :
                     $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute(){
        return $this->stmt->execute();
     }

     public function result(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
     }


     public function single(){
       $this->execute();
       return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    public function count(){
        $this->execute();
        return $this->stmt->rowCount();
      }

    public function closeConnection() {
        $stmt = null;
        $dbh = null;
    }
	
}




class Sanitize {
    private static $check_sanitize           =    "/(DROP TABLE|TRUNCATE TABLE|DROP DATABASE|DELETE FROM|mysqli_query|[\'\"^!£$%&*()}{#~?;|=¬])/i";
    private static $check_sanitize_tag       =    "/(DROP TABLE|TRUNCATE TABLE|DROP DATABASE|DELETE FROM|mysqli_query|[\'\"^])/i";
    private static $check_sanitize_text      =    "/(DROP TABLE|TRUNCATE TABLE|DROP DATABASE|DELETE FROM|mysqli_query)/i";
    private static $check_sanitize_username  =    "/(DROP TABLE|TRUNCATE TABLE|DROP DATABASE|DELETE FROM|mysqli_query|Home|Add|Place|PLace_Landmark|Article|Tag|World_Map|Messenger|Bookmarks|Theme|Accout|Terms|Privacy|Help|About|Ads|Contact|Settings|Login|Logout|_News[\'\",;|+¬])/i";
    
    private static $sanitize;
    private static $check;
   

    public static function check_sanitize( $newdata , $type ){
        self::$sanitize = false ;
        self::$check = '';

        self::$check    =   $type == 1 ?    self::$check_sanitize           :   self::$check;
        self::$check    =   $type == 2 ?    self::$check_sanitize_tag       :   self::$check;
        self::$check    =   $type == 3 ?    self::$check_sanitize_text      :   self::$check;
        self::$check    =   $type == 4 ?    self::$check_sanitize_username  :   self::$check;

        foreach($newdata as $data){
            if(preg_match(self::$check, $data)){
                self::$sanitize = true;
            }
        }
    
       return self::$sanitize;
    }

}

