<?php

class User{
    protected static $db_name = "users";
    public $id;
    public $firstName;
    public $lastName;
    public $email;
    public $password;
    public $activeCode;
    public $active;
    public $trans_id;
    public $phone;
    
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

        if($query->num_rows == 1){

            while($row = $query->fetch_array(MYSQLI_ASSOC)){
                $encrypt = $row['password'];
                $user_id = $row['id'];
            }
    
            if($this->encryptPassword($password, $encrypt)){
                $session->loginSession($user_id);
                return true;
            }

        }else{
            $this->err[] = "Niepoprawny email lub hasło";
            return false;           
        }
    }

    // Function for register new users
    public function validateNewUser($em, $ps){
        global $db;
        $nameVal = '/[\s!#\'\"$@|\/\\*&^%{}();:\.,<>?\[\]=+_~`\-0-9]/i';
        $emailVal = '/[\s!#\'\"$|\/\\*&^%#{}();,:<>?\[\]=+_~`]/i';
        $fname = $db->escape_string($this->firstName);
        $lname = $db->escape_string($this->lastName);
        $email = $db->escape_string($this->email);
        $pass  = $db->escape_string($this->password);
        $confEmail = $db->escape_string($em);
        $confPass  = $db->escape_string($ps);

        // Generator for activation code
        $code = uniqid();

        // Validate fields - first name and last name
        if($fname == "" or $lname == ""){
            $this->err[] = "Wpisz swoje imię i nazwisko";
            return false;
        }else if(strlen($fname) <= 2 or strlen($lname) <= 2){
            $this->err[] = "Wprowadzone imię lub nazwisko jest za krótkie";
            return false;
        }else if(preg_match($nameVal, $fname) != 0 or preg_match($nameVal, $lname) != 0){
            $this->err[] = "Imię lub nazwisko zawiera niedozwolone znaki";
            return false;
        }

        // Validate fields - email
        if($email == ""){
            $this->err[] = "Wpisz swój adres email";
            return false;
        }else if(!filter_var($email, FILTER_SANITIZE_EMAIL)){
            $this->err[] = "Niepoprawny format adresu email";
            return false;
        }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->err[] = "Niepoprawny format adresu email";
            return false;
        }else if(preg_match($emailVal, $email) != 0){
            $this->err[] = "Adres email zawiera niedozwolone znaki";
            return false;
        }else if(!self::compareData($email, $confEmail)){
            $this->err[] = "Adresy email nie są takie same";
            return false;
        }

        // Validate fields - password
        if($pass == ""){
            $this->err[] = "Wpisz swóje hasło";
            return false;
        }else if(strlen($pass) < 8){
            $this->err[] = "Podane hasło jest za krótkie";
            return false;
        }else if(!self::compareData($pass, $confPass)){
            $this->err[] = "Hasła nie są takie same";
            return false;
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
            $this->err[] = "Wystąpił błąd. Spróbuj ponownie";
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

    public function getUser($id){
        if(empty($id)){
            $this->err[] = "Brak użytkownika o podanym ID";
            return false;
        }

        global $db;
        $sql = "SELECT * FROM ". self::$db_name ."";
        $sql.= " WHERE id='". $id ."' LIMIT 1";
        $query = $db->query($sql);

        while($row = $query->fetch_assoc()){
            $this->id         = $row['id'];
            $this->firstName  = $row['firstName'];
            $this->lastName   = $row['lastName'];
            $this->email      = $row['email'];
            $this->password   = $row['password'];
            $this->activeCode = $row['activeCode'];
            $this->active     = $row['activ'];
            $this->trans_id   = $row['trans_id'];
            $this->phone      = $row['phone'];

            return $row;
        }
    }


    public function setUser(){
        
    }

    public function changePassword($o, $n, $cn){
        global $db;
        $old     = $db->escape_string($o);
        $new     = $db->escape_string($n);
        $confNew = $db->escape_string($cn);

        // Validate password
        if(empty($old)){
            $this->err[] = "Podaj swoje aktualne hasło";
            return false;
        }

        if(!self::compareData($new, $confNew)){
            $this->err[] = "Podane hasła nie są takie same";
            return false;
        }

        if(!$this->encryptPassword($old, $this->password)){
            $this->err[] = "Aktualne hasło jest nieprawidłowe";
            return false;
        }

        if(strlen($new) < 8){
            $this->err[] = "Nowe hasło jest za krótkie";
            return false;
        }

        if(empty($this->err)){
            $this->setUser();
            return true;

        }else{
            $this->err[] = "Wystąpił nieoczekiwany błąd. Spróbuj ponownie";
            return false;
        }
    }


    public function deleteUser(){

    }

}


?>