<?php

class Session{

    public $message;
    public $status;
    public $user_id;
    private $isSigned = false;

    public function __construct(){
        session_start();
        $this->checkLogin();
    }

    private function checkLogin(){
        if(isset($_SESSION['user_id'])){
            $this->user_id  = $_SESSION['user_id'];
            $this->isSigned = true;
        }else{
            unset($this->user_id);
            $this->isSigned = false;
        }
    }

    public function loginSession($i){
        if($i){
            $this->user_id  = $_SESSION['user_id'] = $i;
            $this->isSigned = true;
        }
    }

    public function isLogged(){
        return $this->isSigned;
    }

    public function logout(){
        self::clearManageLoads();
        unset($_SESSION['user_id']);
        $this->message("Zostałeś poprawnie wylogowany", "success");
        redirect("index.php");
    }

    public function message($msg = "", $status = ""){
        if(!empty($msg) && !empty($status)){
            $_SESSION['msg_status']  = $status;
            $_SESSION['message']     = $msg;
        }
    }

    public function displayMessage(){
        if(isset($_SESSION['message']) && isset($_SESSION['msg_status'])){
            $this->status  = $_SESSION['msg_status'];
            $this->message = $_SESSION['message'];
        }
        unset($_SESSION['msg_status'], $_SESSION['message']);
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

    public static function manageLoads($load_id, $load_uuid){
        self::clearManageLoads();
        $_SESSION['load_id']    = $load_id;
        $_SESSION['load_uuid']  = $load_uuid;
    }

    public static function clearManageLoads(){
        unset($_SESSION['load_id']);
        unset($_SESSION['load_uuid']);
    }



}

$session = new Session();

?>