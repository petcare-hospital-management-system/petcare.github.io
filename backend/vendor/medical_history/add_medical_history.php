<?php
include "../../config/db.php";
include "../../Include/navbar.php"; // Include the navbar
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_id = $_POST["pet_id"];
    $diagnosis = $_POST["diagnosis"];
    $treatment = $_POST["treatment"];
    $visit_date = $_POST["visit_date"];

    if (!empty($pet_id) && !empty($diagnosis) && !empty($treatment) && !empty($visit_date)) {
        try {
            // Use PDO for database operations
            $stmt = $pdo->prepare("INSERT INTO medical_history (pet_id, diagnosis, treatment, visit_date) VALUES (:pet_id, :diagnosis, :treatment, :visit_date)");
            $stmt->bindParam(":pet_id", $pet_id, PDO::PARAM_INT);
            $stmt->bindParam(":diagnosis", $diagnosis, PDO::PARAM_STR);
            $stmt->bindParam(":treatment", $treatment, PDO::PARAM_STR);
            $stmt->bindParam(":visit_date", $visit_date, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "<script>alert('Medical history added successfully!'); window.location.href='../doctor/doctor_dashboard.php';</script>";
            } else {
                echo "<script>alert('Error adding medical history!');</script>";
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
    <title>Add Medical History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add Medical History</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Pet ID:</label>
                <input type="number" name="pet_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Diagnosis:</label>
                <input type="text" name="diagnosis" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Treatment:</label>
                <textarea name="treatment" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Visit Date:</label>
                <input type="date" name="visit_date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Record</button>
        </form>
    </div>
</body>
</html>