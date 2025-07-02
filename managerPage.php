<?php
//require_once('Models/DeliveryPointSet.php');
//require_once('Models/DeliveryUserSet.php');
$view = new stdClass();
$view->pageTitle = "Manager Page";
//
//$deliveryPointSet = new DeliveryPointSet();
//$deliveryUserSet = new DeliveryUserSet();

if (isset($_POST['createRecord'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $postcode = $_POST['postcode'];
    $deliverer = $_POST['deliverer'];
    $lat = $_POST['lat'];
    $long = $_POST['long'];
    $status = $_POST['status'];
    $view->deliveryPointSet = $deliveryPointSet->createNewRecord($name, $address, $city, $postcode, $deliverer, $lat, $long, $status);
} elseif (isset($_POST['createUser'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $usertype = $_POST['usertype'];
    $view->deliveryUserSet = $deliveryUserSet->createUser($username, $password, $usertype);
} elseif (isset($_POST['viewRecordsBtn'])) {
    $view->deliveryPointSet = $deliveryPointSet->getAllDeliveries();
} elseif (isset($_POST['viewUsersBtn'])) {
    $view->deliveryUserSet = $deliveryUserSet->getAllUsers();
}

require_once('Views/managerpage.phtml');