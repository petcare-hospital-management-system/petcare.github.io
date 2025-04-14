<?php
require_once '../includes/config.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_id = $_POST['pet_id'];
    $doctor_id = $_POST['doctor_id'];
    $test_type = $_POST['test_type'];
    $result = $_POST['result'];
    $report_date = $_POST['report_date'];

    // Handle PDF upload
    $file_path = null;
    if (isset($_FILES['report_file']) && $_FILES['report_file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/lab_reports/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $filename = basename($_FILES['report_file']['name']);
        $target_path = $upload_dir . time() . '_' . $filename;

        if (move_uploaded_file($_FILES['report_file']['tmp_name'], $target_path)) {
            $file_path = $target_path;
        }
    }

    // Insert into DB
    $sql = "INSERT INTO lab_reports (pet_id, doctor_id, test_type, result, report_date, report_file)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$pet_id, $doctor_id, $test_type, $result, $report_date, $file_path])) {
        echo "<div style='padding:10px; background:#d4edda; color:#155724;'>Report added successfully. <a href='laboratory.php'>View Reports</a></div>";
    } else {
        echo "<div style='padding:10px; background:#f8d7da; color:#721c24;'>Error saving report.</div>";
    }
}

// Fetch pets and doctors for dropdowns
$pets = $pdo->query("SELECT pet_id, pet_name FROM pets")->fetchAll();
$doctors = $pdo->query("SELECT doctor_id, doctor_name FROM doctors")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Lab Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Add Lab Report</h2>
    <form method="POST" enctype="multipart/form-data" class="mt-4">
        <div class="mb-3">
            <label for="pet_id" class="form-label">Select Pet</label>
            <select name="pet_id" class="form-select" required>
                <option value="">-- Choose Pet --</option>
                <?php foreach ($pets as $pet): ?>
                    <option value="<?= $pet['pet_id'] ?>"><?= htmlspecialchars($pet['pet_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="doctor_id" class="form-label">Doctor</label>
            <select name="doctor_id" class="form-select" required>
                <option value="">-- Select Doctor --</option>
                <?php foreach ($doctors as $doc): ?>
                    <option value="<?= $doc['doctor_id'] ?>"><?= htmlspecialchars($doc['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Test Type</label>
            <input type="text" name="test_type" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Result</label>
            <textarea name="result" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Report Date</label>
            <input type="date" name="report_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Upload Report File (PDF)</label>
            <input type="file" name="report_file" class="form-control" accept="application/pdf">
        </div>

        <button type="submit" class="btn btn-primary">Add Report</button>
        <a href="laboratory.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
