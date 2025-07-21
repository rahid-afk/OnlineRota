<?php
session_start();

require_once ('Models/UserSet.php');

$view = new stdClass();
$view->pageTitle = 'Update User';

$userSet = new UserSet();

$id = $_GET['id'];
if (isset($_POST['updateUser'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $pass = $_POST['password'];
    $usertype = $_POST['usertype'];
    $view->userSet = $userSet->updateUser($id, $name, $pass, $usertype);
    header("Location: index.php");
}
if (isset($_GET['id'])){
    $view->userSet = $userSet->selectWithID($id);
}

require_once ('Views/updateUser.phtml');