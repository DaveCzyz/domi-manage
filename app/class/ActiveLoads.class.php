<?php

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class ActiveLoads{
    protected static $db_name = "related_loads";

    public $load_id;
    
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

    public $trailer;
    public $weight;
    public $length;

    public $err = [];

    public function __construct($related, $user){
        $this->related_with = $related;
        $this->user_id      = $user;

        try{
            $uuid1 = Uuid::uuid1();
            $this->load_id = $uuid1->toString();
        }catch(UnsatisfiedDependencyException $e){
            echo "Błąd przy generowaniu UUID: " . $e->getMessage();
        }
    }

    // Show ALL related loads by main load ID number
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
    public function deleteOneLoad(){

    }
    // Add new load
    public function addNewLoad(){
        global $db;
        // Clear variables
        $this->related_with = $db->escape_string($this->related_with);
        $this->user_id      = $db->escape_string($this->user_id);
        // Origin variables
        $this->origin_name      = $db->escape_string($this->origin_name);
        $this->origin_country   = $db->escape_string($this->origin_country);
        $this->origin_postcode  = $db->escape_string($this->origin_postcode);
        // Destination variables
        $this->destination_name     = $db->escape_string($this->destination_name);
        $this->destination_country  = $db->escape_string($this->destination_country);
        $this->destination_postcode = $db->escape_string($this->destination_postcode);
        // Loads details
        $this->trailer  = $db->escape_string($this->trailer);
        $this->weight   = $db->escape_string($this->weight);
        $this->length   = $db->escape_string($this->length);
        // Split country ISO
        $tmp_originISO          = explode(', ', $this->origin_country);
        $this->origin_iso       = $tmp_originISO[1];
        $this->origin_country   = $tmp_originISO[0];
        $tmp_destinationISO         = explode(', ', $this->destination_country);
        $this->destination_iso      = $tmp_destinationISO[1];
        $this->destination_country  = $tmp_destinationISO[0];
        // Validate weinght nad length to float
        $tmp_weight     = str_replace(",", ".", $this->weight);
        $this->weight   = $tmp_weight;
        $tmp_length     = str_replace(",", ".", $this->length);
        $this->length   = $tmp_length;
        // Validate variables
        if($this->related_with == ""){
            $this->err[] = "Wystpąpił błąd. Spróbuj ponownie";
        }
        if($this->user_id == "" || $this->user_id == 0){
            $this->err[] = "Wystpąpił błąd. Spróbuj ponownie";
        }
        if($this->origin_name == "" || $this->origin_country == "" || $this->origin_postcode == ""){
            $this->err[] = "Wprowadź miejsce załadunku";
        }
        if($this->destination_name == "" || $this->destination_country == "" || $this->destination_postcode == ""){
            $this->err[] = "Wprowadź miejsce rozładunku";
        }
        if($this->trailer == "" || $this->weight == "" || $this->length == ""){
            $this->err[] = "Wprowadź szczegóły ładunku";
        }
        // Save load to database
        if(empty($this->err)){
            $sql = "INSERT INTO " . self::$db_name . " ";
            $sql.= "(load_id, related_with, user_id, origin_name, origin_country, origin_iso, origin_postcode, destination_name, ";
            $sql.= "destination_country, destination_iso, destination_postcode, trailer, weight, length) ";
            $sql.= "VALUES ('$this->load_id', '$this->related_with', '$this->user_id', '$this->origin_name', '$this->origin_country', '$this->origin_iso', '$this->origin_postcode', ";
            $sql.= "'$this->destination_name', '$this->destination_country', '$this->destination_iso', '$this->destination_postcode', '$this->trailer', '$this->weight', '$this->length') ";
          
            if($db->query($sql)){
                return true;
            }else{
                $this->err[] = "Wystpąpił błąd. Spróbuj ponownie";
                return false;
            }
        }else{
            $this->err[] = "Wystpąpił błąd. Spróbuj ponownie";
            return false;
        }
    }
}

?>

