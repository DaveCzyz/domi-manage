<?php

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class User{
    protected static $db_name = "users";

    public $id;
    public $uuid;

    public $firstName;
    public $lastName;

    public $phone;
    public $email;
    public $password;

    public $trans_id;
    public $trans_pass;

    public $activeCode;
    public $active;
    
    public $err = [];

    // Static function for verifing user during login
    public function verifyUser($m, $p){
        global $db;
        global $session;
        $encrypt;
        $user_id;
        $active;
        $email      = $db->escape_string($m);
        $password   = $db->escape_string($p);

        $sql = "SELECT * FROM ". self::$db_name;
        $sql.= " WHERE email='".$email."' LIMIT 1";
        $query = $db->query($sql);

        if($query->num_rows == 1){

            $_SESSION['tmp_email_login'] = $email;

            while($row = $query->fetch_array(MYSQLI_ASSOC)){
                $encrypt = $row['password'];
                $user_id = $row['id'];
                $active  = $row['activ'];
            }

            if($active == "NO"){
                $this->err[] = 'Konto nie zostało aktywowane';
                return false;
            }
    
            if($this->encryptPassword($password, $encrypt)){
                unset($_SESSION['tmp_email_login']);
                $session->loginSession($user_id);
                return true;
            }else{
                $this->err[] = "Podane hasło jest nieprawidłowe";
                return false;
            }

        }else{
            $this->err[] = "Niepoprawny email lub hasło";
            return false;           
        }
    }

    // Function for register new users
    public function validateNewUser($em, $ps){
        global $db;
        $nameVal    = '/[\s!#\'\"$@|\/\\*&^%{}();:\.,<>?\[\]=+_~`\-0-9]/i';
        $emailVal   = '/[\s!#\'\"$|\/\\*&^%#{}();,:<>?\[\]=+_~`]/i';
        $fname      = $db->escape_string($this->firstName);
        $lname      = $db->escape_string($this->lastName);
        $email      = $db->escape_string($this->email);
        $pass       = $db->escape_string($this->password);
        $confEmail  = $db->escape_string($em);
        $confPass   = $db->escape_string($ps);
        $uuid       = "";

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

        // Generate Unique User ID
        try{
            $uuid1 = Uuid::uuid1();
            $uuid = $uuid1->toString();
        }catch(UnsatisfiedDependencyException $e){
            $this->err[] = "Błąd przy generowaniu UUID: " . $e->getMessage();
        }

        // If errors not occur send query to database
        if(empty($this->err)){
            global $db;
            $pass = $this->hashPassword($pass);
            $sql = "INSERT INTO users ";
            $sql.= " (uuid, firstName, lastName, email, password, activeCode) ";
            $sql.= " VALUES ('$uuid', '$fname', '$lname', '$email', '$pass', '$code')";
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

    // Read specify user
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
            $this->uuid       = $row['uuid'];
            $this->firstName  = $row['firstName'];
            $this->lastName   = $row['lastName'];
            $this->email      = $row['email'];
            $this->password   = $row['password'];
            $this->activeCode = $row['activeCode'];
            $this->active     = $row['activ'];
            $this->trans_id   = $row['trans_id'];
            $this->trans_pass = $row['trans_pass'];
            $this->phone      = $row['phone'];

            return $row;
        }
    }

    // Edit user data
    public function setUser(){
        global $db;
        // Personal data
        $this->firstName = $db->escape_string($this->firstName);
        $this->lastName  = $db->escape_string($this->lastName);
        $this->email     = $db->escape_string($this->email);
        $this->phone     = $db->escape_string($this->phone);
        // Trans data
        $this->trans_id  = $db->escape_string($this->trans_id);
        $this->trans_pass= $db->escape_string($this->trans_pass);
        // Password
        $this->password  = $db->escape_string($this->password);

        $sql = "UPDATE ". self::$db_name ." ";
        $sql.= "SET firstName='".$this->firstName."', lastName='".$this->lastName."', email='".$this->email."', ";
        $sql.= "password='".$this->password."', trans_id='".$this->trans_id."', trans_pass='".$this->trans_pass."', phone='".$this->phone."' ";
        $sql.= "WHERE id='".$this->id."' AND uuid='".$this->uuid."' LIMIT 1";

        if($db->query($sql)){
            return true;
        }else{
            $this->err[] = "Wysątpił bład. Spróbuj ponownie";
            return false;
        }

    }

    // Change and hash new password
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
            $this->password = $this->hashPassword($new);
            $this->setUser();
            return true;

        }else{
            $this->err[] = "Wystąpił nieoczekiwany błąd. Spróbuj ponownie";
            return false;
        }
    }

    // Change trans password
    public function changeTransData(){
        $this->trans_pass = $this->hashPassword($this->trans_pass);
        if($this->setUser()){
            $this->err[] = "Dane zostały zaktualizowane";
            return true;
        }else{
            $this->err[] = "Wystąpił błąd. Spróbuj ponownie";
            return false;
        }
    }




    public function deleteUser($p){
        global $db;
        if(empty($p)){
            echo "wpisz haslo";
            $this->err[] = "Wpisz hasło";
            return false;
        }
        // Check if password is correct
        if(!$this->encryptPassword($p, $this->password)){
            echo 'zle haslo';
            $this->err[] = "Podane hasło jest nieprawidłowe";
            return false;
        }
        // Delete all related loads
        $sql = "DELETE FROM related_loads ";
        $sql.= "WHERE user_id=".$this->id." ";
        if(!$db->query($sql)){
            $this->err[] = "Bład. Powiązane ładunki nie zostały usunięte";
            return false;
        }

        // Delete all groups of load
        $sql2 = "DELETE FROM loads ";
        $sql2.= "WHERE user_id=".$this->id." ";
        if(!$db->query($sql2)){
            $this->err[] = "Bład. Grupy ładunków nie zostały usunięte";
            return false;
        }

        // Delete user
        $sql3 = "DELETE FROM " . self::$db_name . " ";
        $sql3.= "WHERE id=".$this->id." AND uuid='".$this->uuid."' LIMIT 1";
        if(!$db->query($sql3)){
            $this->err[] = "Bład. Użytkownik nie został usunięty";
            return false;
        }
 
        if(empty($this->err)){
            return true;
        }
    }
}


?>