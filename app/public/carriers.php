<?php 
require 'header.php'; 

if(!$session->isLogged()){
    redirect("index.php");
}


?>