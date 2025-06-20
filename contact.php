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


<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Improves loading performance for Google Fonts by preconnecting -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <title>Contact us</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-plot-listing.css">
    <link rel="stylesheet" href="assets/css/animated.css">
    <link rel="stylesheet" href="assets/css/owl.css">

  </head>

<body>


<!-- header section starts -->
<?php include_once "assets/parts/header.php" ?>
<!-- header section ends -->


<!-- contact banner section starts -->
  <div class="page-heading">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <div class="top-text header-text">
            <h6>Keep in touch with us</h6>
            <h2>Feel free to send us a message about your business needs</h2>
          </div>
        </div>
      </div>
    </div>
  </div>
<!-- contact banner section ends -->



<section class="container py-5">

    <!-- Success message alert -->
    <?php if ($controller->success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($controller->success) ?></div>
    <?php endif; ?>

    <!-- Display form errors -->
    <?php foreach ($controller->errors as $err): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
    <?php endforeach; ?>

    <!-- Contact form starts here -->
    <form method="POST">
        <div class="row">
            <!-- Name input field -->
            <div class="col-md-6 mb-3">
                <input type="text" name="name" class="form-control" placeholder="Name"
                       value="<?= htmlspecialchars($controller->name) ?>">
            </div>

            <!-- Surname input field -->
            <div class="col-md-6 mb-3">
                <input type="text" name="surname" class="form-control" placeholder="Surname"
                       value="<?= htmlspecialchars($controller->surname) ?>">
            </div>
        </div>

        <!-- Email input field -->
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Your Email"
                   value="<?= htmlspecialchars($controller->email) ?>">
        </div>

        <!-- Category checkboxes -->
        <div class="mb-3">
            <?php
            $options = ['Cars', 'Apartments', 'Shopping', 'Food & Life', 'Traveling'];
            foreach ($options as $opt): ?>
                <label class="me-3">
                    <input type="checkbox" name="categories[]" value="<?= $opt ?>"
                        <?= in_array($opt, $controller->categories) ? 'checked' : '' ?>>
                    <?= $opt ?>
                </label>
            <?php endforeach; ?>
        </div>

        <!-- Message textarea -->
        <div class="mb-3">
            <textarea name="message" class="form-control"
                      placeholder="Message"><?= htmlspecialchars($controller->message) ?></textarea>
        </div>

        <!-- Submit button -->
        <button class="btn btn-primary">
            <i class="fa fa-paper-plane"></i> Send Message
        </button>
    </form>
</section>


<!-- footer section starts -->
<?php include_once "assets/parts/footer.php" ?>
<!-- footer section ends -->


  <!-- Scripts -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/animation.js"></script>
  <script src="assets/js/imagesloaded.js"></script>
  <script src="assets/js/custom.js"></script>
  
</body>
</html>
