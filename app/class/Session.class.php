<?php

class Session{

    public $message;

    public function __construct(){
        session_start();
    }

    public function message($msg = ""){
        if(!empty($msg)){
            $_SESSION['message'] = $msg;
        }
    }

    public function displayMessage(){
        if(isset($_SESSION['message'])){
            $this->message = $_SESSION['message'];
        }
        unset($_SESSION['message']);
    }
    

    public static function throwMessage($status, $msg){
        switch ($status){
            case "success":
                echo "
                    <div class='row justify-content-center'>
                        <div class='alert alert-success col-4 text-center' role='alert'>
                            ".$msg."
                        </div>
                    </div>
                ";
            break;

            case "error":
                echo "
                    <div class='row justify-content-center'>
                        <div class='alert alert-danger col-4 text-center' role='alert'>
                            ".$msg."
                        </div>
                    </div>
                ";
            break;

            case "info" :
                echo "
                    <div class='row justify-content-center'>
                        <div class='alert alert-info col-4 text-center' role='alert'>
                            ".$msg."
                        </div>
                    </div>
                ";
            break;
        }
    }





}

$session = new Session();

?>