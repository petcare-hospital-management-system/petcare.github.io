<?php
require_once '../../config/db.php';
session_start();

// Redirect if not logged in or not owner
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
  header("Location: ../auth/patient_login.php");
  exit();
}

// Initialize variables with default values
$user_id = $_SESSION['user_id'] ?? null;
$userName = $_SESSION['name'] ?? 'Owner';

$appointment_data = [];
$hospitalLogo = 'default_logo.png'; // Default logo

try {
  // Get settings
  $settingsQuery = $pdo->query("SELECT setting_key, setting_value FROM settings");
  $settingsData = $settingsQuery ? $settingsQuery->fetchAll(PDO::FETCH_KEY_PAIR) : [];

  // Set hospital info from settings
  if ($settingsData) {
    $hospitalTitle = $settingsData['title'] ?? $hospitalTitle;
    $hospitalLogo = $settingsData['logo_website'] ?? $hospitalLogo;
  }

  // Fetch pet count for the logged-in owner
  if ($user_id) {
    $petStmt = $pdo->prepare("SELECT COUNT(*) FROM pets WHERE owner_id = ?");
    if ($petStmt->execute([$user_id])) {
      $petTotal = $petStmt->fetchColumn() ;
    }
  }

  // Fetch appointments for the logged-in owner
  // Note: Adjust the column used in the WHERE clause if your appointments table uses a different column name (e.g., "user_id")
  if ($user_id) {
    $apptStmt = $pdo->prepare("SELECT appointment_date, vet_name, reason FROM appointments WHERE user_id = ? ORDER BY appointment_date ASC");
    if ($apptStmt->execute([$user_id])) {
      $appointment_data = $apptStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
  }
} catch (PDOException $e) {
  // Log error and show a generic message to the user
  error_log("Database error: " . $e->getMessage());
  $error = "A database error occurred. Please try again later.";
}
?>
<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8" />
    <title><?= htmlspecialchars($hospitalTitle) ?> | Owner Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
      :root {
        --primary-color: #2A5C82;
        --secondary-color: #5BA4E6;
        --accent-color: #FF914D;
        --light-bg: #f8f9fa;
      }

      body {
        background: var(--light-bg);
        min-height: 100vh;
      }

      .sidebar {
        width: 280px;
        height: 100vh;
        position: fixed;
        background: var(--primary-color);
        color: white;
        padding: 1rem;
        transition: all 0.3s;
      }

      .main-content {
        margin-left: 280px;
        padding: 2rem;
      }

      .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s;
      }

      .card:hover {
        transform: translateY(-5px);
      }

      .nav-link {
        color: rgba(255, 255, 255, 0.8);
        padding: 12px 20px;
        border-radius: 8px;
        margin: 4px 0;
        display: flex;
        align-items: center;
        gap: 12px;
      }

      .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
      }

      .header-logo {
        max-height: 45px;
      }

      .table thead {
        background: var(--primary-color);
        color: white;
      }

      .btn-primary {
        background: var(--secondary-color);
        border: none;
        padding: 8px 20px;
        border-radius: 8px;
      }

      .btn-primary:hover {
        background: #4A94D6;
      }

      @media (max-width: 768px) {
        .sidebar {
          margin-left: -280px;
        }

        .main-content {
          margin-left: 0;
        }
      }
    </style>
  </head>

  <body>
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="d-flex flex-column h-100">
        <div class="mb-4 text-center">
          <img src="../admin/uploads/logos/logo_website.png" alt="Clinic Logo" class="header-logo mb-3">
        </div>
       <h5 class="text-white">Owner Dashboard<h5>
        <nav class="nav flex-column">
          <a class="nav-link active" href="#">
            <i class="bi bi-speedometer2"></i> Dashboard
          </a>
          <a class="nav-link" href="view_my_pets.php">
            <i class="bi bi-heart-pulse"></i> My Pets
          </a>
          <a class="nav-link" href="../appointment/book_appointment.php">
            <i class="bi bi-calendar-plus"></i> Book Appointment
          </a>
          <a class="nav-link text-danger " href="../auth/logout.php">
            <i class="bi bi-box-arrow-right "></i> Logout
                        <!-- <a class="nav-link " href="../auth/logout.php">Logout</a> -->

          </a>
        </nav>
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Dashboard Header -->
      <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
          <h3 class="fw-bold">Welcome back, <?= htmlspecialchars($userName) ?>!</h3>
          <p class="text-muted mb-0">Here's your latest updates</p>
        </div>
        <div class="d-flex align-items-center gap-3">
          <div class="text-end">
            <small class="text-muted">Last Login</small>
            <div class="fw-bold"><?= date('Y-m-d H:i') ?></div>
          </div>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="row g-4 mb-5">
        <!-- Registered Pets Card -->
        <div class="col-xl-3 col-md-6">
          <div class="card bg-white">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-muted mb-2">Registered Pets</h6>
                  <h3 class="mb-0"><?= htmlspecialchars($petTotal) ?></h3>
                </div>
                <div class="bg-primary p-3 rounded-circle">
                  <i class="bi bi-paw fs-4 text-white"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Appointments Card -->
        <div class="col-xl-3 col-md-6">
          <div class="card bg-white">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-muted mb-2">Appointments</h6>
                    <h3 class="mb-0"><?= htmlspecialchars(count($appointment_data)) ?></h3>
                </div>
                <div class="bg-info p-3 rounded-circle">
                  <i class="bi bi-calendar-check fs-4 text-white"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Upcoming Appointments Table -->
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-4">Upcoming Appointments</h5>
          <?php if (!empty($appointment_data)): ?>
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Veterinarian</th>
                    <th>Reason</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($appointment_data as $appt): ?>
                    <tr>
                      <td class="fw-bold"><?= htmlspecialchars($appt['appointment_date']) ?></td>
                      <td><?= htmlspecialchars($appt['vet_name']) ?></td>
                      <td>
                        <span class="badge bg-primary rounded-pill"><?= htmlspecialchars($appt['reason']) ?></span>
                      </td>
                      <td>
                        <button class="btn btn-sm btn-outline-secondary">
                          <i class="bi bi-pencil"></i> Edit
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="text-center py-5">
              <i class="bi bi-calendar-x fs-1 text-muted"></i>
              <h5 class="mt-3">No upcoming appointments</h5>
              <p class="text-muted">Book your next appointment using the button below</p>
              <a href="../appointment/book_appointment.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Schedule Appointment
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>

</html>