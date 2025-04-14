<?php
require_once '../includes/config.php';

session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$client_id = $_GET['client_id'] ?? null;
if (!$client_id) {
    echo "Client ID is missing.";
    exit();
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $species = trim($_POST['species']);
    $breed = trim($_POST['breed']);
    $age = trim($_POST['age']);

    if (empty($name) || empty($species) || empty($breed) || empty($age)) {
        $errors[] = "All fields are required.";
    } elseif (!is_numeric($age)) {
        $errors[] = "Age must be a number.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO pets (owner_id,pet_name, species, breed, age) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$client_id, $name, $species, $breed, $age])) {
            $success = true;
        } else {
            $errors[] = "Failed to add pet.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Pet</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Add Pet for Client ID: <?= htmlspecialchars($client_id) ?></h2>
  <a href="view_pets.php?client_id=<?= $client_id ?>" class="btn btn-secondary mb-3">Back to Pet List</a>

  <?php if ($success): ?>
    <div class="alert alert-success">Pet added successfully!</div>
  <?php endif; ?>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <?= implode('<br>', $errors) ?>
    </div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label for="name" class="form-label">Pet Name</label>
      <input type="text" class="form-control" name="name" required>
    </div>

    <div class="mb-3">
      <label for="species" class="form-label">Species</label>
      <input type="text" class="form-control" name="species" required>
    </div>

    <div class="mb-3">
      <label for="breed" class="form-label">Breed</label>
      <input type="text" class="form-control" name="breed" required>
    </div>

    <div class="mb-3">
      <label for="age" class="form-label">Age (in years)</label>
      <input type="number" class="form-control" name="age" required>
    </div>

    <button type="submit" class="btn btn-primary">Add Pet</button>
  </form>
</div>
</body>
</html>
