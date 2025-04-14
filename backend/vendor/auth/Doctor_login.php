<?php
session_start();
require_once "../../config/db.php"; // Ensure database connection

$error_message = ""; // Variable to store error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Prepare SQL statement to fetch user details
    $sql = "SELECT user_id, email, password, role FROM users WHERE LOWER(email) = LOWER(:email)";
    $stmt = $pdo->prepare($sql); // Use $pdo instead of $conn

    if ($stmt) {
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists
        if ($user) {
            // Verify password
            if (password_verify($password, $user["password"])) {
                // Set session variables
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["role"] = $user["role"];
                $_SESSION["email"] = $user["email"];

                // Redirect based on role
                switch ($user["role"]) {
                    case 'admin':
                        header("Location: ../admin/admin_dashboard.php");
                        break;
                    case 'doctor':
                        header("Location: ../doctor/doctor_dashboard.php");
                        break;
                    case 'receptionist':
                        header("Location: reception_dashboard.php");
                        break;
                    default:
                        header("Location: doctor_dashboard.php");
                }
                exit();
            } else {
                $error_message = "Invalid email or password.";
            }
        } else {
            $error_message = "No account found with this email.";
        }
    } else {
        $error_message = "Database error. Please try again later.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../doctor/doctor.css">
    <title>Login</title>
</head>
<body>
    <form action="" method="POST"> <!-- Fixed form action -->
        <div class="container">
            <h2>Doctor Login</h2> 
            <?php if (!empty($error_message)) { echo "<p style='color: red;'>$error_message</p>"; } ?>
            <label for="email"><b>Email</b></label>
            <input type="email" name="email" placeholder="Enter Email" required>
            
            <label for="password"><b>Password</b></label>
            <input type="password" name="password" placeholder="Enter Password" required> 
            
            <button class="btn-login" type="submit">Login</button> 
            <p class="signup">
                Forgot your password? <a href="#" class="link">Reset</a>
            </p> 
        </div> 
        <hr/> 
    </form>
</body>

</html>
