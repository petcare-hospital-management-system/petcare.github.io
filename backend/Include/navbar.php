<?php
require_once '../../config/db.php';

// Fetch logo path from settings table
$stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = 'logo_website'");
$stmt->execute();
$logoPath = $stmt->fetchColumn();
$logoPath = $logoPath ?: '../vendor/admin/uploads/logos/logo_website.png'; // fallback vendor/admin/uploads/logos/logo_website.png
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="../doctor/doctor_dashboard.php">
        <h4><img src="../../vendor/admin/uploads\logos\logo_website.png" alt="logo" class="dark-logo" style="width:90%;height:90%;"></h4>

        </a>
        <!-- Rest of navbar... -->

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="../doctor/doctor_dashboard.php">Dashboard</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="../pet/add_pet.php">Add Pet</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="../medical_history/medical_history.php">Medical History</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="../vaccination/vaccinations.php">Vaccinations</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="../appointment/appointments.php">Appointments</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-danger" href="../auth/logout.php">Logout</a>
        </li>
    </ul>
</div>

    </div>
</nav>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>