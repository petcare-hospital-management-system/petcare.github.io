<?php
require_once '../includes/config.php';

session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Fetch all users where role is NOT 'owner' (i.e. staff)
$stmt = $pdo->prepare("SELECT * FROM users WHERE role != 'owner' ORDER BY role, name ASC");
$stmt->execute();
$staff = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Staff Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Staff Users</h2>

  <a href="add_user.php" class="btn btn-success mb-3">Add New Staff</a>
  <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search by name or role...">

  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Phone</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($staff as $user): ?>
        <tr>
          <td><?= htmlspecialchars($user['name']) ?></td>
          <td><?= htmlspecialchars($user['username']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td><?= htmlspecialchars($user['role']) ?></td>
          <td><?= htmlspecialchars($user['phone']) ?></td>
          <td>
            <a href="edit_user.php?id=<?= $user['user_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="delete_user.php?id=<?= $user['user_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this staff user?');">Delete</a>
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
      const role = row.cells[3].textContent.toLowerCase();
      row.style.display = (name.includes(filter) || role.includes(filter)) ? "" : "none";
    });
  });
</script>

</body>
</html>
