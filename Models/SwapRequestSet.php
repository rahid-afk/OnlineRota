<?php
require_once('Models/Database.php');

class SwapRequestSet {
    protected $_dbHandle, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function createSwapRequest($requesterId, $shiftId, $requestedDoctorId, $reason = '', $message = '') {
        $query = "INSERT INTO swap_requests (requester_id, shift_id, requested_doctor_id, reason, message) 
                  VALUES (:requester_id, :shift_id, :requested_doctor_id, :reason, :message)";

        $statement = $this->_dbHandle->prepare($query);
        $statement->bindParam(':requester_id', $requesterId);
        $statement->bindParam(':shift_id', $shiftId);
        $statement->bindParam(':requested_doctor_id', $requestedDoctorId);
        $statement->bindParam(':reason', $reason);
        $statement->bindParam(':message', $message);

        return $statement->execute();
    }

    public function getRequestsForUser($userId) {
        $query = "SELECT sr.*, 
                         u_requester.username as requester_name,
                         s.shift_start, s.shift_end,
                         st.shift_type
                  FROM swap_requests sr
                  JOIN users u_requester ON sr.requester_id = u_requester.userid
                  JOIN schedules s ON sr.shift_id = s.id
                  JOIN shift_type st ON s.shift_type = st.shift_id
                  WHERE sr.requested_doctor_id = :user_id
                  ORDER BY sr.created_at DESC";

        $statement = $this->_dbHandle->prepare($query);
        $statement->bindParam(':user_id', $userId);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingRequestsCount($userId) {
        $query = "SELECT COUNT(*) as count 
                  FROM swap_requests 
                  WHERE requested_doctor_id = :user_id AND status = 'pending'";

        $statement = $this->_dbHandle->prepare($query);
        $statement->bindParam(':user_id', $userId);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return isset($result['count']) ? $result['count'] : 0;
    }

    public function updateRequestStatus($requestId, $status) {
        $query = "UPDATE swap_requests 
                  SET status = :status, created_at = CURRENT_TIMESTAMP 
                  WHERE id = :request_id";

        $statement = $this->_dbHandle->prepare($query);
        $statement->bindParam(':status', $status);
        $statement->bindParam(':request_id', $requestId);

        return $statement->execute();
    }

    public function getRequestById($requestId) {
        $query = "SELECT sr.*, 
                         u_requester.username as requester_name,
                         u_requested.username as requested_name,
                         s.shift_start, s.shift_end,
                         st.shift_type
                  FROM swap_requests sr
                  JOIN users u_requester ON sr.requester_id = u_requester.userid
                  JOIN users u_requested ON sr.requested_doctor_id = u_requested.userid
                  JOIN schedules s ON sr.shift_id = s.id
                  JOIN shift_type st ON s.shift_type = st.shift_id
                  WHERE sr.id = :request_id";

        $statement = $this->_dbHandle->prepare($query);
        $statement->bindParam(':request_id', $requestId);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getSwapRequestWithShiftDetails($requestId) {
        $query = "SELECT sr.*, s.user_id as original_user_id, s.shift_type, s.shift_start, s.shift_end 
              FROM swap_requests sr 
              JOIN schedules s ON sr.shift_id = s.id 
              WHERE sr.id = :request_id";

        $statement = $this->_dbHandle->prepare($query);
        $statement->bindParam(':request_id', $requestId);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}