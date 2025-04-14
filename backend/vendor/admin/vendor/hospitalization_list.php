<?php
require_once '../includes/config.php';

$hospitalizations = []; // Initialize to avoid undefined variable

try {
    $query = "
    SELECT h.*, p.pet_name, doctor_name
    FROM hospitalizations h
    JOIN pets p ON h.pet_id = p.pet_id
    JOIN doctors d ON h.doctor_id = d.doctor_id
    ORDER BY h.admission_date DESC
";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $hospitalizations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Error fetching hospitalization data: " . $e->getMessage() . "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hospitalization List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Hospitalization List</h2>
        <a href="add_hospitalization.php" class="btn btn-success">+ Add Hospitalization</a>
    </div>

    <?php if (!empty($hospitalizations)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Pet Name</th>
                        <th>Doctor</th>
                        <th>Reason</th>
                        <th>Admission</th>
                        <th>Discharge</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hospitalizations as $i => $row): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($row['pet_name']) ?></td>
                            <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                            <td><?= htmlspecialchars($row['reason']) ?></td>
                            <td><?= htmlspecialchars($row['admission_date']) ?></td>
                            <td><?= $row['discharge_date'] ? htmlspecialchars($row['discharge_date']) : '—' ?></td>
                            <td>
                                <span class="badge 
                                    <?= match($row['status']) {
                                        'Admitted' => 'bg-warning text-dark',
                                        'Discharged' => 'bg-success',
                                        'Under Observation' => 'bg-info text-dark',
                                        default => 'bg-secondary'
                                    } ?>">
                                    <?= $row['status'] ?>
                                </span>
                            </td>
                            <td>
                                <a href="view_hospitalization.php?id=<?= $row['hospitalization_id'] ?>" class="btn btn-sm btn-primary">View</a>
                                <a href="edit_hospitalization.php?id=<?= $row['hospitalization_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="delete_hospitalization.php?id=<?= $row['hospitalization_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted">No hospitalizations found.</p>
    <?php endif; ?>
</div>

</body>
</html>
