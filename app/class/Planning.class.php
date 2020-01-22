<?php

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class Planning {

    protected static $db_planning = PLANNING;
    protected static $db_planning_fleet = PLANNING_TRUCK;
    protected static $db_planning_users;

    public $plan_uuid;
    public $user_id;
    public $plan_name;


    public function __construct(){
        try{
            $uuid1 = Uuid::uuid1();
            $this->plan_uuid = $uuid1->toString();
        }catch(UnsatisfiedDependencyException $e){
            echo "Błąd przy generowaniu UUID: " . $e->getMessage();
        }
    }

    // Add new plan
    public function addPlan(){
        global $db;
        if(empty($this->user_id)){
            return false;
        }
        if(empty($this->plan_name)){
            return false;
        }

        $this->plan_name = $db->escape_string($this->plan_name);

        $sql = "INSERT INTO " . self::$db_planning . " ";
        $sql.= "(plan_uuid, user_id, plan_name) ";
        $sql.= "VALUES ('".$this->plan_uuid."', ".$this->user_id.", '".$this->plan_name."')";
        if($db->query($sql)){
            return true;
        }else{
            return false;
        }
    }
    // Get all plans
    public static function getPlans(int $i){
        global $db;
        if(empty($i)){
            return false;
        }
        $plans = [];
        $i = $db->escape_string($i);

        $sql = "SELECT * FROM " . self::$db_planning . " ";
        $sql.= "WHERE user_id=".$i." ";

        $query = $db->query($sql);
        while($row = $query->fetch_assoc()){
            $plans[] = $row;
        }
        return $plans;   

    }
    // Save truck to specify plan
    public static function addTruck(int $i, $p, $t, $n){
        global $db;
        if(empty($i)){
            return false;
        }
        if(empty($p)){
            return false;
        }
        if(empty($t)){
            return false;
        }
        if(empty($n)){
            return false;
        }

        if(self::checkTruck($i, $p, $t)){
            return false;
        }

        $p = $db->escape_string($p);
        $t = $db->escape_string($t);
        $n = $db->escape_string($n);

        $sql = "INSERT INTO " . self::$db_planning_fleet . " ";
        $sql.= "(user_id, related_with, truck_uuid, plan_name) ";
        $sql.= "VALUES ('".$i."', '".$p."', '".$t."', '".$n."') ";
        if($db->query($sql)){
            return true;
        }else{
            return false;
        }
    }
    // Prevent for double plans
    public static function checkTruck($i, $p, $t){
        global $db;
        $sql = "SELECT * FROM " . self::$db_planning_fleet . " ";
        $sql.= "WHERE user_id='".$i."' AND related_with='".$p."' AND truck_uuid='".$t."' ";
        $query = $db->query($sql);
        if($query->num_rows == 1){
            return true;
        }else{
            return false;
        }
    }
    // Check trucks plan
    public static function getTruckPlan($t){
        if(empty($t)){
            return false;
        }
        global $db;
        $t = $db->escape_string($t);

        $plan = [];

        $sql = "SELECT * FROM " . self::$db_planning_fleet . " ";
        $sql.= "WHERE truck_uuid='".$t."' ";
        $query = $db->query($sql);

        if($query->num_rows >= 1){
            while($row = $query->fetch_assoc()){
                $plan[] = $row;
            }
            return $plan;
        }else{

            return false;
        }
    }
    // Delete truck form plan
    public static function deleteTruck(int $u, $i){
        if(empty($u)){
            return false;
        }
        if(empty($i)){
            return false;
        }

        global $db;
        $i = $db->escape_string($i);

        $sql = "DELETE FROM " . self::$db_planning_fleet . " ";
        $sql.= "WHERE id='".$i."' AND user_id='".$u."' LIMIT 1";
        if($db->query($sql)){
            return true;
        }else{
            return false;
        }
    }
    // Get truck from plan
    public static function getTrucks($i){
        global $db;
        $db->escape_string($i);

        $trucks = [];

        $sql = "SELECT * FROM " . self::$db_planning_fleet . " ";
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
    // Read truck details
    public static function getTruckDetais($n){
        global $db;
        $db->escape_string($n);

        $sql = "SELECT * FROM " . FLEET . " ";
        $sql.= "WHERE fleet_uuid='".$n."' ";
        $query = $db->query($sql);

        if($query->num_rows == 1){
            while($row = $query->fetch_assoc()){
                $trucks = $row;
            }
            return $trucks;
        }else{

            return false;
        }
    }
}
?>