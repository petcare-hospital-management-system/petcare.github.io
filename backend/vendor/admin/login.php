<?php
session_start();
require_once("../../config/db.php");

$error_message = ""; // Variable to store error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    try {
        // Prepare SQL statement
        $sql = "SELECT username, password, role FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);

        // Bind parameters and execute
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();

        // Check if user exists
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify password
            if (password_verify($password, $user["password"])) {
                // Set session variables
                $_SESSION["email"] = $user["username"];
                $_SESSION["role"] = $user["role"];

                // Allow only admin to log in
                if ($user["role"] === "admin") {
                    header("Location: admin_dashboard.php");
                    exit();
                } else {
                    $error_message = "Access denied! Only admins can log in.";
                }
            } elseif (empty($password)) {
                $error_message = "Password cannot be empty!";
            } elseif (strlen($password) < 8) {
                $error_message = "Password must be at least 8 characters long!";
            } elseif (!preg_match("/[A-Z]/", $password)) {
                $error_message = "Password must contain at least one uppercase letter!";
            } elseif (!preg_match("/[a-z]/", $password)) {
                $error_message = "Password must contain at least one lowercase letter!";
            } elseif (!preg_match("/[0-9]/", $password)) {
                $error_message = "Password must contain at least one number!";
            } else {
                $error_message = "Incorrect password!";
            }
        } else {
            $error_message = "User not found! Please check your username and try again.";
        }
    } catch (PDOException $e) {
        $error_message = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
        <h2 class="text-center mb-4">Login</h2>

        <!-- Show error message -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username:</label>
                <input type="text" name="username" class="form-control" placeholder="Enter your username" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</body>
</html>