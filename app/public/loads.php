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
    <div class="col-8">



        tutaj input trzeba schować
        <!-- Default form login -->
        <form class="border border-light p-5" action="" method="POST">

            <p class="h4 mb-4 text-center">Dodaj nową grupe ładunków</p>

            <div class="form-row mb-4">
                <div class="col">
                    <!-- First name -->
                    <label for="defaultRegisterFormFirstName" class="text-left">Your vanity URL</label>
                    <input type="text" id="defaultRegisterFormFirstName" class="form-control" placeholder="First name">
                </div>
                <div class="col">
                    <!-- Last name -->
                    <label for="defaultRegisterFormLastName">Your vanity URL</label>
                    <input type="text" id="defaultRegisterFormLastName" class="form-control" placeholder="Last name">
                </div>
            </div>




            <!-- Sign in button -->
            <button class="btn btn-success green darken-1 btn-block my-4" type="submit">Dodaj</button>

      

        </form>
        <!-- Default form login -->
        



    </div>
</div>


<div class="row justify-content-center">
    <div class="col-3 text-center">
        <button type="button" class="btn btn-success green darken-1">Dodaj ładunek</button>
    </div>
</div>



