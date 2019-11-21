<?php
require_once '../config/init.php';

if(isset($_GET['activ'])){
    $code = $_GET['activ'];
    $user = new User();
    if($user->activeAccount($code)){
        global $session;
        $session->message("Konto aktywowane. Zaloguj się", "success");
        redirect("index.php");
    };

}else{
    redirect("index.php");
}

?>














