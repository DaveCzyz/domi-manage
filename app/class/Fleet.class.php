<?php

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class Fleet{
    protected static $db_name = "fleet";

    public $related_with;
    public $user_id;
    public $fleet_uuid;

    public $driver_name;
    public $driver_phone;
    public $driver_id;

    public $truck_type;
    public $truck_ldm;
    public $truck_weight;
    public $truck_height;
    public $truck_plate;

    public function __construct($r, $u){
        $this->related_with = $r;
        $this->user_id      = $u;

        try{
            $uuid1 = Uuid::uuid1();
            $this->fleet_uuid = $uuid1->toString();
        }catch(UnsatisfiedDependencyException $e){
            echo "Błąd przy generowaniu UUID: " . $e->getMessage();
        }
    }

    // Add new truck
    public function setTruck(){
        global $db;
        if(empty($this->related_with) && empty($this->user_id)){
            return false;
        }

        // Clear variables
        $this->related_with = $db->escape_string($this->related_with);
        $this->user_id      = $db->escape_string($this->user_id);

        $this->driver_name  = $db->escape_string($this->driver_name);
        $this->driver_phone = $db->escape_string($this->driver_phone);
        $this->driver_id    = $db->escape_string($this->driver_id);

        $this->truck_type    = $db->escape_string($this->truck_type);
        $this->truck_ldm     = $db->escape_string($this->truck_ldm);
        $this->truck_weight  = $db->escape_string($this->truck_weight);
        $this->truck_height  = $db->escape_string($this->truck_height);
        $this->truck_plate   = $db->escape_string($this->truck_plate);

        $sql = "INSERT INTO " . self::$db_name . " ";
        $sql.= "(user_id, fleet_uuid, related_with, ";
        $sql.= "driver_name, driver_phone, driver_id, ";
        $sql.= "truck_type, truck_ldm, truck_weight, truck_height, truck_plate) ";
        $sql.= "VALUES ('".$this->user_id."', '".$this->fleet_uuid."', '".$this->related_with."', ";
        $sql.= "'".$this->driver_name."', '".$this->driver_phone."', '".$this->driver_id."', ";
        $sql.= "'".$this->truck_type."', '".$this->truck_ldm."', '".$this->truck_weight."', '".$this->truck_height."', '".$this->truck_plate."') ";
        
        if($db->query($sql)){
            return true;
        }else{
            $this->err[] = "Wystąpił bład. Spróbuj ponownie";
            return false;
        }

    }



}







?>