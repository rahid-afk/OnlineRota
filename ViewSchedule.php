<?php
require_once('Models/ScheduleSet.php');
require_once('Models/UserSet.php');
require_once('Models/ShiftTypeSet.php');

$view = new stdClass();
$view->pageTitle = "View Schedule";

$scheduleSet = new ScheduleSet();
$userSet = new UserSet();
$shiftTypeSet = new ShiftTypeSet();

// You'll need to create this method in ScheduleSet to get all schedules with related data
$view->allSchedules = $scheduleSet->getAllSchedulesWithDetails();

require_once('Views/ViewSchedule.phtml');