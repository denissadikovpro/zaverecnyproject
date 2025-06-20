<?php
// Include database connection class
require_once 'classes/database.php';

class ReviewHandler {
    // Database connection and form data
    private $pdo;
    public $name = '';
    public $comment = '';
    public $stars = '';
    public $category = 'Apartments';
    public $errors = ['name' => '', 'comment' => '', 'stars' => ''];

    // Constructor receives PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Handle review form submission
    public function handleForm() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get and sanitize form inputs
            $this->name = trim($_POST['name'] ?? '');
            $this->comment = trim($_POST['comment'] ?? '');
            $this->stars = $_POST['stars'] ?? '';
            $this->category = $_POST['category'] ?? 'Apartments';

            // Validate name input
            if (!$this->name || !preg_match('/^[a-zA-Z0-9_ ]+$/u', $this->name)) {
                $this->errors['name'] = 'Please enter a valid name';
            }

            // Validate comment input
            if (!$this->comment) {
                $this->errors['comment'] = 'Please enter a comment';
            }

            // Validate star rating input
            if (!in_array($this->stars, ['1', '2', '3', '4', '5'])) {
                $this->errors['stars'] = 'Please select a rating';
            }

            // Validate category input
            if (!in_array($this->category, ['Apartments', 'Food', 'Car rental', 'Shopping', 'Tours'])) {
                $this->category = 'Apartments';
            }

            // If no validation errors
            if (empty(array_filter($this->errors))) {
                // Insert review into database
                $stmt = $this->pdo->prepare("INSERT INTO reviews (name, comment, stars, category) VALUES (?, ?, ?, ?)");
                $stmt->execute([$this->name, $this->comment, $this->stars, $this->category]);

                // Clear input fields
                $this->name = $this->comment = $this->stars = '';
            }
        }
    }




<!-- HTML starts here -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <title>Reviews</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Additional CSS Files -->
  <link rel="stylesheet" href="assets/css/fontawesome.css">
  <link rel="stylesheet" href="assets/css/templatemo-plot-listing.css">
  <link rel="stylesheet" href="assets/css/animated.css">
  <link rel="stylesheet" href="assets/css/owl.css">
</head>

<body>

<!-- Include header -->
<?php include_once "assets/parts/header.php" ?>

<!-- banner section starts -->
  <div class="page-heading">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <div class="top-text header-text">
            <h6>Share your thoughts with us</h6>
            <h2>Feel free to leave a review and let us know what you think about our services</h2>
          </div>
        </div>
      </div>
    </div>
  </div>
<!-- banner section ends -->




<?php include_once "assets/parts/footer.php" ?>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/owl-carousel.js"></script>
<script src="assets/js/animation.js"></script>
<script src="assets/js/imagesloaded.js"></script>
<script src="assets/js/custom.js"></script>

</body>
</html>
