<?php
// Database settings
define("DB_HOST", "localhost");
define("DB_USER","root");
define("DB_PASS", "");
define("DB_NAME", "trans");

// Database connection
class DatabaseConnection{

    public $con;
    private $host;
    private $user;
    private $pass;
    private $name;

    public function __construct($host, $user, $pass, $name){
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->name = $name;

        $this->getConnection();
    }

    public function getConnection(){
        try{
            $this->con = new mysqli($this->host, $this->user, $this->pass, $this->name);
        }catch(Exception $e){
            echo 'Database connection error: ' . $e->getMessage();
        }
    }

    public function query($sql){
        try{
            return $this->con->query($sql);
        }catch(Exception $e){
            die("Query failed");
        }
    }

    public function escape_string($string){
        return $this->con->real_escape_string($string);
    }

    public function the_last_id(){
        return mysqli_insert_id($this->con);
    }
}

// Open connection to database
$db = new DatabaseConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);


?>