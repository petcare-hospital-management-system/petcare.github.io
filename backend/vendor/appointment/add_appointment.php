<?php
include "../../config/db.php";
include "../../Include/navbar.php"; // Include the navbar
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_id = $_POST["pet_id"];
    $appointment_date = $_POST["appointment_date"];
    $reason = $_POST["reason"];
    $status = "Pending"; // Default status

    if (!empty($pet_id) && !empty($appointment_date) && !empty($reason)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO appointments (pet_id, appointment_date, reason, status) VALUES (:pet_id, :appointment_date, :reason, :status)");
            $stmt->bindParam(":pet_id", $pet_id, PDO::PARAM_INT);
            $stmt->bindParam(":appointment_date", $appointment_date, PDO::PARAM_STR);
            $stmt->bindParam(":reason", $reason, PDO::PARAM_STR);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "<script>alert('Appointment booked successfully!'); window.location.href='\doctor\doctor_dashboard.php';</script>";
            } else {
                echo "<script>alert('Error booking appointment!');</script>";
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
    <title>Book Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Book Appointment</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Pet ID:</label>
                <input type="number" name="pet_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Appointment Date:</label>
                <input type="date" name="appointment_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Reason:</label>
                <textarea name="reason" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Book Appointment</button>
        </form>
    </div>
</body>
</html>
