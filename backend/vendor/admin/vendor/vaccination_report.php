<?php
require_once '../includes/config.php';
session_start();

// Admin access only
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM vaccinations";
// Fetch vaccination history from the database  
$stmt = $pdo->prepare($query);
$stmt->execute();
$vaccinations = $stmt->fetchAll(PDO::FETCH_ASSOC); // Store as $vaccinations

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaccination History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Vaccination History</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Vaccine Name</th>
                    <th>Vaccination Date</th>
                    <th>Next Due Date</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($vaccinations as $index => $vaccination): ?>                     
    <tr>
        <td><?= htmlspecialchars($vaccination['vaccine_name']); ?></td>
        <td><?= htmlspecialchars($vaccination['vaccination_date']); ?></td>
        <td><?= htmlspecialchars($vaccination['next_due_date']); ?></td>
    </tr>
<?php endforeach; ?>

            </tbody>
        </table>

        <a href="../admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>