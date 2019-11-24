<?php

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class ActiveLoads{
    protected static $db_name = "related_loads";

    public $active_load_id;
    
    public $related_with;
    public $user_id;

    public $origin_name;
    public $origin_country;
    public $origin_iso;
    public $origin_postcode;

    public $destination_name;
    public $destination_country;
    public $destination_iso;
    public $destination_postcode;

    public function __construct($related, $user){
        $this->related_with = $related;
        $this->user_id      = $user;

        try{
            $uuid1 = Uuid::uuid1();
            $this->active_load_id = $uuid1->toString();
        }catch(UnsatisfiedDependencyException $e){
            echo "Błąd przy generowaniu UUID: " . $e->getMessage();
        }
    }




    // Show related loads by main load ID number
    public static function showRelatedLoads($id){
        global $db;

        if(empty($id)){
            return false;
        }

        $sql = "SELECT * FROM ". self::$db_name . " ";
        $sql.= "WHERE related_with='". $id . "' ";
        $query = $db->query($sql);

        $loadArray = [];

        while($row = $query->fetch_assoc()){
            $loadArray[] = array_map('utf8_encode', $row);
        }

        if(!empty($loadArray)){
            // JSON format for AJAX request in loads.php
            return json_encode($loadArray);
        }else{
            return false;
        }
    }

    // Delete ALL related loads
    public static function deleteRelatedLoads($user_id, $related_with){
        global $db;

        if($user_id != "" && $related_with != ""){
            $sql = "DELETE FROM " . self::$db_name . " ";
            $sql.= "WHERE related_with='". $related_with . "' AND user_id='" . $user_id . "' ";

            if($db->query($sql)){
                return true;
            }else{
                return false;
            }

        }else{
            return false;
        }
    }

    // Delete specified related load
}

?>