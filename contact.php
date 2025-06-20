<?php
// Include database connection class
require_once 'classes/Database.php';

// ContactController class
class ContactController {
    // Properties
    private $pdo;
    public $name = '';
    public $surname = '';
    public $email = '';
    public $message = '';
    public $categories = [];
    public $errors = [];
    public $success = '';

    // Constructor - assign PDO
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
