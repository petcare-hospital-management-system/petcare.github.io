<?php
include "../../config/db.php";
include "../../Include/navbar.php"; // Include the navbar
session_start();

$pet_id = isset($_GET['pet_id']) ? $_GET['pet_id'] : '';

try {
    $query = "SELECT * FROM vaccinations WHERE pet_id = :pet_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":pet_id", $pet_id, PDO::PARAM_INT);
    $stmt->execute();
    $vaccinations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}
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
                <?php foreach ($vaccinations as $row) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['vaccine_name']); ?></td>
                        <td><?= htmlspecialchars($row['vaccination_date']); ?></td>
                        <td><?= htmlspecialchars($row['next_due_date']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <a href="../doctor/doctor_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>