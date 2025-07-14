<?php
require_once ('Models/Database.php');
require_once ('Models/User.php');


class UserSet {

    protected $_dbHandle, $_dbInstance;
    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function fetchUsername($username){
        $query = "SELECT username FROM users WHERE username=:username";
        $statement = $this->_dbHandle->prepare($query);

        $statement->bindParam(':username', $username);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result){
            return $result['username'];
        }
    }

    public function fetchPassword($username){
        $query = "SELECT password FROM users WHERE username=:username";
        $statement = $this->_dbHandle->prepare($query);

        $statement->bindParam(':username', $username);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result){
            return $result['password'];
        }
    }

    public function fetchUsertype($username){
        $query = "SELECT usertype FROM users WHERE username=:username";
        $statement = $this->_dbHandle->prepare($query);

        $statement->bindParam(':username', $username);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result){
            return $result['usertype'];
        }
    }

    public function getAllUsers(){
        $query = "SELECT * FROM users";

        $statement = $this->_dbHandle->prepare($query);
        $statement->execute();

        $dataset = [];
        while ($row = $statement->fetch()){
            $dataset[] = new User($row);
        }
        return $dataset;
    }

    public function getUserId($username){
        $query = "SELECT userid FROM users WHERE username=:username";
        $statement = $this->_dbHandle->prepare($query);

        $statement->bindParam(':username', $username);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result){
            return $result['userid'];
        }
    }

    public function getShiftType(){
        $query = "SELECT shift_type FROM users WHERE username=:username";
        $statement = $this->_dbHandle->prepare($query);

        $statement->bindParam(':username', $username);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result){
            return $result['username'];
        }
    }

    public function selectAllDoctors(){
        $query = "SELECT * FROM users WHERE usertype = 2";
        $statement = $this->_dbHandle->prepare($query);
        $statement->execute();

        $dataset = [];
        while ($row = $statement->fetch()){
            $dataset[] = new User($row);
        }
        return $dataset;
    }
}
