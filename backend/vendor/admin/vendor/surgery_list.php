<?php
require_once '../includes/config.php'; // $pdo connection

// Get all surgeries with pet & doctor info
$stmt = $pdo->prepare("
    SELECT 
        s.surgery_id,
        s.surgery_type,
        s.scheduled_date,
        s.status,
        p.pet_name,
        p.owner_name,
        d.doctor_name
    FROM surgeries s
    LEFT JOIN pets p ON s.pet_id = p.pet_id
    LEFT JOIN doctors d ON s.doctor_id = d.doctor_id
    ORDER BY s.scheduled_date DESC
");

$stmt->execute();
$surgeries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html>
<head>
    <title>All Surgeries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>All Surgeries</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Pet Name</th>
                <th>Owner</th>
                <th>Surgery Type</th>
                <th>Scheduled Date</th>
                <th>Status</th>
                <th>Doctor</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($surgeries)): ?>
                <?php foreach ($surgeries as $surgery): ?>
                    <tr>
                        <td><?= htmlspecialchars($surgery['pet_name']) ?></td>
                        <td><?= htmlspecialchars($surgery['owner_name']) ?></td>
                        <td><?= htmlspecialchars($surgery['surgery_type']) ?></td>
                        <td><?= htmlspecialchars($surgery['scheduled_date']) ?></td>
                        <td><?= htmlspecialchars($surgery['status']) ?></td>
                        <td><?= htmlspecialchars($surgery['doctor_name']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No surgeries found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
