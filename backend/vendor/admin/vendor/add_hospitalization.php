<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pet_id = $_POST['pet_id'];
    $doctor_id = $_POST['doctor_id'];
    $reason = $_POST['reason'];
    $admission_date = $_POST['admission_date'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO hospitalizations 
        (pet_id, doctor_id, reason, admission_date, status)
        VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$pet_id, $doctor_id, $reason, $admission_date, $status]);

    echo "Hospitalization record added successfully. <a href='hospitalization_list.php'>View All</a>";
    exit;
}

// Fetch pets and doctors
$pets = $pdo->query("SELECT pet_id, pet_name FROM pets")->fetchAll();
$doctors = $pdo->query("SELECT doctor_id, doctor_name FROM doctors")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head><title>Add Hospitalization</title></head>
<body>
    <h2>Add Hospitalization Record</h2>
    <form method="POST">
        <label>Pet:</label>
        <select name="pet_id" required>
            <?php foreach ($pets as $pet): ?>
                <option value="<?= $pet['pet_id'] ?>"><?= $pet['pet_name'] ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Doctor:</label>
        <select name="doctor_id" required>
            <?php foreach ($doctors as $doctor): ?>
                <option value="<?= $doctor['doctor_id'] ?>"><?= $doctor['doctor_name'] ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Reason:</label><textarea name="reason" required></textarea><br>
        <label>Admission Date:</label><input type="date" name="admission_date" required><br>
        <label>Status:</label>
        <select name="status">
            <option value="Admitted">Admitted</option>
            <option value="Under Observation">Under Observation</option>
            <option value="Discharged">Discharged</option>
        </select><br>
        <button type="submit">Save</button>
    </form>
</body>
</html>
