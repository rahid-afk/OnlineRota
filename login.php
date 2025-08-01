<?php
session_start();

require_once ('Models/UserSet.php');

$view = new stdClass();
$view->pageTitle = 'Log In';

$userSet = new UserSet();

if (isset($_POST['loginbutton'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)){
        $error = "Please enter both username and password";
    } else {
        try {
            $user = $userSet->fetchUsername($username);
            $pass = $userSet->fetchPassword($username);
            if ($user && $userSet->verifyPassword($username, $password)){
                $_SESSION['login'] = $username;
                $_SESSION['userid'] = $userSet->fetchUserID($username);
                $_SESSION['usertype'] = $userSet->fetchUsertype($username);

                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid username and/or password";
            }
        } catch (Exception $e) {
            $error = "Login error: " . $e->getMessage();
        }
    }


//    if ($user === $username && $pass === $password) {
//        $_SESSION['login'] = $username;
//    }

//    if ($username == 'mujahid' && $password == 'password') {
//        $_SESSION['login'] = $username;
//    } elseif ($username == 'jamil' && $password == '123456') {
//        $_SESSION['login'] = $username;
//    } elseif ($username == 'rumaytha' && $password == 'rumaytha') {
//        $_SESSION['login'] = $username;
//    } elseif ($username == 'zakariya' && $password == 'zakariya') {
//        $_SESSION['login'] = $username;
//    } else {
//        echo "Error in username and/or password";
//    }
}

if (isset($_POST['logoutbutton'])) {
    echo "logout user";
    unset($_SESSION['login']);
    session_destroy();
}

require_once ('Views/login.phtml');