<?php

class ShiftType {
    protected $_shiftID, $_shiftType;

    public function __construct ($dbRow) {
        $this->_shiftID = $dbRow['shift_id'];
        $this->_shiftType = $dbRow['shift_type'];
    }

    public function getShiftID(){
        return $this->_shiftID;
    }

    public function getShiftType(){
        return $this->_shiftType;
    }
}