<?php
require_once ('Models/Database.php');
require_once ('Models/Schedule.php');

class ScheduleSet
{
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function createShift($shiftStart, $shiftEnd, $userID, $shiftType)
    {
        $query = "INSERT INTO schedules (shift_start, shift_end, user_id, shift_type)
                    VALUES (:shiftStart, :shiftEnd, :user_id, :shift_type)";
        $stmt = $this->_dbHandle->prepare($query);

        $stmt->bindParam(':shiftStart', $shiftStart);
        $stmt->bindParam(':shiftEnd', $shiftEnd);
        $stmt->bindParam(':user_id', $userID);
        $stmt->bindParam(':shift_type', $shiftType);
        $stmt->execute();
    }

    public function getAllSchedules()
    {
        $query = "SELECT s.id, s.user_id, s.shift_type as shift_type_id, s.shift_start, s.shift_end, 
                         u.username, st.shift_type
                  FROM schedules s 
                  JOIN users u ON s.user_id = u.userid 
                  JOIN shift_type st ON s.shift_type = st.shift_id
                  ORDER BY s.shift_start";

        $statement = $this->_dbHandle->prepare($query);
        $statement->execute();

        $dataset = [];
        while ($row = $statement->fetch()) {
            $dataset[] = new Schedule($row);
        }
        return $dataset;
    }

    public function getAllSchedulesWithDetails()
    {
        $query = "SELECT s.id, s.user_id, s.shift_type as shift_type_id, s.shift_start, s.shift_end, 
                         u.username, st.shift_type
                  FROM schedules s 
                  JOIN users u ON s.user_id = u.userid 
                  JOIN shift_type st ON s.shift_type = st.shift_id
                  ORDER BY s.shift_start";

        $statement = $this->_dbHandle->prepare($query);
        $statement->execute();

        $schedules = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            // Debug: Check what we're getting from the database
            error_log("Database row: " . print_r($row, true));

            $schedules[] = [
                'id' => $row['id'],
                'title' => ($row['shift_type'] ?? 'Unknown') . ': ' . ($row['username'] ?? 'Unknown'),
                'start' => $row['shift_start'],
                'end' => $row['shift_end'],
                'doctor' => $row['username'] ?? 'Unknown',
                'shiftType' => $row['shift_type'] ?? 'Unknown', // This is now the actual shift type name
                'shiftTypeId' => $row['shift_type_id'], // The ID for reference
                'userId' => $row['user_id']
            ];
        }

        return $schedules;
    }

    public function getSchedulesForUser($userid)
    {
        $query = "SELECT s.id, s.user_id, s.shift_type as shift_type_id, s.shift_start, s.shift_end, 
                         u.username, st.shift_type
                  FROM schedules s 
                  JOIN users u ON s.user_id = u.userid 
                  JOIN shift_type st ON s.shift_type = st.shift_id
                  WHERE s.user_id = :userid
                  ORDER BY s.shift_start";

        $statement = $this->_dbHandle->prepare($query);
        $statement->bindParam(':userid', $userid);
        $statement->execute();

        $schedules = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $schedules[] = [
                'id' => $row['id'],
                'title' => $row['shift_type'],
                'start' => $row['shift_start'],
                'end' => $row['shift_end'],
                'doctor' => $row['username'],
                'shiftType' => $row['shift_type'],
                'shiftTypeId' => $row['shift_type_id'],
                'userId' => $row['user_id']
            ];
        }

        return $schedules;
    }

    public function getSchedulesByDateRange($startDate, $endDate)
    {
        $query = "SELECT s.id, s.user_id, s.shift_type as shift_type_id, s.shift_start, s.shift_end, 
                         u.username, st.shift_type
                  FROM schedules s 
                  JOIN users u ON s.user_id = u.userid 
                  JOIN shift_type st ON s.shift_type = st.shift_id
                  WHERE DATE(s.shift_start) BETWEEN :startDate AND :endDate
                  ORDER BY s.shift_start";

        $statement = $this->_dbHandle->prepare($query);
        $statement->bindParam(':startDate', $startDate);
        $statement->bindParam(':endDate', $endDate);
        $statement->execute();

        $dataset = [];
        while ($row = $statement->fetch()) {
            $dataset[] = new Schedule($row);
        }
        return $dataset;
    }

    public function deleteSchedule($scheduleID)
    {
        $query = "DELETE FROM schedules WHERE id = :scheduleID";
        $statement = $this->_dbHandle->prepare($query);
        $statement->bindParam(':scheduleID', $scheduleID);
        return $statement->execute();
    }

    public function transferShift($shiftId, $newUserId) {
        $query = "UPDATE schedules SET user_id = :new_user_id WHERE id = :shift_id";
        $statement = $this->_dbHandle->prepare($query);

        $statement->bindParam(':new_user_id', $newUserId);
        $statement->bindParam(':shift_id', $shiftId);

        return $statement->execute();
    }

    public function deleteShift($shiftId) {
        $query = "DELETE FROM schedules WHERE id = :shift_id";
        $statement = $this->_dbHandle->prepare($query);
        $statement->bindParam(':shift_id', $shiftId);

        return $statement->execute();
    }

    public function createShiftFromExisting($originalShiftId, $newUserId) {
        // Get the original shift details
        $query = "SELECT shift_type, shift_start, shift_end FROM schedules WHERE id = :shift_id";
        $statement = $this->_dbHandle->prepare($query);
        $statement->bindParam(':shift_id', $originalShiftId);
        $statement->execute();

        $originalShift = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$originalShift) {
            return false;
        }

        // Create new shift for the new user
        $insertQuery = "INSERT INTO schedules (user_id, shift_type, shift_start, shift_end) 
                        VALUES (:user_id, :shift_type, :shift_start, :shift_end)";
        $insertStatement = $this->_dbHandle->prepare($insertQuery);

        $insertStatement->bindParam(':user_id', $newUserId);
        $insertStatement->bindParam(':shift_type', $originalShift['shift_type']);
        $insertStatement->bindParam(':shift_start', $originalShift['shift_start']);
        $insertStatement->bindParam(':shift_end', $originalShift['shift_end']);

        return $insertStatement->execute();
    }

    public function getShiftById($shiftId) {
        $query = "SELECT s.*, u.username, st.shift_type as shift_type_name
                  FROM schedules s 
                  JOIN users u ON s.user_id = u.userid 
                  JOIN shift_type st ON s.shift_type = st.shift_id
                  WHERE s.id = :shift_id";

        $statement = $this->_dbHandle->prepare($query);
        $statement->bindParam(':shift_id', $shiftId);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteShiftById($shiftId) {
        $query = "DELETE FROM schedules WHERE id = :shift_id";
        $statement = $this->_dbHandle->prepare($query);
        $statement->bindParam(':shift_id', $shiftId);

        return $statement->execute();
    }

    public function updateShiftUser($shiftId, $newUserId) {
        $query = "UPDATE schedules SET user_id = :new_user_id WHERE id = :shift_id";
        $statement = $this->_dbHandle->prepare($query);

        $statement->bindParam(':new_user_id', $newUserId);
        $statement->bindParam(':shift_id', $shiftId);

        return $statement->execute();
    }
}