<?php
class UserType {
    protected $_userID, $_userType;

    public function  __construct($dbRow){
        $this->_userID = $dbRow['id'];
        $this->_userType = $dbRow['userTypeName'];
    }

    public function getUserID(){
        return $this->_userID;
    }

    public function getUserType(){
        return $this->_userType;
    }
}