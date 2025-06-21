<?php
// Include database connection class
require_once 'classes/database.php';

// Review handler class
class ReviewHandler {
    private $pdo;
    public $name = '';
    public $comment = '';
    public $stars = '';
    public $category = 'Apartments';
    public $errors = ['name' => '', 'comment' => '', 'stars' => ''];

    // For editing review
    public $editId = null;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Handle POST requests
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['delete_id'])) {
                // Delete review
                $this->deleteReview((int)$_POST['delete_id']);
            } elseif (isset($_POST['edit_id'])) {
                // Load review to edit
                $this->editId = (int)$_POST['edit_id'];
                $this->loadReviewForEdit($this->editId);
            } elseif (isset($_POST['update_id'])) {
                // Update review
                $this->updateReview((int)$_POST['update_id']);
            } else {
                // Add new review
                $this->addReview();
            }
        }
    }

    // Add review to database
    private function addReview() {
        $this->name = trim($_POST['name'] ?? '');
        $this->comment = trim($_POST['comment'] ?? '');
        $this->stars = $_POST['stars'] ?? '';
        $this->category = $_POST['category'] ?? 'Apartments';

        $this->validate();

        if (empty(array_filter($this->errors))) {
            $stmt = $this->pdo->prepare("INSERT INTO reviews (name, comment, stars, category) VALUES (?, ?, ?, ?)");
            $stmt->execute([$this->name, $this->comment, $this->stars, $this->category]);
            $this->clearForm();
        }
    }

    // Validate form fields
    private function validate() {
        if (!$this->name || !preg_match('/^[a-zA-Z0-9_ ]+$/u', $this->name)) {
            $this->errors['name'] = 'Please enter a valid name';
        }
        if (!$this->comment) {
            $this->errors['comment'] = 'Please enter a comment';
        }
        if (!in_array($this->stars, ['1', '2', '3', '4', '5'])) {
            $this->errors['stars'] = 'Please select a rating';
        }
        $validCategories = ['Apartments', 'Food', 'Car rental', 'Shopping', 'Tours'];
        if (!in_array($this->category, $validCategories)) {
            $this->category = 'Apartments';
        }
    }

    // Clear form values
    private function clearForm() {
        $this->name = '';
        $this->comment = '';
        $this->stars = '';
        $this->category = 'Apartments';
        $this->errors = ['name' => '', 'comment' => '', 'stars' => ''];
        $this->editId = null;
    }

    // Get all reviews
    public function fetchReviews() {
        $stmt = $this->pdo->query("SELECT * FROM reviews ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete review by ID
    private function deleteReview(int $id) {
        $stmt = $this->pdo->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->execute([$id]);
        $this->clearForm();
    }

    // Load review into form
    private function loadReviewForEdit(int $id) {
        $stmt = $this->pdo->prepare("SELECT * FROM reviews WHERE id = ?");
        $stmt->execute([$id]);
        $review = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($review) {
            $this->name = $review['name'];
            $this->comment = $review['comment'];
            $this->stars = $review['stars'];
            $this->category = $review['category'];
        }
    }

    // Update review in database
    private function updateReview(int $id) {
        $this->name = trim($_POST['name'] ?? '');
        $this->comment = trim($_POST['comment'] ?? '');
        $this->stars = $_POST['stars'] ?? '';
        $this->category = $_POST['category'] ?? 'Apartments';

        $this->validate();

        if (empty(array_filter($this->errors))) {
            $stmt = $this->pdo->prepare("UPDATE reviews SET name = ?, comment = ?, stars = ?, category = ? WHERE id = ?");
            $stmt->execute([$this->name, $this->comment, $this->stars, $this->category, $id]);
            $this->clearForm();
        } else {
            $this->editId = $id;
        }
    }
}

// Initialize handler
$db = new Database();
$pdo = $db->connect();
$reviewHandler = new ReviewHandler($pdo);
$reviewHandler->handleRequest();
$reviews = $reviewHandler->fetchReviews();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Reviews</title>

  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/fontawesome.css" />
  <link rel="stylesheet" href="assets/css/templatemo-plot-listing.css" />
  <link rel="stylesheet" href="assets/css/animated.css" />
  <link rel="stylesheet" href="assets/css/owl.css" />

  <style>
    .star {
      font-size: 24px;
      color: #ccc;
      cursor: pointer;
      transition: color 0.2s;
    }

    .star.selected {
      color: #ffb300;
    }
  </style>
</head>

<body>

<?php include_once "assets/parts/header.php" ?>

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

<section class="container py-5">
  <!-- Form title -->
  <h2 class="mb-4"><?= $reviewHandler->editId ? "Edit Review" : "Leave a Review" ?></h2>

  <form method="POST">
    <!-- Hidden input for update -->
    <?php if ($reviewHandler->editId): ?>
      <input type="hidden" name="update_id" value="<?= $reviewHandler->editId ?>">
    <?php endif; ?>

    <!-- Name input -->
    <div class="mb-3">
      <label class="form-label">Your Name or Username:</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($reviewHandler->name) ?>" />
      <div style="color:red;"><?= $reviewHandler->errors['name'] ?></div>
    </div>

    <!-- Comment textarea -->
    <div class="mb-3">
      <label class="form-label">Your Comment:</label>
      <textarea name="comment" class="form-control"><?= htmlspecialchars($reviewHandler->comment) ?></textarea>
      <div style="color:red;"><?= $reviewHandler->errors['comment'] ?></div>
    </div>

    <!-- Category dropdown -->
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

    <!-- Star rating -->
    <div class="mb-3">
      <label class="form-label">Rating:</label><br />
      <div id="stars-wrapper">
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <!-- Star radio -->
          <input type="radio" id="star<?= $i ?>" name="stars" value="<?= $i ?>" style="display: none" <?= ($reviewHandler->stars == $i) ? 'checked' : '' ?> />
          <label for="star<?= $i ?>" class="star" data-value="<?= $i ?>">&#9733;</label>
        <?php endfor; ?>
      </div>
      <div style="color:red;"><?= $reviewHandler->errors['stars'] ?></div>
    </div>

    <!-- Submit button -->
    <button type="submit" class="btn btn-primary"><?= $reviewHandler->editId ? "Update Review" : "Submit Review" ?></button>

    <!-- Cancel edit button -->
    <?php if ($reviewHandler->editId): ?>
      <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary">Cancel</a>
    <?php endif; ?>
  </form>
</section>

<!-- Reviews list -->
<section class="container py-5">
  <h2 class="mb-4">All Reviews</h2>

  <?php foreach ($reviews as $r): ?>
    <div class="mb-4 border-bottom pb-3">
      <!-- Reviewer name -->
      <strong style="color:#ffb300;"><?= htmlspecialchars($r['name']) ?></strong><br />

      <!-- Date and category -->
      <small class="text-muted">
        <?= date('F j, Y H:i', strtotime($r['created_at'])) ?> |
        Category: <?= htmlspecialchars($r['category']) ?>
      </small>

      <!-- Star display -->
      <div>
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <span style="color:<?= $i <= $r['stars'] ? '#ffb300' : '#ccc' ?>">&#9733;</span>
        <?php endfor; ?>
      </div>

      <!-- Review text -->
      <p class="mt-2"><?= nl2br(htmlspecialchars($r['comment'])) ?></p>

      <!-- Edit button -->
      <form method="POST" style="display:inline-block;">
        <input type="hidden" name="edit_id" value="<?= $r['id'] ?>">
        <button type="submit" class="btn btn-sm btn-warning">Edit</button>
      </form>

      <!-- Delete button -->
      <form method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this review?');">
        <input type="hidden" name="delete_id" value="<?= $r['id'] ?>">
        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
      </form>
    </div>
  <?php endforeach; ?>
</section>


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
