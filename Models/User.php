<?php

class User {
    protected $_userid, $_username, $_password, $_usertype;

    public function __construct($dbRow){
        $this->_userid = $dbRow['userid'];
        $this->_username = $dbRow['username'];
        $this->_password = $dbRow['password'];
        $this->_usertype = $dbRow['usertype'];
    }

    public function getUserID(){
        return $this->_userid;
    }
    public function getUsername(){
        return $this->_username;
    }
    public function getPassword(){
        return $this->_password;
    }
    public function getUsertype(){
        return $this->_usertype;
    }
}