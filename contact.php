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

    // Handle POST request
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form input
            $this->name = trim($_POST['name'] ?? '');
            $this->surname = trim($_POST['surname'] ?? '');
            $this->email = trim($_POST['email'] ?? '');
            $this->message = trim($_POST['message'] ?? '');
            $this->categories = $_POST['categories'] ?? [];

            // Validate input
            $this->validate();

            // Save if no errors
            if (empty($this->errors)) {
                $this->saveMessage();
                $this->success = "Message sent successfully!";
                // Clear form values
                $this->name = $this->surname = $this->email = $this->message = '';
                $this->categories = [];
            }
        }
    }

    // Validate user input
    private function validate() {
        if (!$this->name) $this->errors[] = 'Name is required';
        if (!$this->surname) $this->errors[] = 'Surname is required';
        if (!$this->email || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Valid email is required';
        }
        if (!$this->message) $this->errors[] = 'Message is required';
    }

    // Save message to database
    private function saveMessage() {
        $catString = implode(', ', $this->categories);
        $stmt = $this->pdo->prepare("INSERT INTO contact_messages (name, surname, email, categories, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$this->name, $this->surname, $this->email, $catString, $this->message]);
    }
}

// Initialize database connection
$db = new Database();
$pdo = $db->connect();

// Create controller and handle request
$controller = new ContactController($pdo);
$controller->handleRequest();
?>