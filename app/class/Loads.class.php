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
    
    public function __construct(){
        try{
            $uuid1 = Uuid::uuid1();
            $this->load_id = $uuid1->toString();
        }catch(UnsatisfiedDependencyException $e){
            echo "Błąd przy generowaniu UUID: " . $e->getMessage();
        }
    }


    // Create new load
    public function addNewGroup($loadData){
        global $db;

       


    }


    // Edit existed load
    public function editLoad(){


    }


    // Delete load
    public function deleteLoad(){


    }

}


?>