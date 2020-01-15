<?php

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class Fleet{
    protected static $db_name = "fleet";

    public $id;

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

    // Get specify truck
    public function getTruck(int $i){
        if(empty($i)){
            return false;
        }
        if(!is_numeric($i)){
            return false;
        }
        if(empty($this->related_with)){
            return false;
        }
        if(empty($this->user_id)){
            return false;
        }

        global $db;
        $i = $db->escape_string($i);

        $sql = "SELECT * FROM " . self::$db_name . " ";
        $sql.= "WHERE id=".$i." AND user_id=".$this->user_id." AND related_with='".$this->related_with."' LIMIT 1 ";
        $query = $db->query($sql);
        if($query->num_rows == 1){
            while($row = $query->fetch_assoc()){
                $this->id               = $row['id'];
                $this->user_id          = $row['user_id'];
                $this->fleet_uuid       = $row['fleet_uuid'];
                $this->related_with     = $row['related_with'];
                $this->driver_name      = $row['driver_name'];
                $this->driver_phone     = $row['driver_phone'];
                $this->driver_id        = $row['driver_id'];
                $this->truck_type       = $row['truck_type'];
                $this->truck_ldm        = $row['truck_ldm'];
                $this->truck_weight     = $row['truck_weight'];
                $this->truck_height     = $row['truck_height'];
                $this->truck_plate      = $row['truck_plate'];
            }
            return true;

        }else{
            return false;
        }
    }

    // Get all trucks
    public static function getAllTrucks($i){
        global $db;
        if(empty($i)){
            return false;
        }

        $trucks = [];

        $sql = "SELECT * FROM " . self::$db_name . " ";
        $sql.= "WHERE related_with='".$i."' ";
        $query = $db->query($sql);

        if($query->num_rows >= 1){
            while($row = $query->fetch_assoc()){
                $trucks[] = $row;
            }

            return $trucks;
        }else{

            return false;
        }
    }

    // Edit truck
    public function editTruck(){
        if(empty($this->user_id)){
            return false;
        }
        if(empty($this->related_with)){
            return false;
        }

        global $db;
        // Clear variables
        $this->driver_name  = $db->escape_string($this->driver_name);
        $this->driver_phone = $db->escape_string($this->driver_phone);
        $this->driver_id    = $db->escape_string($this->driver_id);

        $this->truck_type    = $db->escape_string($this->truck_type);
        $this->truck_ldm     = $db->escape_string($this->truck_ldm);
        $this->truck_weight  = $db->escape_string($this->truck_weight);
        $this->truck_height  = $db->escape_string($this->truck_height);
        $this->truck_plate   = $db->escape_string($this->truck_plate);

        $sql = "UPDATE " . self::$db_name . " ";
        $sql.= "SET driver_name='".$this->driver_name."', driver_phone='".$this->driver_phone."', ";
        $sql.= "driver_id='".$this->driver_id."', truck_type='".$this->truck_type."', ";
        $sql.= "truck_ldm='".$this->truck_ldm."', truck_weight='".$this->truck_weight."', ";
        $sql.= "truck_height='".$this->truck_height."', truck_plate='".$this->truck_plate."' ";
        $sql.= "WHERE id=".$this->id." AND user_id=".$this->user_id." AND related_with='".$this->related_with."' LIMIT 1";
    
        if($db->query($sql)){
            return true;
        }else{
            return false;
        }
    }

    // Delete specified truck
    public function deleteTruck(){
        if(empty($this->id)){
            return false;
        }
        if(empty($this->related_with)){
            return false;
        }
        if(empty($this->user_id)){
            return false;
        }

        global $db;

        $sql = "DELETE FROM " . self::$db_name . " ";
        $sql.= "WHERE id=".$this->id." AND user_id=".$this->user_id." ";
        $sql.= "AND related_with='".$this->related_with."' LIMIT 1 ";
        if($db->query($sql)){
            return true;
        }else{
            return false;
        }
    }

    // Delete all trucks
    public static function deleteAllTrucks($r){
        if(empty($r)){
            return false;
        }
        global $db;
        $sql = "DELETE FROM " . self::$db_name . " ";
        $sql.= "WHERE related_with='".$r."' ";
        if($db->query($sql)){
            return true;
        }else{
            return false;
        }
    }
    




}







?>