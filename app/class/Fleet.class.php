<?php

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class Fleet{
    protected static $db_name = "fleet";

    public $related_with;
    public $user_id;
    public $fleet_uuid;

    public $carrier;
    public $base;

    public $driver_firstName;
    public $driver_lastName;
    public $driver_phone;
    public $driver_id;

    public $truck_type;
    public $truck_ldm;
    public $truck_weight;
    public $truck_height;
    public $truck_plate;
    public $trailer_plate;





}







?>