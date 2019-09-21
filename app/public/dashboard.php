<?php
require 'header.php'; 

if(!$session->isLogged()){
    redirect("index.php");
}

echo $_SESSION['user_id'];










?>
