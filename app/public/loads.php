<?php 
require 'header.php'; 

if(!$session->isLogged()){
    redirect("index.php");
}

$session->displayMessage();
$msg    = $session->message;
$errors = [];

// Get user class
$userID = $_SESSION['user_id'];
$user   = new User();
$user->getUser($userID);

// Get user loads


?>

<div class="row justify-content-center">
    <div class="col-10 center">
        <h3 class="text-center">Zarządzaj bieżącymi ładunkami</h3>
        <?php 
            if(!empty($msg) && !empty($msg_status)){
                Session::throwMessage($msg_status, $msg);
            }
        ?>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-3 text-center">
        <button type="button" class="btn btn-success green darken-1">Dodaj ładunek</button>
    </div>

    <div class="col-10">
        tutaj input trzeba schować

        
    </div>
</div>



