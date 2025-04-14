<?php
require_once '../includes/config.php';

if (!isset($_GET['client_id'])) {
    echo "Client ID missing!";
    exit();
}

$client_id = $_GET['client_id'];

// Fetch client info
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    echo "Client not found!";
    exit();
}

// Fetch pets for this client
$petsStmt = $pdo->prepare("SELECT * FROM pets WHERE owner_id = ?");
$petsStmt->execute([$client_id]);
$pets = $petsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($client['name']) ?>'s Pets</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2><?= htmlspecialchars($client['name']) ?>Pets</h2>
  <a href="client_management.php" class="btn btn-secondary mb-3">Back to Client List</a>
  <?php
$client_id = $_GET['client_id'] ?? null;
if (!$client_id) {
    echo "Client ID is missing.";
    exit();
}
?>
<a href="add_pet.php?client_id=<?= $client_id ?>" class="btn btn-success mb-3">Add Pet</a>
  <?php if (count($pets) > 0): ?>
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>Pet Name</th>
        <th>Species</th>
        <th>Breed</th>
        <th>Age</th>
        <th>Gender</th>
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
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p>No pets found for this client.</p>
  <?php endif; ?>
</div>
</body>
</html>

