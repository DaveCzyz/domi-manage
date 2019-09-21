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
    public function verifyUser($m, $p){
        global $db;
        global $session;
        $encrypt;
        $user_id;
        $email      = $db->escape_string($m);
        $password   = $db->escape_string($p);

        $sql = "SELECT * FROM ". self::$db_name;
        $sql.= " WHERE email='".$email."' LIMIT 1";
        $query = $db->query($sql);

        while($row = $query->fetch_array(MYSQLI_ASSOC)){
            $encrypt = $row['password'];
            $user_id = $row['id'];
        }

        if($this->encryptPassword($password, $encrypt)){
            $session->loginSession($user_id);
            return true;
        }else{
            $this->err[] = "Nieprawidłowy email lub hasło";
        }
    }

    // Function for register new users
    public function validateNewUser(){
        global $db;
        $nameVal = '/[\s!#\'\"$@|\/\\*&^%{}();:\.,<>?\[\]=+_~`\-0-9]/i';
        $emailVal = '/[\s!#\'\"$|\/\\*&^%#{}();,:<>?\[\]=+_~`]/i';
        $fname = $db->escape_string($this->firstName);
        $lname = $db->escape_string($this->lastName);
        $email = $db->escape_string($this->email);
        $pass  = $db->escape_string($this->password);

        // Generator for activation code
        $code = uniqid();

        // Validate fields - first name and last name
        if($fname == "" or $lname == ""){
            $this->err[] = "Wpisz swoje imię i nazwisko";
        }else if(strlen($fname) <= 2 or strlen($lname) <= 2){
            $this->err[] = "Wprowadzone imię lub nazwisko jest za krótkie";
        }else if(preg_match($nameVal, $fname) != 0 or preg_match($nameVal, $lname) != 0){
            $this->err[] = "Imię lub nazwisko zawiera niedozwolone znaki";
        }

        // Validate fields - email
        if($email == ""){
            $this->err[] = "Wpisz swój adres email";
        }else if(!filter_var($email, FILTER_SANITIZE_EMAIL)){
            $this->err[] = "Niepoprawny format adresu email";
        }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->err[] = "Niepoprawny format adresu email";
        }else if(preg_match($emailVal, $email) != 0){
            $this->err[] = "Adres email zawiera niedozwolone znaki";
        }

        // Validate fields - password
        if($pass == ""){
            $this->err[] = "Wpisz swóje hasło";
        }else if(strlen($pass) < 8){
            $this->err[] = "Podane hasło jest za krótkie";
        }

        // If errors not occur send query to database
        if(empty($this->err)){
            global $db;
            $pass = $this->hashPassword($pass);
            $sql = "INSERT INTO users ";
            $sql.= " (firstName, lastName, email, password, activeCode) ";
            $sql.= " VALUES ('$fname', '$lname', '$email', '$pass', '$code')";
            $db->query($sql);
            return true;

        }else{
            return false;
        }

    }

    // Hash passwords during login or register
    private function hashPassword($p){
        return password_hash($p, PASSWORD_DEFAULT);
    }

    private function encryptPassword($h, $e){
        if(password_verify($h, $e)){
            return true;
        }else{
            return false;
        }
    }

    // Static function for comparing two data
    public static function compareData($a, $b){
        if($a == "" || $b == ""){
            return false;
        }
        return ($a === $b) ? true : false;
    }

    // Function for activation user account via email link
    public function activeAccount($code = ""){
        if(empty($code)){
            return false;
        }
        global $db;
        $sql = "UPDATE ". self::$db_name ."";
        $sql.= " SET activ='YES', activeCode=''"; 
        $sql.= " WHERE activeCode='".$code."' LIMIT 1";
        $db->query($sql);

        return true;
    }


    public function updateUser(){

    }


    public function deleteUser(){

    }

}


?>