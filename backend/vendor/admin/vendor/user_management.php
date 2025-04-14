<?php
require_once '../includes/config.php';

session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Fetch only users with role = 'owner'
$stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'owner' ORDER BY name ASC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Client (Owner) Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Client Management</h2>

  <a href="add_user.php" class="btn btn-success mb-3">Add New Client</a>
  <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search by name, username or email...">

  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $user): ?>
        <tr>
          <td><?= htmlspecialchars($user['name']) ?></td>
          <td><?= htmlspecialchars($user['username']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td><?= htmlspecialchars($user['phone']) ?></td>
          <td>
            <a href="edit_user.php?id=<?= $user['user_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="delete_user.php?id=<?= $user['user_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this client?');">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script>
  const searchInput = document.getElementById('searchInput');
  searchInput.addEventListener('keyup', function () {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll("table tbody tr");

    rows.forEach(row => {
      const name = row.cells[0].textContent.toLowerCase();
      const username = row.cells[1].textContent.toLowerCase();
      const email = row.cells[2].textContent.toLowerCase();
      row.style.display = (name.includes(filter) || username.includes(filter) || email.includes(filter)) ? "" : "none";
    });
  });
</script>

</body>
</html>
