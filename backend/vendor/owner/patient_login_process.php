<?php
session_start();
require_once '../../config/db.php'; // Update path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'owner'");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Store user info in session
        $_SESSION['user_id'] = $user['user_id'];  // or whatever your ID column is
        $_SESSION['role'] = 'owner';
        $_SESSION['name'] = $user['name'];

        header("Location: owner_dashboard.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Invalid email or password.";
        header("Location: Patient_login.php");
        exit();
    }
}
?>
