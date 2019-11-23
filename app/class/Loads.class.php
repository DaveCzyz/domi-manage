<?php

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;


class Loads{
    protected static $db_name = "loads";

    public $load_id;
    public $user_id;
    public $customer;

    public $origin_name;
    public $origin_country;

    public $destination_name;
    public $destination_country;

    public $active_loads;
    // column active_loads in database has default "NO"

    public $err = [];
    
    public function __construct(){
        try{
            $uuid1 = Uuid::uuid1();
            $this->load_id = $uuid1->toString();
        }catch(UnsatisfiedDependencyException $e){
            echo "Błąd przy generowaniu UUID: " . $e->getMessage();
        }
    }

    // Create new load
    public function addNewGroup(){
        global $db;
        
        // temporary variables for validation
        $tmp_loadID             = $db->escape_string($this->load_id);
        $tmp_userID             = $db->escape_string($this->user_id);
        $tmp_customer           = $db->escape_string($this->customer);
        $tmp_originName         = $db->escape_string($this->origin_name);
        $tmp_originCountry      = $db->escape_string($this->origin_country);
        $tmp_destinationName    = $db->escape_string($this->destination_name);
        $tmp_destinationCountry = $db->escape_string($this->destination_country);

        // Split country ISO
        $tmp_originISO      = explode(', ', $tmp_originCountry);
        $origin_iso         = $tmp_originISO[1];
        $tmp_destinationISO = explode(', ', $tmp_destinationCountry);
        $destination_iso    = $tmp_destinationISO[1];

        // Form validation
        if($tmp_userID == ""){
            $this->err[] = "Błąd poczas przesyłania danych. Spróbuj ponownie";
            return false;
        }

        if($tmp_customer == ""){
            $this->err[] = "Podaj nazwę klienta";
            return false;
        }

        if($tmp_originName == ""){
            $this->err[] = "Podaj miejsce załadunku";
            return false;
        }

        if($tmp_originCountry == ""){
            $this->err[] = "Podaj kraj załadunku";
            return false;
        }

        if($tmp_destinationName == ""){
            $this->err[] = "Podaj miejsce rozładunku";
            return false;
        }

        if($tmp_destinationCountry == ""){
            $this->err[] = "Podaj kraj rozładunku";
            return false;
        }

        // If no errors occur then save data to DB
        if(empty($this->err)){
            $sql = "INSERT INTO " . self::$db_name . " ";
            $sql.= "(load_id, user_id, customer, origin_name, origin_country, origin_iso, destination_name, destination_country, destination_iso) ";
            $sql.= "VALUES ('$tmp_loadID', '$tmp_userID', '$tmp_customer', '$tmp_originName', '$tmp_originCountry', '$origin_iso', '$tmp_destinationName', '$tmp_destinationCountry', '$destination_iso')";
            $db->query($sql);
            return true;
            
        }else{
            $this->err[] = "Wystąpił błąd podczas przesyłania danych z formularza";
            return false;
        }
    }

    // Get all groups from DB
    public static function getGroups($id){
        if(empty($id)){
            $this->err['Brak ładunków do wyświetlenia'];
            return false;
        }

        global $db;
        $loads = [];

        $sql = "SELECT * FROM " . self::$db_name . " ";
        $sql.= "WHERE user_id='".$id."'";
        $query = $db->query($sql);

        while($row = $query->fetch_assoc()){
            $loads[] = $row;
        }

        return $loads;
    }

    // Get specify group
    public static function getOneGroup($id){

    }


    // Edit existed load
    public function editLoad(){


    }


    // Delete load
    public static function deleteLoad($id, $loadID){
        global $db;
        $tmp_id     = $db->escape_string($id);
        $tmp_loadID = $db->escape_string($loadID);

        if($tmp_id != "" && $tmp_loadID != ""){
            return true;

        }else{
            return false;
        }
    }



}


?>