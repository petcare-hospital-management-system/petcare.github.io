<?php
require_once '../includes/config.php'; // Connects and gives $pdo

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = ?");
    $stmt->execute(['owner']);
    $owners = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Pet Owners</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light p-4">
    <div class="container">
        <h1 class="mb-4 text-center">Client Pet Owners</h1>
        <div class="card shadow">
            <div class="card-body">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($owners)): ?>
                            <?php foreach ($owners as $owner): ?>
                                <tr>
                                    <td><?= htmlspecialchars($owner['user_id']) ?></td>
                                    <td><?= htmlspecialchars($owner['username']) ?></td>
                                    <td><?= htmlspecialchars($owner['email']) ?></td>
                                    <td><?= htmlspecialchars($owner['phone']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No owner records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
