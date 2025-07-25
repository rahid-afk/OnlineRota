<?php
session_start();
require_once('Models/ScheduleSet.php');
require_once('Models/UserSet.php');
require_once('Models/ShiftTypeSet.php');

$view = new stdClass();
$view->pageTitle = "Shift Swap Request";

$scheduleSet = new ScheduleSet();
$userSet = new UserSet();

// Check if user is logged in
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$currentUsername = $_SESSION['login'];
$currentUserId = $userSet->getUserId($currentUsername);

// Get shift ID from URL parameter
$shiftId = isset($_GET['id']) ? $_GET['id'] : null;

if (!$shiftId) {
    $_SESSION['error'] = "No shift specified for swap request.";
    header("Location: doctorPage.php");
    exit;
}

// Get the shift details
$view->shift = null;
$view->user = null;
$allSchedules = $scheduleSet->getAllSchedulesWithDetails();

foreach ($allSchedules as $schedule) {
    if ($schedule['id'] == $shiftId) {
        $view->shift = $schedule;
        break;
    }
}

if (!$view->shift) {
    $_SESSION['error'] = "Shift not found.";
    header("Location: doctorPage.php");
    exit;
}

// Check if this is the user's shift
if ($view->shift['user_id'] != $currentUserId) {
    $_SESSION['error'] = "You can only request swaps for your own shifts.";
    header("Location: index.php");
    exit;
}

// Get all doctors except the current user for swap selection
$allDoctors = $userSet->selectAllDoctors();
$view->availableDoctors = [];

foreach ($allDoctors as $doctor) {
    if ($doctor->getUsername() !== $currentUsername) {
        $view->availableDoctors[] = $doctor;
    }
}

// Handle swap request submission
if (isset($_POST['submitSwapRequest'])) {
    $swapWithUsername = isset($_POST['swapWithDoctor']) ? $_POST['swapWithDoctor'] : '';
    $swapReason = isset($_POST['swapReason']) ? $_POST['swapReason'] : '';
    $swapMessage = isset($_POST['swapMessage']) ? $_POST['swapMessage'] : '';

    if (empty($swapWithUsername)) {
        $view->error = "Please select a doctor to swap with.";
    } else {
        $swapWithUserId = $userSet->getUserId($swapWithUsername);

        if ($swapWithUserId) {
            // Create the swap request
            require_once('Models/SwapRequestSet.php');
            $swapRequestSet = new SwapRequestSet();

            $success = $swapRequestSet->createSwapRequest(
                $currentUserId,
                $shiftId,
                $swapWithUserId,
                $swapReason,
                $swapMessage
            );

            if ($success) {
                $_SESSION['success'] = "Swap request sent to " . htmlspecialchars($swapWithUsername) . " successfully!";
                header("Location: doctorPage.php");
                exit;
            } else {
                $view->error = "Failed to send swap request. Please try again.";
            }
        } else {
            $view->error = "Selected doctor not found.";
        }
    }
}

require_once('Views/shiftSwap.phtml');
?>