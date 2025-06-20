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

    // Get all reviews from database
    public function fetchReviews() {
        $stmt = $this->pdo->query("SELECT * FROM reviews ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Initialize database and handler
$db = new Database();
$pdo = $db->connect();
$reviewHandler = new ReviewHandler($pdo);
$reviewHandler->handleForm();
$reviews = $reviewHandler->fetchReviews();
?>


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

<section class="container py-5">
  <!-- Review form title -->
  <h2 class="mb-4">Leave a Review</h2>

  <!-- Review form start -->
  <form method="POST">
    <!-- Name input -->
    <div class="mb-3">
      <label class="form-label">Your Name or Username:</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($reviewHandler->name) ?>">
      <div style="color:red;"><?= $reviewHandler->errors['name'] ?></div>
    </div>

    <!-- Comment textarea -->
    <div class="mb-3">
      <label class="form-label">Your Comment:</label>
      <textarea name="comment" class="form-control"><?= htmlspecialchars($reviewHandler->comment) ?></textarea>
      <div style="color:red;"><?= $reviewHandler->errors['comment'] ?></div>
    </div>

    <!-- Category selection -->
    <div class="mb-3">
      <label class="form-label">Category:</label>
      <select name="category" class="form-control">
        <?php
        $options = ['Apartments', 'Food', 'Car rental', 'Shopping', 'Tours'];
        foreach ($options as $opt):
        ?>
        <!-- Category option -->
        <option value="<?= $opt ?>" <?= $reviewHandler->category === $opt ? 'selected' : '' ?>><?= $opt ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- Star rating input -->
    <div class="mb-3">
      <label class="form-label">Rating:</label><br>
      <div id="stars-wrapper">
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <!-- Star radio button -->
          <input type="radio" id="star<?= $i ?>" name="stars" value="<?= $i ?>" style="display: none" <?= ($reviewHandler->stars == $i) ? 'checked' : '' ?>>
          <label for="star<?= $i ?>" class="star" data-value="<?= $i ?>">&#9733;</label>
        <?php endfor; ?>
      </div>
      <div style="color:red;"><?= $reviewHandler->errors['stars'] ?></div>
    </div>

    <!-- Submit button -->
    <button type="submit" class="btn btn-primary">Submit Review</button>
  </form>
</section>

<section class="container py-5">
  <!-- All reviews title -->
  <h2 class="mb-4">All Reviews</h2>

  <!-- Loop through reviews -->
  <?php foreach ($reviews as $r): ?>
    <div class="mb-4 border-bottom pb-3">
      <!-- Review author -->
      <strong style="color:#ffb300;"><?= htmlspecialchars($r['name']) ?></strong><br>
      <!-- Review date and category -->
      <small class="text-muted">
        <?= date('F j, Y H:i', strtotime($r['created_at'])) ?> |
        Category: <?= htmlspecialchars($r['category']) ?>
      </small>
      <!-- Review stars -->
      <div>
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <span style="color:<?= $i <= $r['stars'] ? '#ffb300' : '#ccc' ?>">&#9733;</span>
        <?php endfor; ?>
      </div>
      <!-- Review comment -->
      <p class="mt-2"><?= nl2br(htmlspecialchars($r['comment'])) ?></p>
    </div>
  <?php endforeach; ?>
</section>


<style>
.star { font-size: 24px; color: #ccc; cursor: pointer; transition: color 0.2s; }
.star.selected { color: #ffb300; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const starsWrapper = document.getElementById('stars-wrapper');
  const stars = starsWrapper.querySelectorAll('.star');
  const radios = starsWrapper.querySelectorAll('input[type="radio"]');

  function highlightStars(count) {
    stars.forEach(star => {
      const val = parseInt(star.getAttribute('data-value'));
      star.classList.toggle('selected', val <= count);
    });
  }

  stars.forEach(star => {
    star.addEventListener('click', () => {
      const val = parseInt(star.getAttribute('data-value'));
      radios[val - 1].checked = true;
      highlightStars(val);
    });

    star.addEventListener('mouseover', () => {
      const val = parseInt(star.getAttribute('data-value'));
      highlightStars(val);
    });

    star.addEventListener('mouseout', () => {
      const checkedRadio = starsWrapper.querySelector('input[type="radio"]:checked');
      highlightStars(checkedRadio ? parseInt(checkedRadio.value) : 0);
    });
  });

  const checkedRadio = starsWrapper.querySelector('input[type="radio"]:checked');
  if (checkedRadio) highlightStars(parseInt(checkedRadio.value));
});
</script>

<?php include_once "assets/parts/footer.php" ?>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/owl-carousel.js"></script>
<script src="assets/js/animation.js"></script>
<script src="assets/js/imagesloaded.js"></script>
<script src="assets/js/custom.js"></script>

</body>
</html>
