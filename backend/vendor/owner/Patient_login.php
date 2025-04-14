<?php
session_start();
require_once "../../config/db.php"; // ensure this path is correct

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'owner'");
            $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = 'owner';
            $_SESSION['name'] = $user['name'];

            header("Location: owner_dashboard.php");
            exit();
        }
    }

    // If login fails
    $_SESSION['login_error'] = "Invalid email or password.";
    header("Location: Patient_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="patient.css">
    <title>Login</title>
</head>
<body>
<?php if (!empty($_SESSION['login_error'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
    </div>
<?php endif; ?>

<form action="patient_login_process.php" method="POST">
    <div class="container">
        <h2>Patient Login</h2> 
        <label for=""><b>Email</b></label>
<input type="email" name="email" placeholder="" required>

<label for=""><b>Password</b></label>
<input type="password" name="password" placeholder="" required>


        <button class="btn-login" type="submit">Login</button> 

        <p class="signup">
            Forgot your password? <a href="#" class="link">Reset</a>
        </p> 
    </div> 
    <hr/> 
    <a class="btn-external-login">
        <img src="google.png" alt=""/><span>Continue with Google</span>
    </a> 
    <a class="btn-external-login">
        <img src="fb.png" alt=""/><span>Continue with Facebook</span>
    </a>
</form>

</body>
</html>