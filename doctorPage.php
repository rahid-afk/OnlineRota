<?php
require_once ('Models/ScheduleSet.php');
require_once ('Models/UserSet.php');
require_once ('Models/ShiftTypeSet.php');

$view = new stdClass();
$view->pageTitle = "Schedule";

$scheduleSet = new ScheduleSet();
$userSet = new UserSet();

$currentUsername = isset($_SESSION['login']) ? $_SESSION['login'] : null;
$currentUserID = null;

if ($currentUsername) {
    $currentUserID = $userSet->getUserId($currentUsername);
}

if ($currentUserID){
    $view->schedules = $scheduleSet->getSchedulesForUser($currentUserID);
    //If i want to show all shifts use line below
//    $view->schedules = $scheduleSet->getAllSchedulesWithDetails();
} else {
    $view->schedules = [];
}

//$view->scheduleSet = $scheduleSet->getAllSchedules();

require_once ('Views/doctorPage.phtml');