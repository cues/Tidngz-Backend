<?php
// Read DB config from environment (Cloud Run) with local defaults for XAMPP
$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_NAME') ?: 'Tidngz';
$dbUser = getenv('DB_USER') ?: 'Erroll';
$dbPass = getenv('DB_PASS') ?: 'Cues@1707';

// If Cloud Run has Cloud SQL instance attached, prefer unix socket.
// Set INSTANCE_CONNECTION_NAME=tidngz-221511:europe-west2:tidngz1707 in Cloud Run.
$instanceConnectionName = getenv('INSTANCE_CONNECTION_NAME') ?: '';
$cloudSqlSocket = $instanceConnectionName ? (getenv('DB_SOCKET') ?: ("/cloudsql/".$instanceConnectionName)) : '';

// mysqli connection:
// - Cloud SQL connector: use unix socket
// - local/dev: use TCP host/port
if ($cloudSqlSocket) {
  // When using unix_socket, pass port 0 to avoid PHP treating it as TCP.
  $con = mysqli_connect('localhost', $dbUser, $dbPass, $dbName, 0, $cloudSqlSocket);
} else {
  $con = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName, (int)$dbPort);
}

$appEnv = getenv('APP_ENV') ?: 'prod';
$isLocal = in_array(strtolower($appEnv), ['local', 'dev', 'development'], true);

if ($isLocal) {
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
} else {
	ini_set('display_errors', '0');
}

if (mysqli_connect_errno()) {
	$msg = "mysqli connect failed: " . mysqli_connect_error();

	// Helpful context (no passwords)
	error_log($msg);
	error_log(sprintf(
		"DB context: env=%s host=%s port=%s db=%s user=%s socket=%s",
		$appEnv,
		$dbHost,
		$dbPort,
		$dbName,
		$dbUser,
		$cloudSqlSocket ?: '(none)'
	));

	http_response_code(500);
	exit($isLocal ? $msg : "Database connection failed");
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
        // Prefer env vars in Cloud Run; fallback to existing defaults for local dev
        $name = getenv('DB_NAME') ?: $this->dbName;
        $user = getenv('DB_USER') ?: $this->dbUsername;
        $pass = getenv('DB_PASS') ?: $this->dbPassword;

        $instanceConnectionName = getenv('INSTANCE_CONNECTION_NAME') ?: '';
        $socket = $instanceConnectionName ? (getenv('DB_SOCKET') ?: ("/cloudsql/".$instanceConnectionName)) : '';

        if ($socket) {
            // Cloud SQL connector path (Cloud Run)
            $dsn = 'mysql:unix_socket='.$socket.';dbname='.$name.';charset=utf8mb4';
        } else {
            // TCP fallback (local dev)
            $host = getenv('DB_HOST') ?: $this->dbHost;
            $port = getenv('DB_PORT') ?: '3306';
            $dsn = 'mysql:host='.$host.';port='.$port.';dbname='.$name.';charset=utf8mb4';
        }

        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'"
        );

        try {
            $this->dbh = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            $msg = "PDO connection failed: " . $e->getMessage();
            error_log($msg);
            error_log("PDO DSN: " . $dsn);

            $appEnv = getenv('APP_ENV') ?: 'prod';
            $isLocal = in_array(strtolower($appEnv), ['local', 'dev', 'development'], true);

            if (!headers_sent()) {
                http_response_code(500);
            }
            exit($isLocal ? $msg : "Database connection failed");
        }
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
        // Actually release PDO resources
        $this->stmt = null;
        $this->dbh = null;
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



class APIKey {
    public static function check_key($con, $key){
        $check_key = mysqli_query($con,"SELECT * FROM Api_Keys WHERE CLIENT = '$key'");
        $row_key = mysqli_num_rows($check_key);
        if($row_key == 0){
             return false;
        } else {
             return true;
        }
    }
}

