<?php
require 'header.php'; 

if(!$session->isLogged()){
    redirect("index.php");
}

$session->displayMessage();
$msg_status = $session->status;
$msg        = $session->message;



?>

<div class="row justify-content-center">
    <div class="col-10 center">
        <h3 class="text-center">Witaj</h3>
        <?php 
            if(!empty($msg) && !empty($msg_status)){
                Session::throwMessage($msg_status, $msg);
            }
        ?>
    </div>
</div>





<?php require 'footer.php';?>