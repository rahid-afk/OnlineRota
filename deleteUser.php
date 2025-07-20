<?php
require_once ('Models/UserSet.php');

$view = new stdClass();
$view->pageTitle = 'Delete User';

$userSet = new UserSet();
$userID = $_GET['id'];
$view->userSet = $userSet->deleteUser($userID);

require_once ('Views/deleteUser.phtml');