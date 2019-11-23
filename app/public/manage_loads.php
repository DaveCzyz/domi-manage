<?php 
require 'header.php'; 

// Check if user are logged
if(!$session->isLogged()){
    redirect("index.php");
}

// Display system messages
$session->displayMessage();
$msg_status = $session->status;
$msg        = $session->message;

// Get user class
$userID = $_SESSION['user_id'];
$user   = new User();
$user->getUser($userID);



// Delete group of loads
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['deleteGroup'])){
    if($_POST['id'] != "" && $_POST['load_id'] != ""){
        $id      = $_POST['id'];
        $load_id = $_POST['load_id'];

        if(Loads::deleteLoad($id, $load_id)){
            $session->message("ładunek został usunięty", "success");
        }else{
            $session->message("Wystąpił błąd. Spróbuj ponownie", "error");
        }

    }else{

    }
}

// Edit group 
$currentLoad;

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['editGroup'])){
    $id      = $_POST['id'];
    $load_id = $_POST['load_id'];
    $user_id = $user;

    // przypisać do current load funkcje z klasy loads
    // $currentLoad = Loads::getOneGroup();
}



// Edit single load


?>



<?php if($currentLoad != "") : ?>

kot tutaj


<?php endif; ?>























<?php require 'footer.php';?>