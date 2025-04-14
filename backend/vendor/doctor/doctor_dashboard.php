<?php
session_start();
include "../../config/db.php";
include "../../Include/navbar.php";

// Check if the user is logged in and has the "doctor" role
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "doctor") {
    // Redirect to login page or show an error message
    header("Location: ../auth/Doctor_login.php");
    exit();
}

// Fetch all pets from the database
try {
    $stmt = $pdo->query("SELECT * FROM pets");
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Owner Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 50px;
        }
        .table {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Welcome to Your Pet Dashboard</h2>

    <div class="d-flex justify-content-between align-items-center">
        <h4>Your Pets</h4>
        <a href="../pet/add_pet.php" class="btn btn-success">+ Add Pet</a>
    </div>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Species</th>
                <th>Breed</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pets as $row) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['pet_id']); ?></td>
                    <td><?= htmlspecialchars($row['pet_name']); ?></td>
                    <td><?= htmlspecialchars($row['species']); ?></td>
                    <td><?= htmlspecialchars($row['breed']); ?></td>
                    <td><?= htmlspecialchars($row['age']) . " years"; ?></td>
                    <td><?= htmlspecialchars($row['gender']); ?></td>
                    <td>
            <a href="../medical_history/medical_history.php?pet_id=<?= $row['pet_id']; ?>" class="btn btn-info btn-sm">Medical History</a>
            <a href="../appointment/appointments.php?pet_id=<?= $row['pet_id']; ?>" class="btn btn-primary btn-sm">Appointments</a>
            <a href="../vaccination/vaccinations.php?pet_id=<?= $row['pet_id']; ?>" class="btn btn-warning btn-sm">Vaccination History</a>
            <a href="../vaccination/add_vaccination.php?pet_id=<?= $row['pet_id']; ?>" class="btn btn-success btn-sm">Add Vaccination</a>
            <a href="../pet/delete_pet.php?pet_id=<?= $row['pet_id']; ?>" class="btn btn-light btn-sm">
                <img src="../../../frontend/assets/images/delete_icon.png" style="height: 20px;" alt="Delete">
            </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    
</div>

</body>
</html>