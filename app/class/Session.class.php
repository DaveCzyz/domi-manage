<?php

class Session{
    
    public static function errorMessage($msg){
        echo "
            <div class='row justify-content-center'>
                <div class='alert alert-danger col-4 text-center' role='alert'>
                    ".$msg."
                </div>
            </div>
        ";
    }





}

$session = new Session();

?>