<?php

class Database
{
    /**
     * @var Database
     */
    protected static $_dbInstance = null;

    /**
     * @var PDO
     */
    protected $_dbHandle;

    /**
     * @return Database
     */
    public static function getInstance() {
//        $username ='onlinerota_wrongtable';
//        $password = '8473191bd982fe430da634302589e17bbcea3527';
//        $host = 'gu525.h.filess.io';
//        $dbName = 'onlinerota_wrongtable';
//        $port = '61002';


        // Connect with PuTTy first before using
        $username ='medinwep_rahid';
        $password = 'Polyjuice23';
        $host = '127.0.0.1';
        $dbName = 'medinwep_onlinerota';
        $port = '5522';


        if(self::$_dbInstance === null) { //checks if the PDO exists
            // creates new instance if not, sending in connection info
            self::$_dbInstance = new self($username, $password, $host, $dbName, $port);
        }

        return self::$_dbInstance;
    }

    /**
     * @param $username
     * @param $password
     * @param $host
     * @param $database
     * @param $port
     */
    private function __construct($username, $password, $host, $database, $port) {
        try {
//            $this->_dbHandle = new PDO("mysql:host=$host;dbname=$database",  $username, $password); // creates the database handle with connection info
//            $this->_dbHandle = new PDO("mysql://onlinerota_wrongtable:8473191bd982fe430da634302589e17bbcea3527@gu525.h.filess.io:61002/onlinerota_wrongtable"); // creates the database handle with connection info
            // Include port in the DSN
//            $dsn = "mysql:host=$host;port=$port;dbname=$database";
//            $this->_dbHandle = new PDO($dsn, $username, $password);
            $this->_dbHandle = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
        }
        catch (PDOException $e) { // catch any failure to connect to the database
            echo $e->getMessage();
        }
    }

    /**
     * @return PDO
     */
    public function getdbConnection() {
        return $this->_dbHandle; // returns the PDO handle to be used                                        elsewhere
    }

    public function __destruct() {
        $this->_dbHandle = null; // destroys the PDO handle when nolonger needed                                        longer needed
    }
}