<?php

class Session{

    public $message;
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
        unset($_SESSION['user_id']);
        $this->message("Zostałeś poprawnie wylogowany");
        redirect("index.php");
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