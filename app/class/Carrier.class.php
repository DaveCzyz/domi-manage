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

    // Edit carrier

    // Delete carrier

}

?>