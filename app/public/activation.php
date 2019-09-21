<?php
require_once '../config/init.php';

if(isset($_GET['activ'])){
    $code = $_GET['activ'];
    echo $code;
    $user = new User();
    if($user->activeAccount($code)){
        global $session;
        $session->message("Konto aktywowane. Zaloguj się");
        redirect("index.php");
    };

}else{
    redirect("index.php");
}

?>














