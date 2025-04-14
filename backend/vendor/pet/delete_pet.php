<?php
include "../../config/db.php"; // Ensure this file initializes $pdo

if (!isset($pdo)) {
    die("Database connection not established.");
}

if (isset($_GET['pet_id'])) {
    $pet_id = $_GET['pet_id'];

    try {
        // Prepare the DELETE query
        $sql = "DELETE FROM pets WHERE pet_id = :pet_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":pet_id", $pet_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>alert('Pet deleted successfully!'); window.location.href='../doctor/doctor_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error deleting pet!'); window.location.href='../doctor/doctor_dashboard.php';</script>";
        }
    } catch (PDOException $e) {
        die("Database query failed: " . $e->getMessage());
    }
}
?>