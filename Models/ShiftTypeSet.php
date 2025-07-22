<?php
require_once ('Models/Database.php');
require_once ('Models/ShiftType.php');

class ShiftTypeSet {
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function getShiftID($shiftType){
        $query = "SELECT shift_id FROM shift_type WHERE shift_type=:shiftType";
        $statement = $this->_dbHandle->prepare($query);

        $statement->bindParam(':shiftType', $shiftType);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result){
            return $result['shift_id'];
        }
    }

    public function getAllShiftTypes()
    {
        $query = "SELECT * FROM shift_type";
        $statement = $this->_dbHandle->prepare($query);
        $statement->execute();

        $dataset = [];
        while ($row = $statement->fetch()) {
            $dataset[] = new ShiftType($row);
        }
        return $dataset;
    }

    public function getShiftTypeByID($shiftID){
        $query = "SELECT shift_type FROM shift_type WHERE shift_id=:shiftID";
        $statement = $this->_dbHandle->prepare($query);

        $statement->bindParam(':shiftID', $shiftID);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result){
            return $result['shift_type'];
        }
    }
}