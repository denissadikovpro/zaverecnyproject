<?php
class Database {
    // DB connection details
    private $host = "localhost";
    private $db_name = "customer_reviews";
    private $username = "root";
    private $password = "";
    public $conn; // Holds PDO connection object

    public function connect() {
        $this->conn = null;
        try {  // Create new PDO connection
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable PDO error exceptions
        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage(); // Print connection error
        }
        return $this->conn; // Return PDO connection object
    }
}
