<?php
require_once '../includes/config.php';

// Fetch lab reports with pet names
$query = "SELECT lab_reports.*, pets.pet_name 
          FROM lab_reports 
          JOIN pets ON lab_reports.pet_id = pets.pet_id 
          ORDER BY lab_reports.report_date DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laboratory Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Laboratory Reports</h2>
    <a href="add_lab_report.php" class="btn btn-primary mb-4">Add Lab Report</a>

    <?php if (!empty($reports)): ?>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Pet Name</th>
                    <th>Test Type</th>
                    <th>Result</th>
                    <th>Report Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $index => $report): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($report['pet_name']); ?></td>
                        <td><?= htmlspecialchars($report['test_type']); ?></td>
                        <td><?= nl2br(htmlspecialchars($report['result'])); ?></td>
                        <td><?= date('Y-m-d', strtotime($report['report_date'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted">No lab reports available.</p>
    <?php endif; ?>

    <a href="../admin_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
</body>
</html>
