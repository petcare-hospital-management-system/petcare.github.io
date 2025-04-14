<?php
include "../../config/db.php";
session_start();

$appointment_id = $_GET['id'] ?? null;

if (!$appointment_id) {
    die("Error: No appointment selected. Please go back and select an appointment.");
}

try {
    // Delete the appointment from the database
    $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = :id");
    $stmt->bindParam(":id", $appointment_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Appointment deleted successfully!'); window.location.href='appointments.php';</script>";
    } else {
        echo "<script>alert('Error deleting appointment!'); window.location.href='appointments.php';</script>";
    }
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}
?>