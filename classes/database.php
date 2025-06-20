<?php
class Database {
    // DB connection details
    private $host = "localhost";
    private $db_name = "customer_reviews";
    private $username = "root";
    private $password = "";
    public $conn; // Holds PDO connection object
    