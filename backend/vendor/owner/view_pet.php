<?php
require_once '../../config/db.php';
session_start();

if (!isset($_GET['pet_id']) || !is_numeric($_GET['pet_id'])) {
    echo "<div class='alert alert-danger text-center'>Invalid pet ID.</div>";
    exit();
}

$pet_id = $_GET['pet_id'];

// Fetch pet details
$stmt = $pdo->prepare("SELECT * FROM pets WHERE pet_id = ?");
$stmt->execute([$pet_id]);
$pet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pet) {
    echo "<div class='alert alert-warning text-center'>Pet not found.</div>";
    exit();
}

// Fetch related data
$medicalHistory = $pdo->prepare("SELECT * FROM medical_history WHERE pet_id = ? ORDER BY visit_date DESC");
$medicalHistory->execute([$pet_id]);

$medications = $pdo->prepare("SELECT * FROM medications WHERE pet_id = ? ORDER BY start_date DESC");
$medications->execute([$pet_id]);

$labReports = $pdo->prepare("SELECT * FROM lab_reports WHERE pet_id = ?");
$labReports->execute([$pet_id]);
$labData = $labReports->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pet Medical History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h3 class="mb-4">Pet Profile - <?= htmlspecialchars($pet['pet_name']) ?></h3>

    <?php if (!empty($pet['photo'])): ?>
        <div class="text-center mb-3">
            <img src="../pet/<?= htmlspecialchars($pet['photo']) ?>" class="rounded" style="height: 150px; object-fit: cover;">
        </div>
    <?php endif; ?>

    <!-- Pet Info -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Basic Details</div>
        <div class="card-body">
            <p><strong>Breed:</strong> <?= htmlspecialchars($pet['breed']) ?></p>
            <p><strong>Species:</strong> <?= htmlspecialchars($pet['species']) ?></p>
            <p><strong>Age:</strong> <?= htmlspecialchars($pet['age']) ?> years</p>
            <p><strong>Gender:</strong> <?= htmlspecialchars($pet['gender']) ?></p>
            <p><strong>Owner:</strong> <?= htmlspecialchars($pet['owner_name']) ?></p>
        </div>
    </div>

    <!-- Medical History -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">Medical History</div>
        <div class="card-body">
            <?php foreach ($medicalHistory as $entry): ?>
                <div class="border-bottom pb-2 mb-2">
                    <strong>Date:</strong> <?= htmlspecialchars($entry['created_at']) ?><br>
                    <strong>Diagnosis:</strong> <?= htmlspecialchars($entry['diagnosis']) ?><br>
                    <strong>Treatment:</strong> <?= htmlspecialchars($entry['treatment']) ?>
                </div>
            <?php endforeach; ?>
            <?php if ($medicalHistory->rowCount() == 0): ?>
                <p class="text-muted">No medical history found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Medications -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">Medications</div>
        <div class="card-body">
            <?php foreach ($medications as $med): ?>
                <div class="border-bottom pb-2 mb-2">
                    <strong>Medicine:</strong> <?= htmlspecialchars($med['medicine_name']) ?><br>
                    <strong>Dosage:</strong> <?= htmlspecialchars($med['dosage']) ?><br>
                    <strong>Period:</strong> <?= htmlspecialchars($med['start_date']) ?> to <?= htmlspecialchars($med['end_date']) ?>
                </div>
            <?php endforeach; ?>
            <?php if ($medications->rowCount() == 0): ?>
                <p class="text-muted">No medications found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Lab Reports -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">Lab Reports</div>
        <div class="card-body">
            <?php foreach ($labReports as $report): ?>
                <div class="border-bottom pb-2 mb-2">
                    <strong>Type:</strong> <?= htmlspecialchars($report['report_type']) ?><br>
                    <strong>Result:</strong> <?= htmlspecialchars($report['result']) ?><br>
                    <strong>Date:</strong> <?= htmlspecialchars($report['date_uploaded']) ?><br>
                    <?php if (!empty($report['file_path'])): ?>
                        <a href="../lab/<?= htmlspecialchars($report['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-1">View Report</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <?php if ($labReports->rowCount() == 0): ?>
                <p class="text-muted">No lab reports found.</p>
            <?php endif; ?>
        </div>
    </div>

    <a href="view_my_pets.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to My Pets</a>
</div>
</body>
</html>
