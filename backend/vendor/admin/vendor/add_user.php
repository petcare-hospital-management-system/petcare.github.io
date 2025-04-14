<?php
require_once '../includes/config.php';
session_start();

// Only allow admins
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $role     = $_POST['role'];
    $password = $_POST['password'];

    if (empty($name) || empty($username) || empty($email) || empty($phone) || empty($role) || empty($password)) {
        $errors[] = "All fields are required.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, username, email, phone, password, role) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $username, $email, $phone, $hashed_password, $role])) {
            $success = true;
        } else {
            $errors[] = "Failed to add user. Username or email may already exist.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Staff</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Add New Staff User</h2>
  <a href="users_staff.php" class="btn btn-secondary mb-3">Back to User Staff</a>

  <?php if ($success): ?>
    <div class="alert alert-success">User added successfully!</div>
  <?php endif; ?>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <?= implode('<br>', $errors) ?>
    </div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Full Name</label>
      <input type="text" class="form-control" name="name" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Username</label>
      <input type="text" class="form-control" name="username" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" class="form-control" name="email" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Phone</label>
      <input type="text" class="form-control" name="phone" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Role</label>
      <select name="role" class="form-select" required>
        <option value="">Select Role</option>
        <option value="admin">Admin</option>
        <option value="doctor">doctor</option>
        <option value="staff">Staff</option>
        <option value="receptionist">Receptionist</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" class="form-control" name="password" required>
    </div>

    <button type="submit" class="btn btn-success">Add User</button>
  </form>
</div>
</body>
</html>
