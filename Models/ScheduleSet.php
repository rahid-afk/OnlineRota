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

    public function createShift($date, $userID, $shiftType){
        $query = "INSERT INTO schedules (date, user_id, shift_type)
                    VALUES (:date, :user_id, :shift_type)";
        $stmnt = $this->_dbHandle->prepare($query);

        $stmnt->bindParam(':date', $date);
        $stmnt->bindParam(':user_id', $userID);
        $stmnt->bindParam(':shift_type', $shiftType);
        $stmnt->execute();
    }
}