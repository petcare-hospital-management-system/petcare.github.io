<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "petcare";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if $pdo is defined and initialized
if (!isset($pdo)) {
    die("❌ Database connection not established. Please check config.php.");
}

// Hash the password before storing it
$password = password_hash('admin123', PASSWORD_DEFAULT);

try {
    $sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
    $stmt = $pdo->prepare($sql);

    // Bind parameters to prevent SQL injection
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->bindParam(":password", $password, PDO::PARAM_STR);
    $stmt->bindParam(":role", $role, PDO::PARAM_STR);

    // Define the values for the placeholders
    $username = 'zaid'; // Example username
    $email = 'zaid@example.com';
    $password = password_hash('admin123', PASSWORD_DEFAULT); // Hash the password
    // Set the role to 'admin'
    $role = 'staff'; // Example role

    $stmt->execute();

    echo "✅ Admin user added successfully!"; 
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>