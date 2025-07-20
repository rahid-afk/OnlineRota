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

    public function createUser($username, $password, $userType){
        $query = "INSERT INTO users (username, password, usertype)
                    VALUES (:username, :password, :userType)";
        $stmt = $this->_dbHandle->prepare($query);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':userType', $userType);
        $stmt->execute();
    }

    public function deleteUser($id){
        $query = "DELETE FROM users WHERE userid=:id";
        $statement = $this->_dbHandle->prepare($query);

        $statement->bindParam('id', $id);
        $statement->execute();
    }

    public function updateUser($id, $name, $pass, $usertype){
        $query = "UPDATE users 
                  SET username = :name, password = :pass, usertype = :usertype
                  WHERE userid = :id";
        $statement = $this->_dbHandle->prepare($query);

        $statement->bindParam(':name', $name);
        $statement->bindParam(':pass', $pass);
        $statement->bindParam(':usertype', $usertype);
        $statement->bindParam(':id', $id);
        $statement->execute();
    }

    public function selectWithID($id){
        $query = "SELECT * FROM users WHERE userid =:id";
        $statement = $this->_dbHandle->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();

        $dataset = [];
        while ($row = $statement->fetch()){
            $dataset[] = new User($row);
        }
        return $dataset;
    }
}
