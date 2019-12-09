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

if(isset($_GET['getLoadData'])){
    $userID = $_SESSION['user_id'];
    $key    = $_GET['getLoadData'];
    $groupLoads = Loads::getGroups($userID);

    $cust = array();
    foreach($groupLoads as $k => $v){
        $cust[] = $v[$key];
    }

    echo json_encode($cust);
}
?>