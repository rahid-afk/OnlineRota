<?php
session_start();

require_once ('Models/UserSet.php');

$view = new stdClass();
$view->pageTitle = 'Log In';

//$userSet = new UserSet();

if (isset($_POST['loginbutton'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    /* Fix Database connection */
//    $user = $userSet->fetchUsername($username);
//    $pass = $userSet->fetchPassword($username);
//
//    if ($user === $username && $pass === $password) {
//        $_SESSION['login'] = $username;
//    }

    if ($username == 'mujahid' && $password == 'password') {
        $_SESSION['login'] = $username;
    } elseif ($username == 'jamil' && $password == '123456') {
        $_SESSION['login'] = $username;
    } elseif ($username == 'rumaytha' && $password == 'rumaytha') {
        $_SESSION['login'] = $username;
    } elseif ($username == 'zakariya' && $password == 'zakariya') {
        $_SESSION['login'] = $username;
    } else {
        echo "Error in username and/or password";
    }
}

if (isset($_POST['logoutbutton'])) {
    echo "logout user";
    unset($_SESSION['login']);
    session_destroy();
}

require_once ('Views/login.phtml');