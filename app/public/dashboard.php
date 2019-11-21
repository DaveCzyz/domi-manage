<?php
require 'header.php'; 

// Check login session
if(!$session->isLogged()){
    redirect("index.php");
}

// Display current messages
$session->displayMessage();
$msg_status = $session->status;
$msg        = $session->message;

// Get user class
$userID = $_SESSION['user_id'];
$user   = new User();
$user->getUser($userID);


?>

<div class="row justify-content-center">
    <div class="col-10 center">
        <h3 class="text-center">Witaj <?php echo $user->firstName;?></h3>
        <?php 
            if(!empty($msg) && !empty($msg_status)){
                Session::throwMessage($msg_status, $msg);
            }
        ?>
    </div>
</div>





<?php require 'footer.php';?>