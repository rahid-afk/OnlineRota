<?php
session_start();
require_once('Models/ScheduleSet.php');
require_once('Models/UserSet.php');
require_once('Models/SwapRequestSet.php');

$view = new stdClass();
$view->pageTitle = "Notifications";

// Check if user is logged in
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$userSet = new UserSet();
$scheduleSet = new ScheduleSet();
$swapRequestSet = new SwapRequestSet();

$currentUsername = $_SESSION['login'];
$currentUserId = $userSet->getUserId($currentUsername);

// Handle approve/deny actions
if (isset($_POST['action']) && isset($_POST['request_id'])) {
    $requestId = $_POST['request_id'];
    $action = $_POST['action'];

    if ($action === 'approve' || $action === 'deny') {
        $status = ($action === 'approve') ? 'approved' : 'denied';
        $success = $swapRequestSet->updateRequestStatus($requestId, $status);

        if ($success) {
            if ($action === 'approve') {
                // Get the swap request details to access shift information
                $swapRequestDetails = $swapRequestSet->getSwapRequestWithShiftDetails($requestId);

                if ($swapRequestDetails) {
                    // Store shift details as variables
                    $shift_id = $swapRequestDetails['shift_id'];
                    $requested_doctor_id = $swapRequestDetails['requested_doctor_id'];

                    // Update the shift to assign it to the doctor who approved the swap
                    $shiftUpdated = $scheduleSet->updateShiftUser($shift_id, $requested_doctor_id);

                    if ($shiftUpdated) {
                        $_SESSION['success'] = "Shift swap request approved successfully! The shift has been transferred to your schedule.";
                    } else {
                        $_SESSION['error'] = "Failed to transfer shift assignment.";
                    }
                } else {
                    $_SESSION['error'] = "Could not retrieve shift details for swap.";
                }
            } else {
                $_SESSION['success'] = "Shift swap request denied.";
            }
        } else {
            $_SESSION['error'] = "Failed to update request status.";
        }

        // Redirect to prevent form resubmission
        header("Location: notification.php");
        exit;
    }
}

// Get all swap requests for current user
$view->swapRequests = $swapRequestSet->getRequestsForUser($currentUserId);

// Get pending requests count
$view->pendingCount = $swapRequestSet->getPendingRequestsCount($currentUserId);

require_once('Views/notification.phtml');
?>