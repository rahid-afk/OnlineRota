<?php
require_once ('Models/Database.php');
require_once ('Models/Schedule.php');

class ScheduleSet {
    protected $_dbHandle, $_dbInstance;
    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function createShift($shiftStart, $shiftEnd,  $userID, $shiftType){
        $query = "INSERT INTO schedules (shift_start, shift_end, user_id, shift_type)
                    VALUES (:shiftStart, :shiftEnd, :user_id, :shift_type)";
        $stmt = $this->_dbHandle->prepare($query);

        $stmt->bindParam(':shiftStart', $shiftStart);
        $stmt->bindParam(':shiftEnd', $shiftEnd);
        $stmt->bindParam(':user_id', $userID);
        $stmt->bindParam(':shift_type', $shiftType);
        $stmt->execute();
    }
}