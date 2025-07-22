<?php
session_start();

// Check if user is manager
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] != 1) {
    header("Location: login.php");
    exit();
}

require_once('Models/ScheduleSet.php');
require_once('Models/UserSet.php');
require_once('Models/ShiftTypeSet.php');

$view = new stdClass();
$view->pageTitle = "Edit Shift";

$scheduleSet = new ScheduleSet();
$userSet = new UserSet();
$shiftTypeSet = new ShiftTypeSet();

$errorMessage = '';
$successMessage = '';

// Get schedule ID from URL
$scheduleId = isset($_GET['id']) ? $_GET['id'] : null;

if (!$scheduleId) {
    header("Location: ViewSchedule.php");
    exit();
}

// Get the schedule details
$schedule = $scheduleSet->getScheduleById($scheduleId);

if (!$schedule) {
    header("Location: ViewSchedule.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateShift'])) {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $shiftType = isset($_POST['shiftType']) ? $_POST['shiftType'] : ''; //Returns number
    $shiftTypeName = $shiftTypeSet->getShiftTypeByID($shiftType);
    $shiftStart = isset($_POST['shiftStart']) ? $_POST['shiftStart'] : '';
    $shiftEnd = isset($_POST['shiftEnd']) ? $_POST['shiftEnd'] : '';

    if (empty($username) || empty($shiftType) || empty($shiftStart) || empty($shiftEnd)) {
        $errorMessage = "All fields are required.";
    } else {
        try {
            $userid = $userSet->getUserId($username);
            $shiftId = $shiftTypeSet->getShiftID($shiftType);

            if (!$userid || !$shiftId) {
                $errorMessage = "Invalid user or shift type selected.";
            } else {
                $success = $scheduleSet->updateShift($scheduleId, $shiftStart, $shiftEnd, $userid, $shiftId);

                if ($success) {
                    $successMessage = "Shift updated successfully!";
                    // Refresh the schedule data
                    $schedule = $scheduleSet->getScheduleById($scheduleId);
                } else {
                    $errorMessage = "Failed to update shift.";
                }
            }
        } catch (Exception $e) {
            $errorMessage = "Error updating shift: " . $e->getMessage();
        }
    }
}

// Get all doctors and shift types for dropdowns
$view->doctors = $userSet->selectAllDoctors();
$view->shiftTypes = $shiftTypeSet->getAllShiftTypes();
$view->schedule = $schedule;
$view->errorMessage = $errorMessage;
$view->successMessage = $successMessage;

require_once('Views/EditShift.phtml');