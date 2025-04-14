<?php
require_once '../includes/config.php';

session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$client_id = $_GET['id'] ?? null;
if (!$client_id) {
    echo "Client ID is missing.";
    exit();
}

$errors = [];
$success = false;

// Fetch existing client data
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ? AND role = 'owner'");
$stmt->execute([$client_id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    echo "Client not found.";
    exit();
}

// Handle update form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);

    if (empty($name) || empty($username) || empty($email) || empty($phone)) {
        $errors[] = "All fields are required.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, username = ?, email = ?, phone = ? WHERE user_id = ? AND role = 'owner'");
        if ($stmt->execute([$name, $username, $email, $phone, $client_id])) {
            $success = true;
            // Refresh data after update
            $client = ['name' => $name, 'username' => $username, 'email' => $email, 'phone' => $phone];
        } else {
            $errors[] = "Failed to update client.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Client</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Edit Client</h2>
  <a href="client_management.php" class="btn btn-secondary mb-3">Back to Client List</a>

  <?php if ($success): ?>
    <div class="alert alert-success">Client updated successfully!</div>
  <?php endif; ?>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <?= implode('<br>', $errors) ?>
    </div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Full Name</label>
      <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($client['name']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Username</label>
      <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($client['username']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($client['email']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Phone</label>
      <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($client['phone']) ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Update Client</button>
  </form>
</div>
</body>
</html>
