<?php
require_once '../includes/config.php';
session_start();

// Only allow logged-in staff or admins
if (!isset($_SESSION["role"]) || !in_array($_SESSION["role"], ['admin', 'staff', 'receptionist'])) {
    header("Location: login.php");
    exit();
}

// Fetch all pets and join with their owners
$stmt = $pdo->prepare("
    SELECT pets.*, users.name AS owner_name
    FROM pets
    JOIN users ON pets.owner_id = users.user_id
    ORDER BY pets.pet_name ASC
");

$stmt->execute();
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pets Report</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Pets Report</h2>
  <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

  <?php if (count($pets) === 0): ?>
    <div class="alert alert-info">No pets found.</div>
  <?php else: ?>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Name</th>
          <th>Species</th>
          <th>Breed</th>
          <th>Age</th>
          <th>Gender</th>
          <th>Owner</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pets as $pet): ?>
          <tr>
            <td><?= htmlspecialchars($pet['pet_name']) ?></td>
            <td><?= htmlspecialchars($pet['species']) ?></td>
            <td><?= htmlspecialchars($pet['breed']) ?></td>
            <td><?= htmlspecialchars($pet['age']) ?></td>
            <td><?= htmlspecialchars($pet['gender']) ?></td>
            <td><?= htmlspecialchars($pet['owner_name']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
</body>
</html>
