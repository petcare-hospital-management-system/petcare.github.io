<?php
// Include DB connection
require_once '../includes/config.php'; // Connects and gives $pdo

$query = "
    SELECT c.*, p.pet_name, d.doctor_name 
    FROM consultations c
    JOIN pets p ON c.pet_id = p.pet_id
    JOIN doctors d ON c.doctor_id = d.doctor_id
    ORDER BY c.consultation_date DESC
";
try {
    $stmt = $pdo->query($query);
    $consultations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching consultations: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Consultations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Consultation Records</h2>
    <a href="add_consultation.php" class="btn btn-primary mb-3">Add Consultation</a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Pet</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Notes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($consultations as $index => $row): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($row['pet_name']) ?></td>
                <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                <td><?= htmlspecialchars($row['consultation_date']) ?></td>
                <td><?= htmlspecialchars($row['notes']) ?></td>
                <td>
                    <a href="edit_consultation.php?id=<?= $row['consultation_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete_consultation.php?id=<?= $row['consultation_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this consultation?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
