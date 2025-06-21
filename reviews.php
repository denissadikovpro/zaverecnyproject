<?php
require_once 'classes/database.php';

class ReviewHandler {
    private $pdo;
    public $name = '';
    public $comment = '';
    public $stars = '';
    public $category = 'Apartments';
    public $edit_id = null;
    public $errors = ['name' => '', 'comment' => '', 'stars' => ''];

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function handleForm() {
        // Handle deletion
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
            $stmt = $this->pdo->prepare("DELETE FROM reviews WHERE id = ?");
            $stmt->execute([$_POST['delete_id']]);
            return;
        }

        // Handle edit button
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
            $stmt = $this->pdo->prepare("SELECT * FROM reviews WHERE id = ?");
            $stmt->execute([$_POST['edit_id']]);
            $review = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($review) {
                $this->edit_id = $review['id'];
                $this->name = $review['name'];
                $this->comment = $review['comment'];
                $this->stars = $review['stars'];
                $this->category = $review['category'];
            }
            return;
        }

        // Handle insert/update
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
            $this->name = trim($_POST['name'] ?? '');
            $this->comment = trim($_POST['comment'] ?? '');
            $this->stars = $_POST['stars'] ?? '';
            $this->category = $_POST['category'] ?? 'Apartments';
            $this->edit_id = $_POST['edit_id'] ?? null;

            if (!$this->name || !preg_match('/^[a-zA-Z0-9_ ]+$/u', $this->name)) {
                $this->errors['name'] = 'Please enter a valid name';
            }
            if (!$this->comment) {
                $this->errors['comment'] = 'Please enter a comment';
            }
            if (!in_array($this->stars, ['1', '2', '3', '4', '5'])) {
                $this->errors['stars'] = 'Please select a rating';
            }
            if (!in_array($this->category, ['Apartments', 'Food', 'Car rental', 'Shopping', 'Tours'])) {
                $this->category = 'Apartments';
            }

            if (empty(array_filter($this->errors))) {
                if ($this->edit_id) {
                    $stmt = $this->pdo->prepare("UPDATE reviews SET name=?, comment=?, stars=?, category=? WHERE id=?");
                    $stmt->execute([$this->name, $this->comment, $this->stars, $this->category, $this->edit_id]);
                } else {
                    $stmt = $this->pdo->prepare("INSERT INTO reviews (name, comment, stars, category) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$this->name, $this->comment, $this->stars, $this->category]);
                }

                // Clear input after save
                $this->name = $this->comment = $this->stars = '';
                $this->category = 'Apartments';
                $this->edit_id = null;
            }
        }
    }

    public function fetchReviews() {
        $stmt = $this->pdo->query("SELECT * FROM reviews ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

$db = new Database();
$pdo = $db->connect();
$reviewHandler = new ReviewHandler($pdo);
$reviewHandler->handleForm();
$reviews = $reviewHandler->fetchReviews();
?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <title>Homepage</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">


    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-plot-listing.css">
    <link rel="stylesheet" href="assets/css/animated.css">
    <link rel="stylesheet" href="assets/css/owl.css">

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
  <h2 class="mb-4"><?= $reviewHandler->edit_id ? 'Edit Review' : 'Leave a Review' ?></h2>

  <form method="POST">
    <input type="hidden" name="edit_id" value="<?= $reviewHandler->edit_id ?>">
    <div class="mb-3">
      <label class="form-label">Your Name or Username:</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($reviewHandler->name) ?>">
      <div style="color:red;"><?= $reviewHandler->errors['name'] ?></div>
    </div>

    <div class="mb-3">
      <label class="form-label">Your Comment:</label>
      <textarea name="comment" class="form-control"><?= htmlspecialchars($reviewHandler->comment) ?></textarea>
      <div style="color:red;"><?= $reviewHandler->errors['comment'] ?></div>
    </div>

    <div class="mb-3">
      <label class="form-label">Category:</label>
      <select name="category" class="form-control">
        <?php
        $options = ['Apartments', 'Food', 'Car rental', 'Shopping', 'Tours'];
        foreach ($options as $opt):
        ?>
          <option value="<?= $opt ?>" <?= $reviewHandler->category === $opt ? 'selected' : '' ?>><?= $opt ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Rating:</label><br>
      <div id="stars-wrapper">
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <input type="radio" id="star<?= $i ?>" name="stars" value="<?= $i ?>" style="display: none" <?= ($reviewHandler->stars == $i) ? 'checked' : '' ?>>
          <label for="star<?= $i ?>" class="star" data-value="<?= $i ?>">&#9733;</label>
        <?php endfor; ?>
      </div>
      <div style="color:red;"><?= $reviewHandler->errors['stars'] ?></div>
    </div>

    <button type="submit" name="submit_review" class="btn btn-<?= $reviewHandler->edit_id ? 'success' : 'primary' ?>">
      <?= $reviewHandler->edit_id ? 'Update Review' : 'Submit Review' ?>
    </button>
  </form>
</section>

<section class="container py-5">
  <h2 class="mb-4">All Reviews</h2>

  <?php foreach ($reviews as $r): ?>
    <div class="mb-4 border-bottom pb-3">
      <strong style="color:#ffb300;"><?= htmlspecialchars($r['name']) ?></strong><br>
      <small class="text-muted">
        <?= date('F j, Y H:i', strtotime($r['created_at'])) ?> |
        Category: <?= htmlspecialchars($r['category']) ?>
      </small>
      <div>
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <span style="color:<?= $i <= $r['stars'] ? '#ffb300' : '#ccc' ?>">&#9733;</span>
        <?php endfor; ?>
      </div>
      <p class="mt-2"><?= nl2br(htmlspecialchars($r['comment'])) ?></p>

      <form method="POST" class="d-inline">
        <input type="hidden" name="edit_id" value="<?= $r['id'] ?>">
        <button type="submit" class="btn btn-sm btn-warning">Edit</button>
      </form>

      <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this review?');">
        <input type="hidden" name="delete_id" value="<?= $r['id'] ?>">
        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
      </form>
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
</body>
</html>
