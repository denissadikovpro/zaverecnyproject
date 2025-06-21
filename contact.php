<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact us</title>
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
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
          <h6>Keep in touch with us</h6>
          <h2>Feel free to send us a message about your business needs</h2>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
require_once 'classes/Database.php';

class ContactController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function get($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM contact_messages WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        return $this->pdo->query("SELECT * FROM contact_messages ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO contact_messages (name, surname, email, categories, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['name'], $data['surname'], $data['email'], $data['categories'], $data['message']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE contact_messages SET name=?, surname=?, email=?, categories=?, message=? WHERE id=?");
        $stmt->execute([$data['name'], $data['surname'], $data['email'], $data['categories'], $data['message'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// Init
$db = new Database();
$pdo = $db->connect();
$controller = new ContactController($pdo);

// Variables
$success = '';
$error = '';
$editing = false;
$data = ['name'=>'', 'surname'=>'', 'email'=>'', 'categories'=>[], 'message'=>''];
$id = $_GET['edit'] ?? null;

// Handle edit request
if ($id) {
    $editing = true;
    $row = $controller->get($id);
    if ($row) {
        $data = $row;
        $data['categories'] = explode(', ', $data['categories']);
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $controller->delete($_GET['delete']);
    header("Location: contact.php");
    exit;
}

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => trim($_POST['name']),
        'surname' => trim($_POST['surname']),
        'email' => trim($_POST['email']),
        'categories' => $_POST['categories'] ?? [],
        'message' => trim($_POST['message'])
    ];
    $errors = [];

    if (!$data['name']) $errors[] = 'Name is required';
    if (!$data['surname']) $errors[] = 'Surname is required';
    if (!$data['email'] || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
    if (!$data['message']) $errors[] = 'Message is required';

    if (empty($errors)) {
        $data['categories'] = implode(', ', $data['categories']);
        if ($editing) {
            $controller->update($id, $data);
            $success = "Message updated successfully!";
            header("Location: contact.php");
            exit;
        } else {
            $controller->create($data);
            $success = "Message sent successfully!";
            $data = ['name'=>'', 'surname'=>'', 'email'=>'', 'categories'=>[], 'message'=>''];
        }
    }
}

$messages = $controller->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact CRUD</title>
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <h2><?= $editing ? 'Edit Message' : 'Contact Us' ?></h2>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger"><?php foreach ($errors as $e) echo "<div>$e</div>"; ?></div>
  <?php elseif ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="row">
      <div class="col-md-6 mb-2">
        <input name="name" class="form-control" placeholder="Name" value="<?= htmlspecialchars($data['name']) ?>">
      </div>
      <div class="col-md-6 mb-2">
        <input name="surname" class="form-control" placeholder="Surname" value="<?= htmlspecialchars($data['surname']) ?>">
      </div>
    </div>

    <div class="mb-2">
      <input name="email" class="form-control" placeholder="Email" value="<?= htmlspecialchars($data['email']) ?>">
    </div>

    <div class="mb-2">
      <?php $opts = ['Cars', 'Apartments', 'Shopping', 'Food & Life', 'Traveling'];
      foreach ($opts as $opt): ?>
        <label class="me-3">
          <input type="checkbox" name="categories[]" value="<?= $opt ?>"
            <?= in_array($opt, $data['categories']) ? 'checked' : '' ?>> <?= $opt ?>
        </label>
      <?php endforeach; ?>
    </div>

    <div class="mb-2">
      <textarea name="message" class="form-control" placeholder="Message"><?= htmlspecialchars($data['message']) ?></textarea>
    </div>

    <button class="btn btn-primary"><?= $editing ? 'Update' : 'Send' ?></button>
    <?php if ($editing): ?>
      <a href="contact.php" class="btn btn-secondary">Cancel</a>
    <?php endif; ?>
  </form>

  <hr>
  <h4 class="mt-5">Submitted Messages</h4>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Name</th><th>Surname</th><th>Email</th><th>Categories</th><th>Message</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($messages as $msg): ?>
        <tr>
          <td><?= htmlspecialchars($msg['name']) ?></td>
          <td><?= htmlspecialchars($msg['surname']) ?></td>
          <td><?= htmlspecialchars($msg['email']) ?></td>
          <td><?= htmlspecialchars($msg['categories']) ?></td>
          <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
          <td>
            <a href="?edit=<?= $msg['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="?delete=<?= $msg['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this message?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include_once "assets/parts/footer.php" ?>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/owl-carousel.js"></script>
<script src="assets/js/animation.js"></script>
<script src="assets/js/imagesloaded.js"></script>
<script src="assets/js/custom.js"></script>

</body>
</html>
