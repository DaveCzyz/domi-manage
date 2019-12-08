<?php
require_once '../config/init.php';

if(isset($_GET['getLoad'])){
    $relatedLoads = $_GET['getLoad'];
    $test = ActiveLoads::showRelatedLoads($relatedLoads);
    echo $test;
}

if(isset($_GET['getCustomerList'])){
    $userID = $_SESSION['user_id'];
    $groupLoads = Loads::getGroups($userID);

    $cust = array();
    foreach($groupLoads as $k => $v){
        $cust[] = $v['customer'];
    }

    $customer_json = json_encode($cust);
    echo json_encode($cust);
}
?>