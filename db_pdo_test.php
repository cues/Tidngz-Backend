<?php
//$con = mysqli_connect("localhost","erroll","erroll","Tidngz");
//$con_c = mysqli_connect("localhost","erroll","erroll","Tidngz");

$appEnv = getenv('APP_ENV') ?: 'prod';
$isLocal = in_array(strtolower($appEnv), ['local', 'dev', 'development'], true);

// Read DB config from environment (Cloud Run) with local defaults for XAMPP
$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_NAME') ?: 'Tidngz';
$dbUser = getenv('DB_USER') ?: 'Erroll';
$dbPass = getenv('DB_PASS') ?: 'Cues@1707';

// Redis Config
$redisHost = getenv('REDIS_HOST') ?: 'localhost';
$redisPort = getenv('REDIS_PORT') ?: '6379';

// Diagnostic: Warn if env vars are missing in Cloud Run (prevents silent fallback to local creds)
if (!$isLocal && getenv('DB_USER') === false) {
    error_log("WARNING: DB_USER environment variable is not set. Using local fallback 'Erroll', which will likely fail in Cloud Run.");
}

// If Cloud Run has Cloud SQL instance attached, prefer unix socket.
$instanceConnectionName = getenv('INSTANCE_CONNECTION_NAME') ?: '';
$cloudSqlSocket = $instanceConnectionName ? (getenv('DB_SOCKET') ?: ("/cloudsql/".$instanceConnectionName)) : '';

// Debug logging to help identify 500 errors
error_log("DB Config: Host=$dbHost, Socket=" . ($cloudSqlSocket ?: 'none') . ", User=$dbUser, DB=$dbName");

if ($cloudSqlSocket) {
    // Cloud Run recommended: host=null, port=null, socket=/cloudsql/...
    $con = mysqli_connect(null, $dbUser, $dbPass, $dbName, null, $cloudSqlSocket);
} else {
    $con = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName, (int)$dbPort);
}

if (mysqli_connect_errno()) {
    $msg = "mysqli connect failed: " . mysqli_connect_error();
    error_log($msg);
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
// $google_api_key = "AIzaSyByKeN4udg5wvJwfTF6HEIgzCvTKPoW6ZY";
$google_api_key = "AIzaSyCc0Xcf1L52LM2-a7SspVXDQyH1nHrAScY";


// Define user class
class Db {
    protected $dbHost     = "localhost";
    protected $dbUsername = "Erroll";
    protected $dbPassword = "Cues@1707";
    protected $dbName     = "Tidngz";
    protected $userTbl    = 'users';
    protected $dbh;
    protected $stmt;

    // Redis Properties
    protected $redis = null;
    protected $cacheActive = false;
    protected $cacheTTL = 0;
    protected $params = []; // Store params to generate cache key
    protected $queryStr = '';
    protected $rowCount = 0; // Store row count for cached results
    


    public function __construct(){
        // Ensure we use utf8mb4 so 4-byte unicode characters (emoji, some accented letters) are stored correctly
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
            // Ensure connection uses utf8mb4 character set and a unicode collation
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'"
        );

        try {
            $this->dbh = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            $msg = "PDO connection failed: " . $e->getMessage();
            error_log($msg);

            if (!headers_sent()) {
                http_response_code(500);
            }

            $appEnv = getenv('APP_ENV') ?: 'prod';
            $isLocal = in_array(strtolower($appEnv), ['local', 'dev', 'development'], true);

            exit($isLocal ? $msg : "Database connection failed");
        }

        // Initialize Redis
        if (class_exists('Redis')) {
            try {
                $this->redis = new Redis();
                $redisHost = getenv('REDIS_HOST') ?: 'localhost';
                $redisPort = getenv('REDIS_PORT') ?: 6379;
                // Silence connection errors in dev if Redis isn't running
                @$this->redis->connect($redisHost, (int)$redisPort);
            } catch (Exception $e) {
                $this->redis = null;
                error_log("Redis connection failed: " . $e->getMessage());
            }
        }
    }

    // Enable caching for the next query
    // Usage: $db->cache(60)->query("SELECT...");
    public function cache($ttl = 300) {
        if ($this->redis && $this->redis->isConnected()) {
            $this->cacheActive = true;
            $this->cacheTTL = $ttl;
        }
        return $this;
    }

    public function query($query){
        $this->queryStr = $query;
        $this->params = []; // Reset params
        $this->rowCount = 0;
        $this->stmt =  $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null){
        // Store param for cache key generation
        $this->params[$param] = $value;

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
        // Reset cache settings for writes/updates
        $this->cacheActive = false; 
        return $this->stmt->execute();
     }

     public function result(){
        if ($this->cacheActive) {
            $key = $this->generateCacheKey();
            $cached = $this->redis->get($key);
            
            if ($cached !== false) {
                $data = json_decode($cached, true);
                $this->rowCount = count($data);
                $this->resetCache();
                return $data;
            }
        }

        $this->execute();
        $data = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->rowCount = $this->stmt->rowCount();

        if ($this->cacheActive) {
            $this->redis->setex($key, $this->cacheTTL, json_encode($data));
            $this->resetCache();
        }

        return $data;
     }


     public function single(){
        if ($this->cacheActive) {
            $key = $this->generateCacheKey();
            $cached = $this->redis->get($key);
            
            if ($cached !== false) {
                $data = json_decode($cached, true);
                // If data is empty/false, count is 0, else 1
                $this->rowCount = $data ? 1 : 0;
                $this->resetCache();
                return $data;
            }
        }

        $this->execute();
        $data = $this->stmt->fetch(PDO::FETCH_ASSOC);
        $this->rowCount = $this->stmt->rowCount();

        if ($this->cacheActive) {
            $this->redis->setex($key, $this->cacheTTL, json_encode($data));
            $this->resetCache();
        }

        return $data;
    }

    
    public function count(){
        // If we served from cache, return the stored count
        if ($this->rowCount > 0 || $this->stmt === null) {
            return $this->rowCount;
        }
        
        $this->execute();
        return $this->stmt->rowCount();
    }

    public function closeConnection() {
        $this->stmt = null;
        $this->dbh = null;
        $this->redis = null;
    }

    private function generateCacheKey() {
        // Create a unique hash based on the query and bound parameters
        return 'sql:' . md5($this->queryStr . serialize($this->params));
    }

    private function resetCache() {
        $this->cacheActive = false;
        $this->cacheTTL = 0;
    }
    
}
// ...existing code...