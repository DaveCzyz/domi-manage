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
            $sql.= "(load_id, user_id, customer, origin_name, origin_country, destination_name, destination_country) ";
            $sql.= "VALUES ('$tmp_loadID', '$tmp_userID', '$tmp_customer', '$tmp_originName', '$tmp_originCountry', '$tmp_destinationName', '$tmp_destinationCountry')";
            $db->query($sql);
            return true;
            
        }else{
            $this->err[] = "Wystąpił błąd podczas przesyłania danych z formularza";
            return false;
        }
    }

    // Get all groups from DB
    public function getGroups(){
        
    }


    // Edit existed load
    public function editLoad(){


    }


    // Delete load
    public function deleteLoad(){


    }

}


?>