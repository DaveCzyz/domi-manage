<?php

class User{
    protected static $db_name = "users";
    public $first_name;
    public $last_name;
    public $email;
    public $password;

    // Static function for verifing user during login
    public static function verifyUser($m, $p){
        global $db;
        $email      = $db->escape_string($m);
        $password   = $db->escape_string($p);

        $sql = "SELECT * FROM ". self::$db_name;
        $sql.= " WHERE email='".$email."' AND password='".$password."' LIMIT 1";
        $db->query($sql);
        $query = $db->query($sql);

        return $query->num_rows;
    }

}


?>