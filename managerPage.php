<?php

require_once('Models/UserSet.php');
require_once('Models/ShiftTypeSet.php');
require_once('Models/ScheduleSet.php');

$view = new stdClass();
$view->pageTitle = "Manager Page";


$userSet = new UserSet();
$shiftTypeSet = new ShiftTypeSet();
$scheduleSet = new ScheduleSet();


if (isset($_POST['createShift'])) {
    // Validate that all required fields are present
    if (isset($_POST['shiftStart']) && isset($_POST['shiftEnd']) && isset($_POST['username']) && isset($_POST['shiftType']) &&
        !empty($_POST['shiftStart']) && !empty($_POST['shiftEnd']) && !empty($_POST['username']) && !empty($_POST['shiftType'])) {

        $username = $_POST['username'];
        $userid = $userSet->getUserId($username);
        $shiftType = $_POST['shiftType'];
        $shiftId = $shiftTypeSet->getShiftID($shiftType);
        $shiftStart = $_POST['shiftStart'];
        $shiftEnd = $_POST['shiftEnd'];

        $view->scheduleSet = $scheduleSet->createShift($shiftStart, $shiftEnd, $userid, $shiftId);
    }
} elseif (isset($_POST['createUser'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $usertype = $_POST['usertype'];
    $view->deliveryUserSet = $userSet->createUser($username, $password, $usertype);
} elseif (isset($_POST['viewRecordsBtn'])) {
    $view->deliveryPointSet = $scheduleSet->getAllSchedules();
} elseif (isset($_POST['viewUsersBtn'])) {
    $view->userSet = $userSet->getAllUsers();
}

$view->userSet = $userSet->selectAllDoctors();
$view->shiftTypeSet = $shiftTypeSet->getAllShiftTypes();

require_once('Views/managerpage.phtml');