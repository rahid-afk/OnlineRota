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

    public function fetchUserID($username){
        $query = "SELECT userid FROM users WHERE username=:username";
        $statement = $this->_dbHandle->prepare($query);

        $statement->bindParam(':username', $username);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result){
            return $result['userid'];
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

    /**
     * Verify password for a user
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function verifyPassword($username, $password) {
        $query = "SELECT password FROM users WHERE username = :username";
        $statement = $this->_dbHandle->prepare($query);
        $statement->bindParam(':username', $username);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $storedPassword = $result['password'];

            // Check if password is hashed (starts with $2y$ for bcrypt)
            if (password_get_info($storedPassword)['algo'] !== null) {
                // Password is hashed, use password_verify
                return password_verify($password, $storedPassword);
            } else {
                // Password is stored in plain text (for backwards compatibility)
                return $password === $storedPassword;
            }
        }
        return false;
    }

    /**
     * Create new user with hashed password
     * @param string $username
     * @param string $password
     * @param int $userType
     * @return bool
     */
    public function createUserWithHash($username, $password, $userType) {
        // Check if username already exists
        if ($this->getUserByUsername($username)) {
            throw new Exception("Username already exists");
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, password, usertype)
                    VALUES (:username, :password, :userType)";
        $stmt = $this->_dbHandle->prepare($query);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':userType', $userType);

        return $stmt->execute();
    }

    /**
     * Update user password (with hashing)
     * @param string $username
     * @param string $newPassword
     * @return bool
     */
    public function updatePassword($username, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $query = "UPDATE users SET password = :password WHERE username = :username";
        $stmt = $this->_dbHandle->prepare($query);

        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':username', $username);

        return $stmt->execute();
    }
}
