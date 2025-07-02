<?php
session_start();
require_once ('Models/UserSet.php');

$view = new stdClass();
$view->pageTitle = 'Homepage';

//$userSet = new UserSet();
$username = $_SESSION['login'];

//$usertype = $userSet->fetchUsertype($username);

/* Establishing which page to require based on the usertype */
if (isset($_SESSION['login'])){
//    if ($usertype == 1){ /* Admin */
//        require_once('managerPage.php');
//    } else {
//        require_once ("doctorPage.php");
////        header("Location: index.php?page=1");
//        exit;
//    }
//    /*ELSE IF usertype == 3 THEN
//            require_once ("receptionPage.php")
//    */

    if ($username == 'mujahid') {
        require_once ('managerPage.php');
    } elseif ($username == 'jamil') {
        require_once ('doctorPage.php');
    } else {
        echo "Error in username and/or password";
    }

} else {
    header("Location: login.php");
}