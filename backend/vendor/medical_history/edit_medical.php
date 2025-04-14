<?php
include "../../config/db.php"; // Include the database connection
// Ensure the database connection is established correctly
if (!isset($pdo)) {
    die("Database connection not established.");
}
// Include the navbar for navigation                                                                     
include "../../Include/navbar.php"; // Include the navbar
session_start();

$record_id = $_GET['id'] ?? null;

// Validate the record ID
if ($record_id === null || !is_numeric($record_id)) {
    echo "<script>alert('Invalid or missing record ID.'); window.location.href='medical_history.php';</script>";
    exit;
}

// Check if this is an edit or add operation
$is_edit = !empty($record_id);

if ($is_edit) {
    // UPDATE query
    $stmt = $pdo->prepare("UPDATE medical_history 
        SET pet_id = :pet_id, diagnosis = :diagnosis, treatment = :treatment, visit_date = :visit_date 
        WHERE record_id = :record_id");

    $stmt->bindParam(":record_id", $record_id, PDO::PARAM_INT);
} else {
    // INSERT query
    $stmt = $pdo->prepare("INSERT INTO medical_history 
        (pet_id, diagnosis, treatment, visit_date) 
        VALUES (:pet_id, :diagnosis, :treatment, :visit_date)");
}


// Handle form submission for both adding and editing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_id = $_POST["pet_id"];
    $diagnosis = $_POST["diagnosis"];
    $treatment = $_POST["treatment"];
    $visit_date = $_POST["visit_date"];

    if (!empty($pet_id) && !empty($diagnosis) && !empty($treatment) && !empty($visit_date)) {
        try {
            if ($is_edit) {
                // Update existing record
                $stmt = $pdo->prepare("UPDATE medical_history SET pet_id = :pet_id, diagnosis = :diagnosis, treatment = :treatment, visit_date = :visit_date WHERE record_id = :record_id");
                $stmt->bindParam(":record_id", $record_id, PDO::PARAM_INT);
            } else {
                // Add new record
                $stmt = $pdo->prepare("INSERT INTO medical_history (pet_id, diagnosis, treatment, visit_date) VALUES (:pet_id, :diagnosis, :treatment, :visit_date)");
            }

            $stmt->bindParam(":pet_id", $pet_id, PDO::PARAM_INT);
            $stmt->bindParam(":diagnosis", $diagnosis, PDO::PARAM_STR);
            $stmt->bindParam(":treatment", $treatment, PDO::PARAM_STR);
            $stmt->bindParam(":visit_date", $visit_date, PDO::PARAM_STR);
            

            if ($stmt->execute()) {
                $message = $is_edit ? "Medical history updated successfully!" : "Medical history added successfully!";
                echo "<script>alert('$message'); window.location.href='medical_history.php';</script>";
            } else {
                echo "<script>alert('Error saving medical history!');</script>";
            }
        } catch (PDOException $e) {
            die("Database query failed: " . $e->getMessage());
        }
    } else {
        echo "<script>alert('All fields are required!');</script>";
    }
}
?>