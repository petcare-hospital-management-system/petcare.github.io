<?php
require_once '../includes/config.php';

session_start();
// Only allow admin to delete
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Check if ID is passed
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $client_id = $_GET['id'];

    // Optional: Check if client exists and has role 'owner'
    $checkStmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ? AND role = 'owner'");
    $checkStmt->execute([$client_id]);

    if ($checkStmt->rowCount() > 0) {
        // Delete the client
        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->execute([$client_id]);

        // Optional: Delete their pets too (if you want cascading delete)
        // $pdo->prepare("DELETE FROM pets WHERE owner_id = ?")->execute([$client_id]);

        header("Location: client_management.php?deleted=1");
        exit();
    } else {
        echo "Client not found or unauthorized.";
    }
} else {
    echo "Invalid client ID.";
}
?>
