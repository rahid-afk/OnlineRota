<?php
require_once ('Models/Database.php');
require_once ('Models/UserType.php');

class UserTypeSet {
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function getAllUserTypes(){
        $query = "SELECT * FROM usertype";
        $statement = $this->_dbHandle->prepare($query);
        $statement->execute();

        $dataset = [];
        while ($row = $statement->fetch()) {
            $dataset[] = new UserType($row);
        }
        return $dataset;
    }

    public function getUserTypeIDFromUserType($userType){
        $query = "SELECT id FROM usertype WHERE userTypeName=:userType";
        $statement = $this->_dbHandle->prepare($query);

        $statement->bindParam(':userType', $userType);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result){
            return $result['id'];
        }
    }
}