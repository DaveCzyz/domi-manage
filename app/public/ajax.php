<?php
require_once '../config/init.php';

if(isset($_POST['getLoad'])){
    $relatedLoads = $_POST['getLoad'];
    echo ActiveLoads::showRelatedLoads($relatedLoads);
}
?>