<?php
require_once '../includes/config.php';

session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Fetch all clients (users with role 'owner')
$stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'owner'");
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Client Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Client Management</h2>

  <a href="add_client.php" class="btn btn-success mb-3">Add New Client</a>
  <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search by name or email...">

  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>Username</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($clients as $client): ?>
        <tr>
          <td><?= htmlspecialchars($client['username']) ?></td>
          <td><?= htmlspecialchars($client['name']) ?></td>
          <td><?= htmlspecialchars($client['email']) ?></td>
          <td><?= htmlspecialchars($client['phone']) ?></td>
          <td>
            <a href="view_pets.php?client_id=<?= $client['user_id'] ?>" class="btn btn-sm btn-primary">View Pets</a>
            <a href="edit_client.php?id=<?= $client['user_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="delete_client.php?id=<?= $client['user_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this client?');">Delete</a>
          
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
      const email = row.cells[1].textContent.toLowerCase();
      row.style.display = (name.includes(filter) || email.includes(filter)) ? "" : "none";
    });
  });
</script>

</body>
</html>
