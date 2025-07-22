<?php
session_start(); // Add session start
require_once('Models/ScheduleSet.php');
require_once('Models/UserSet.php');
require_once('Models/ShiftTypeSet.php');

$view = new stdClass();
$view->pageTitle = "View Schedule";

// Handle AJAX delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_shift') {
    header('Content-Type: application/json');

    if (!isset($_POST['schedule_id']) || empty($_POST['schedule_id'])) {
        echo json_encode(['success' => false, 'message' => 'Schedule ID is required']);
        exit();
    }

    try {
        $scheduleSet = new ScheduleSet();
        $success = $scheduleSet->deleteShift($_POST['schedule_id']);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Shift deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete shift']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit();
}

try {
    $scheduleSet = new ScheduleSet();
    $userSet = new UserSet();
    $shiftTypeSet = new ShiftTypeSet();

    // Get all schedules with details
    $view->allSchedules = $scheduleSet->getAllSchedulesWithDetails();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    $view->allSchedules = [];
}

require_once('Views/ViewSchedule.phtml');