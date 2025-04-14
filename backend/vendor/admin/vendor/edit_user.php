<?php
require_once '../includes/config.php';
session_start();

// Only allow admins
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid user ID.");
}

$user_id = (int) $_GET['id'];

// Fetch the user
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ? AND role != 'owner'");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found or not allowed to edit.");
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $role     = $_POST['role'];
    $password = $_POST['password']; // Optional

    if (empty($name) || empty($username) || empty($email) || empty($phone) || empty($role)) {
        $errors[] = "All fields except password are required.";
    }

    if (empty($errors)) {
        // Update password only if a new one is provided
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET name = ?, username = ?, email = ?, phone = ?, password = ?, role = ? WHERE user_id = ?");
            $success = $stmt->execute([$name, $username, $email, $phone, $hashed_password, $role, $user_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, username = ?, email = ?, phone = ?, role = ? WHERE user_id = ?");
            $success = $stmt->execute([$name, $username, $email, $phone, $role, $user_id]);
        }

        if ($success) {
            header("Location: users_staff.php");
            exit();
        } else {
            $errors[] = "Failed to update user.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Staff User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Edit Staff User</h2>
  <a href="users_staff.php" class="btn btn-secondary mb-3">Back to User Management</a>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <?= implode('<br>', $errors) ?>
    </div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Full Name</label>
      <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Username</label>
      <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Phone</label>
      <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Role</label>
      <select name="role" class="form-select" required>
        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        <option value="staff" <?= $user['role'] === 'staff' ? 'selected' : '' ?>>Staff</option>
        <option value="receptionist" <?= $user['role'] === 'receptionist' ? 'selected' : '' ?>>Receptionist</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">New Password <small class="text-muted">(Leave blank to keep current)</small></label>
      <input type="password" class="form-control" name="password">
    </div>

    <button type="submit" class="btn btn-primary">Update User</button>
  </form>
</div>
</body>
</html>
