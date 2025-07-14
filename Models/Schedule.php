<?php
class Schedule {
    protected $_schedule_id, $_date, $_user_id, $_shift_type;

    public function __construct($dbRow){
        $this->_schedule_id = $dbRow['id'];
        $this->_date = $dbRow['date'];
        $this->_user_id = $dbRow['userid'];
        $this->_shift_type = $dbRow['shift_type'];
    }

    public function getScheduleID(){
        return $this->_schedule_id;
    }

    public function getDate(){
        return $this->_date;
    }

    public function getUserID(){
        return $this->_user_id;
    }

    public function getShiftType(){
        return $this->_shift_type;
    }
}