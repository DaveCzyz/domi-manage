<?php

class User{
    protected static $db_name = "users";
    public $id;
    public $firstName;
    public $lastName;
    public $email;
    public $password;
    public $active;
    public $activeCode;
    public $trans_id;
    
    public $err = [];

    // Static function for verifing user during login
    public static function verifyUser($m, $p){
        global $db;
        $email      = $db->escape_string($m);
        $password   = $db->escape_string($p);

        $sql = "SELECT * FROM ". self::$db_name;
        $sql.= " WHERE email='".$email."' AND password='".$password."' LIMIT 1";
        $query = $db->query($sql);

        return $query->num_rows;
    }

    public function validateNewUser(){
        global $db;
        $nameVal = '/[\s!#\'\"$@|\/\\*&^%{}();:\.,<>?\[\]=+_~`\-0-9]/i';
        $emailVal = '/[\s!#\'\"$|\/\\*&^%#{}();,:<>?\[\]=+_~`]/i';
        $fname = $db->escape_string($this->firstName);
        $lname = $db->escape_string($this->lastName);
        $email = $db->escape_string($this->email);
        $pass  = $db->escape_string($this->password);

        //Validate fields:
        if($fname == "" or $lname == ""){
            $this->err[] = "Wpisz swoje imię i nazwisko";
        }else if(strlen($fname) <= 2 or strlen($lname) <= 2){
            $this->err[] = "Wprowadzone imię lub nazwisko jest za krótkie";
        }else if(preg_match($nameVal, $fname) != 0 or preg_match($nameVal, $lname) != 0){
            $this->err[] = "Imię lub nazwisko zawiera niedozwolone znaki";
        }

        if($email == ""){
            $this->err[] = "Wpisz swój adres email";
        }else if(!filter_var($email, FILTER_SANITIZE_EMAIL)){
            $this->err[] = "Niepoprawny format adresu email";
        }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->err[] = "Niepoprawny format adresu email";
        }else if(preg_match($emailVal, $email) != 0){
            $this->err[] = "Adres email zawiera niedozwolone znaki";
        }

        if($pass == ""){
            $this->err[] = "Wpisz swóje hasło";
        }else if(strlen($pass) < 8){
            $this->err[] = "Podane hasło jest za krótkie";
        }

        if(empty($this->err)){
            global $db;
            $pass = $this->hashPassword($pass);
            $sql = "INSERT INTO users ";
            $sql.= " (firstName, lastName, email, password) ";
            $sql.= " VALUES ('$fname', '$lname', '$email', '$pass')";
            $db->query($sql);
            return true;

        }else{
            return false;
        }

    }

    private function hashPassword($p){
        return password_hash($p, PASSWORD_DEFAULT);
    }

    private function encryptPassword($e){

    }

    public static function compareData($a, $b){
        if($a == "" || $b == ""){
            return false;
        }
        return ($a === $b) ? true : false;
    }


    public function activeAccount(){
        global $db;
        if($this->active == "NO"){
            $sql = "UPDATE ".self::$db_name." SET active='YES' WHERE id=".$this->id."";
            $db->query($sql);
            $this->active = "YES";
            return true;
        }else{
            return false;
        }
    }


    public function updateUser(){

    }


    public function deleteUser(){

    }

}


?>