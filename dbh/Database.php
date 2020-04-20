<?php

class Database{
    private $conn;
    private $host;
    private $db;
    private $user;
    private $pwd;
    
    // initialize the instance variables
    function __construct(){
        $this->host = "localhost:3306";
        $this->db = "jwtdb";
        $this->user = "root";
        $this->pwd ="root";
    }

    // connect with database and return the connection
    public function connect(){
        $this->conn = new mysqli($this->host,$this->user,$this->pwd,$this->db);
        if ($this->conn->connect_error){
            die('Connection failed: '. $this->conn->connect_errno);
        }
        return $this->conn;
    
    }
    // disconnect the connection with database
    public function disconnect(){
        $this->conn->close();
    }

}
