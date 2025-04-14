<?php
require_once '../includes/config.php';

// Fetch pets and doctors for the dropdowns
$pets = $pdo->query("SELECT pet_id, pet_name FROM pets")->fetchAll(PDO::FETCH_ASSOC);
$doctors = $pdo->query("SELECT doctor_id, name FROM doctors")->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_id = $_POST['pet_id'];
    $doctor_id = $_POST['doctor_id'];
    $reason = $_POST['reason'];
    $admission_date = $_POST['admission_date'];
    $discharge_date = $_POST['discharge_date'] ?: null;
    $status = $_POST['status'];

    $sql = "INSERT INTO hospitalizations (pet_id, doctor_id, reason, admission_date, discharge_date, status)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$pet_id, $doctor_id, $reason, $admission_date, $discharge_date, $status])) {
        echo "<script>alert('Hospitalization added successfully!'); window.location.href='hospitalization_list.php';</script>";
    } else {
        echo "<div class='alert alert-danger'>Error saving hospitalization.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Hospitalization</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Add Hospitalization</h2>

    <form method="POST" class="bg-white p-4 rounded shadow-sm">
        <div class="mb-3">
            <label for="pet_id" class="form-label">Pet</label>
            <select class="form-select" name="pet_id" required>
                <option value="">Select a pet</option>
                <?php foreach ($pets as $pet): ?>
                    <option value="<?= $pet['pet_id'] ?>"><?= htmlspecialchars($pet['pet_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="doctor_id" class="form-label">Doctor</label>
            <select class="form-select" name="doctor_id" required>
                <option value="">Select a doctor</option>
                <?php foreach ($doctors as $doctor): ?>
                    <option value="<?= $doctor['doctor_id'] ?>"><?= htmlspecialchars($doctor['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Reason</label>
            <textarea name="reason" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Admission Date</label>
            <input type="date" name="admission_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Discharge Date (optional)</label>
            <input type="date" name="discharge_date" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="Admitted">Admitted</option>
                <option value="Discharged">Discharged</option>
                <option value="Under Observation">Under Observation</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Add Hospitalization</button>
        <a href="hospitalization_list.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
