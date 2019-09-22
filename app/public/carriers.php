<?php 
require 'header.php'; 

if(!$session->isLogged()){
    redirect("index.php");
}

$session->displayMessage();
$msg    = $session->message;
$errors = [];



?>