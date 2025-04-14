<?php
include "../../config/db.php";
include "../Include/navbar.php"; // Include the navbar
session_start();

$appointment_id = $_GET['id'] ?? null;

if (!$appointment_id) {
    die("Error: No appointment selected. Please go back and select an appointment.");
}

// Fetch the existing appointment details
try {
    $stmt = $pdo->prepare("SELECT * FROM appointments WHERE id = :id");
    $stmt->bindParam(":id", $appointment_id, PDO::PARAM_INT);
    $stmt->execute();
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$appointment) {
        die("Error: Appointment not found.");
    }
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

// Handle form submission to update the appointment
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_date = $_POST["appointment_date"];
    $reason = $_POST["reason"];
    $status = $_POST["status"];

    if (!empty($appointment_date) && !empty($reason) && !empty($status)) {
        try {
            $stmt = $pdo->prepare("UPDATE appointments SET appointment_date = :appointment_date, reason = :reason, status = :status WHERE id = :id");
            $stmt->bindParam(":appointment_date", $appointment_date, PDO::PARAM_STR);
            $stmt->bindParam(":reason", $reason, PDO::PARAM_STR);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->bindParam(":id", $appointment_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo "<script>alert('Appointment updated successfully!'); window.location.href='appointments.php?pet_id=" . $appointment['pet_id'] . "';</script>";
            } else {
                echo "<script>alert('Error updating appointment!');</script>";
            }
        } catch (PDOException $e) {
            die("Database query failed: " . $e->getMessage());
        }
    } else {
        echo "<script>alert('All fields are required!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Appointment</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Appointment Date:</label>
                <input type="date" name="appointment_date" class="form-control" value="<?= htmlspecialchars($appointment['appointment_date']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Reason:</label>
                <textarea name="reason" class="form-control" required><?= htmlspecialchars($appointment['reason']); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Status:</label>
                <select name="status" class="form-select" required>
                    <option value="Pending" <?= $appointment['status'] == "Pending" ? "selected" : ""; ?>>Pending</option>
                    <option value="Completed" <?= $appointment['status'] == "Completed" ? "selected" : ""; ?>>Completed</option>
                    <option value="Cancelled" <?= $appointment['status'] == "Cancelled" ? "selected" : ""; ?>>Cancelled</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Appointment</button>
            <a href="appointments.php?pet_id=<?= $appointment['pet_id']; ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>