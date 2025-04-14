<?php
require_once 'includes/config.php';
$settingsData = $pdo->query("SELECT setting_key, setting_value FROM settings")->fetchAll(PDO::FETCH_KEY_PAIR);

// You can now use like:
$hospitalTitle = $settingsData['title'] ?? 'PetCare Clinic';
$hospitalLogo = $settingsData['logo_website'] ?? 'default_logo.png';

session_start();
    // Check if the user is logged in and has the "admin" role
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
        // Redirect to login page or show an error message
        header("Location: login.php");
        exit();
    }

    $staffCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role IN ('doctor', 'receptionist')")->fetchColumn();
    $dogCount = $pdo->query("SELECT COUNT(*) FROM pets WHERE species = 'dog'")->fetchColumn();
    $catCount = $pdo->query("SELECT COUNT(*) FROM pets WHERE species = 'cat'")->fetchColumn();
    $petsCount = $pdo->query("SELECT COUNT(*) FROM pets ")->fetchColumn();
    $clientCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'owner'")->fetchColumn();
    $salesToday = $pdo->query("SELECT SUM(amount) FROM sales WHERE date = CURDATE()")->fetchColumn();

    $appointments = $pdo->query("SELECT appointment_date AS start, CONCAT(vet_name, ' - ', reason) AS title FROM appointments ORDER BY appointment_date ASC")->fetchAll();
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css' rel='stylesheet' />
        <style>
            #calendar {
                max-width: 900px;
                margin: 0 auto;
            }
        </style>
    </head>
    <body>
    <div class="d-flex">
        <!-- Sidebar -->
        
        <div class="bg-dark text-white p-3" style="width: 250px; height: 100vh;">
            <h4><img src="uploads\logos\logo_website.png" alt="logo" class="dark-logo" style="width:90%;height:90%;"></h4>

            <h5 class="text-center text-white">Welcome, Admin</h5>
                        <?php
            $current_page = basename($_SERVER['PHP_SELF']);
            ?>
          <?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<ul class="nav flex-column">
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'admin_dashboard.php' ? 'active' : ''; ?>" href="admin_dashboard.php">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'data_entries.php' ? 'active' : ''; ?>" href="vendor/data_entries.php">Data Entries</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'client_pet_owner.php' ? 'active' : ''; ?>" href="vendor/client_pet_owner.php">Client / Pet Owners</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'pet_details.php' ? 'active' : ''; ?>" href="vendor/pet_details.php">Pet Details</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'appointments.php' ? 'active' : ''; ?>" href="vendor/appointments.php">Appointments</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'consultation.php' ? 'active' : ''; ?>" href="vendor/consultation.php">Consultation</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'vaccination.php' ? 'active' : ''; ?>" href="vendor/vaccination.php">Vaccination</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'laboratory.php' ? 'active' : ''; ?>" href="vendor/laboratory.php">Laboratory</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'surgery_list.php' ? 'active' : ''; ?>" href="vendor/surgery_list.php">Surgery</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'hospitalization_list.php' ? 'active' : ''; ?>" href="vendor/hospitalization_list.php">Hospitalization</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'client_management.php' ? 'active' : ''; ?>" href="vendor/client_management.php">Client Management</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'email_enquiries.php' ? 'active' : ''; ?>" href="vendor/email_enquiries.php">Email / Enquiries</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'user_management.php' ? 'active' : ''; ?>" href="vendor/user_management.php">User Management</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'users_staff.php' ? 'active' : ''; ?>" href="vendor/users_staff.php">Users (Staff)</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'pets_report.php' ? 'active' : ''; ?>" href="vendor/pets_report.php">Pets Reports</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'vaccination_report.php' ? 'active' : ''; ?>" href="vendor/vaccination_report.php">Vaccination Reports</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'settings.php' ? 'active' : ''; ?>" href="vendor/settings.php">Settings</a></li>
    <li class="nav-item">
            <a class="nav-link text-danger" href="../auth/logout.php">Logout</a>
        </li>
</ul>
        </div>

        <!-- Main Content -->
        <div class="p-4 flex-grow-1">
            <h2>Dashboard</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">Staffs</h5>
                            <p class="card-text fs-4"><?php echo $staffCount; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Dogs</h5>
                            <p class="card-text fs-4"><?php echo $dogCount; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">Cats</h5>
                            <p class="card-text fs-4"><?php echo $catCount; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                <div class="card text-white" style="background-color: gray;">
                <div class="card-body">
                            <h5 class="card-title">pets</h5>
                            <p class="card-text fs-4"><?php echo $petsCount; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h5 class="card-title">Clients</h5>
                            <p class="card-text fs-4"><?php echo $clientCount; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mt-4">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <h5 class="card-title">Sales Today</h5>
                            <p class="card-text fs-4">Rs: <?php echo number_format($salesToday, 2); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointments Calendar -->
            <div class="mt-5">
                <h4>Appointments Calendar</h4>
                <div id='calendar'></div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: <?php echo json_encode($appointments); ?>,
                height: 500
            });
            calendar.render();
        });
    </script>
    </body>
    </html>