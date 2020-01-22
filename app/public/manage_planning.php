<?php

require_once '../config/init.php';

// Get user class
$userID = $_SESSION['user_id'];
$user   = new User();
$user->getUser($userID);

// Create new plan
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addPlan'])){
    $p = new Planning();
    $p->user_id = $user->id;
    $p->plan_name = $_POST['planName'];

    if($p->addPlan()){
        $session->message("Plan został dodany", "success");
        redirect("fleet.php"); 
    }else{
        $session->message("Wystąpił błąd. Spróbuj ponownie", "error");
        redirect("fleet.php");
    }
}
// Add truck to plan
if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['u'])){
    $id         = $user->id;
    $plan_uuid  = $_GET['u'];
    $truck_uuid = $_GET['t'];
    $plan_name  = $_GET['n'];
    if(Planning::addTruck((int)$id, $plan_uuid, $truck_uuid, $plan_name)){
        $session->message("Pojazd został dodany do planu", "success");
        redirect("manage_carriers.php"); 
    }else{
        $session->message("Wystąpił błąd. Spróbuj ponownie", "error");
        redirect("manage_carriers.php");
    }
}
// Delete truck from plan
if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['deleteTruck'])){
    $plan_id = $_GET['deleteTruck'];
    if(Planning::deleteTruck((int)$user->id, $plan_id)){
        $session->message("Pojazd został usunięty z planu", "success");
        redirect("manage_carriers.php"); 
    }else{
        $session->message("Wystąpił błąd. Spróbuj ponownie", "error");
        redirect("manage_carriers.php");
    }
}



?>