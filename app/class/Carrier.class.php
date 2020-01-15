<?php

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class Carrier{

    protected static $db_name = "carriers";

    public $id;

    // User property
    public $user_id;
    public $carrier_uuid;

    // Carrier details
    public $carrier_name;
    public $carrier_base;
    public $carrier_person;
    public $carrier_phone;
    public $carrier_email;
    public $carrier_trucks;

    public $err = [];

    public function __construct(int $user){
        $this->user_id = $user;

        try{
            $uuid1 = Uuid::uuid1();
            $this->carrier_uuid = $uuid1->toString();
        }catch(UnsatisfiedDependencyException $e){
            echo "Błąd przy generowaniu UUID: " . $e->getMessage();
        } 
    }

    // Add new carrier
    public function setCarrier(){
        global $db;
        // clear variables
        $this->carrier_name     = $db->escape_string($this->carrier_name);
        $this->carrier_base     = $db->escape_string($this->carrier_base);
        $this->carrier_person   = $db->escape_string($this->carrier_person);
        $this->carrier_phone    = $db->escape_string($this->carrier_phone);
        $this->carrier_email    = $db->escape_string($this->carrier_email);

        if(empty($this->user_id)){
            $this->err[] = "Brak powiązanego użytkownika. Spróbuj ponownie";
            return false;
        }

        $sql = "INSERT INTO " . self::$db_name . " ";
        $sql.= "(user_id, carrier_uuid, carrier_name, carrier_base, ";
        $sql.= "carrier_person, carrier_phone, carrier_email) ";
        $sql.= "VALUES ('".$this->user_id."', '".$this->carrier_uuid."', '".$this->carrier_name."', ";
        $sql.= "'".$this->carrier_base."', '".$this->carrier_person."', '".$this->carrier_phone."', '".$this->carrier_email."')";

        if($db->query($sql)){
            return true;
        }else{
            $this->err[] = "Wystąpił błąd przy przesyłaniu danych. Spróbuj ponownie";
            return false;
        }

    }

    // Get all carriers
    public static function getAllCarriers(int $i){
        if(empty($i)){
            return false;
        }

        global $db;
        $carrier = [];
        $sql = "SELECT * FROM " . self::$db_name . " ";
        $sql.= "WHERE user_id='".$i."'";

        $query = $db->query($sql);
        while($row = $query->fetch_assoc()){
            $carrier[] = $row;
        }
        return $carrier;    
    }

    // Get carrier
    public function getCarrier($u){
        global $db;
        if(empty($u)){
            return false;
        }
        $u = $db->escape_string($u);

        if(empty($this->user_id)){
            return false;
        }

        $sql = "SELECT * FROM " . self::$db_name . " ";
        $sql.= "WHERE user_id='".$this->user_id."' AND  carrier_uuid='".$u."' LIMIT 1";
        $query = $db->query($sql);

        if($query->num_rows == 1){
            while($row = $query->fetch_array()){
                $this->id               = $row['id'];
                $this->user_id          = $row['user_id'];
                $this->carrier_uuid     = $row['carrier_uuid'];

                $this->carrier_name     = $row['carrier_name'];
                $this->carrier_base     = $row['carrier_base'];
                $this->carrier_person   = $row['carrier_person'];
                $this->carrier_phone    = $row['carrier_phone'];
                $this->carrier_email    = $row['carrier_email'];
                $this->carrier_trucks   = $row['carrier_trucks'];
            }

            if($this->carrier_uuid != $u){
                return false;
            }
        }else{
            return false;
        }




    }

    // Edit carrier
    public function editCarrier(){
        global $db;

        if(empty($this->id) && empty($this->user_id)){
            return false;
        }

        // clear variables
        $this->carrier_name     = $db->escape_string($this->carrier_name);
        $this->carrier_base     = $db->escape_string($this->carrier_base);
        $this->carrier_person   = $db->escape_string($this->carrier_person);
        $this->carrier_phone    = $db->escape_string($this->carrier_phone);
        $this->carrier_email    = $db->escape_string($this->carrier_email);

        $sql = "UPDATE " . self::$db_name . " ";
        $sql.= "SET carrier_name='".$this->carrier_name."', carrier_base='".$this->carrier_base."', ";
        $sql.= "carrier_person ='".$this->carrier_person."', carrier_phone='".$this->carrier_phone."', ";
        $sql.= "carrier_email='".$this->carrier_email."' ";
        $sql.= "WHERE id='".$this->id."' AND user_id='".$this->user_id."' LIMIT 1";

        if($db->query($sql)){
            return true;
        }else{
            $this->err[] = "Błąd zapytania SQL. Spróbuj ponownie";
            return false;
        }
    }   

    // Delete carrier
    public function deleteCarrier(){
        global $db;

        // Delete all carrier trucks
        $sql = "DELETE FROM fleet ";
        $sql.= "WHERE user_id=".$this->user_id." AND related_with='".$this->carrier_uuid."' ";
        if($db->query($sql)){

            // Delete carrier account
            $sql2 = "DELETE FROM ". self::$db_name . " ";
            $sql2.= "WHERE user_id=".$this->user_id." AND id=".$this->id." ";
            if($db->query($sql2)){
                return true;
            }else{
                $this->err[] = "Błąd. Przewoźnik nie został usunięty poprawnie";
                return false;
            }

        }else{
            $this->err[] = "Błąd. Pojazdy przewoźnika nie zostały usunięte";
            return false;
        }
    }

    // Update counter
    public function updateCounter($n){
        global $db;

        if(empty($n)){
            return false;
        }

        if($n == "plus"){
            $this->carrier_trucks = $this->carrier_trucks + 1;
        }

        if($n == "minus"){
            if($this->carrier_trucks == 0){
                return false;
            }

            $this->carrier_trucks = $this->carrier_trucks - 1;
        }

        $sql = "UPDATE " . self::$db_name . " ";
        $sql.= "SET carrier_trucks='" . $this->carrier_trucks . "' ";
        $sql.= "WHERE id=".$this->id." AND user_id=".$this->user_id." LIMIT 1";

        if($db->query($sql)){
            return true;
        }else{
            return false;
        }
    }
}

?>