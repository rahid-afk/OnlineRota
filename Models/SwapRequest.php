<?php

class SwapRequest {
    protected $_swapId, $_requester_id, $_shift_id, $_requested_doctor_id, $_reason, $_message, $_status, $_created_at;

    public function __construct ($dbRow) {
        $this->_swapId = $dbRow['id'];
        $this->_requester_id = $dbRow['requested_id'];
        $this->_shift_id = $dbRow['shift_id'];
        $this->_requested_doctor_id = $dbRow['requested_doctor_id'];
        $this->_reason = $dbRow['reason'];
        $this->_message = $dbRow['message'];
        $this->_status = $dbRow['status'];
        $this->_created_at = $dbRow['created_at'];
    }

    public function getId(){
        return $this->_swapId;
    }

    public function getRequesterId(){
        return $this->_requester_id;
    }

    public function getShiftId(){
        return $this->_shift_id;
    }

    public function getRequestedDoctorId(){
        return $this->_requested_doctor_id;
    }

    public function getReason(){
        return $this->_reason;
    }

    public function getMessage(){
        return $this->_message;
    }

    public function getStatus(){
        return $this->_status;
    }

    public function getCreatedAt(){
        return $this->_created_at;
    }
}