<?php
require_once '../config/init.php';

if(isset($_GET['getLoad'])){
    $relatedLoads = $_GET['getLoad'];
    $test = ActiveLoads::showRelatedLoads($relatedLoads);
    echo $test;
}
?>