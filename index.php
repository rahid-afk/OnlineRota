<?php
session_start();
require_once ('Models/UserSet.php');

$view = new stdClass();
$view->pageTitle = 'Homepage';

$userSet = new UserSet();
$username = $_SESSION['login'];

$usertype = $userSet->fetchUsertype($username);

/* Establishing which page to require based on the usertype */
if (isset($_SESSION['login'])){
    switch ($usertype) {
        case 1: // Admin/Manager
            require_once('managerPage.php');
            break;
        case 2: // Doctor
            require_once('doctorPage.php');
            break;
        case 3: // Receptionist (if you have this role)
//            require_once('receptionPage.php');
            break;
        default:
            // Unknown user type, log out for security
            session_destroy();
            header("Location: login.php?error=invalid_usertype");
            exit();
    }
} else {
    header("Location: login.php");
}