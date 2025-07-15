<?php
class Schedule {
    protected $_schedule_id, $_shiftStart, $_shiftEnd, $_user_id, $_shift_type;

    public function __construct($dbRow){
        $this->_schedule_id = $dbRow['id'];
        $this->_shiftStart = $dbRow['date'];
        $this->_shiftEnd = $dbRow['date'];
        $this->_user_id = $dbRow['userid'];
        $this->_shift_type = $dbRow['shift_type'];
    }

    public function getScheduleID(){
        return $this->_schedule_id;
    }

    public function getShiftStart(){
        return $this->_shiftStart;
    }

    public function getShiftEnd(){
        return $this->_shiftEnd;
    }

    public function getUserID(){
        return $this->_user_id;
    }

    public function getShiftType(){
        return $this->_shift_type;
    }
}