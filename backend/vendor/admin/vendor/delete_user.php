<?php
require_once '../includes/config.php';
session_start();

// Only allow admins
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request.");
}

$user_id = (int) $_GET['id'];

// ✅ FIX 1: Use correct query to fetch the specific user by ID
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ? AND role != 'owner'");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("Staff user not found or not allowed to delete.");
}

// ✅ FIX 2: Delete by ID properly
$delete = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
if ($delete->execute([$user_id])) {
    header("Location: user_management.php");
    exit();
} else {
    echo "Failed to delete staff user.";
}
?>
