<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<ul class="nav flex-column">
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'data_entries.php' ? 'active' : ''; ?>" href="data_entries.php">Data Entries</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'client_pet_owner.php' ? 'active' : ''; ?>" href="client_pet_owner.php">Client / Pet Owner</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'pet_details.php' ? 'active' : ''; ?>" href="pet_details.php">Pet Details</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'appointments.php' ? 'active' : ''; ?>" href="appointments.php">Appointments</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'consultation.php' ? 'active' : ''; ?>" href="consultation.php">Consultation</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'vaccination.php' ? 'active' : ''; ?>" href="vaccination.php">Vaccination</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'laboratory.php' ? 'active' : ''; ?>" href="laboratory.php">Laboratory</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'surgery.php' ? 'active' : ''; ?>" href="surgery.php">Surgery</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'hospitalization.php' ? 'active' : ''; ?>" href="hospitalization.php">Hospitalization</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'pet_lodge.php' ? 'active' : ''; ?>" href="pet_lodge.php">Pet Lodge</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'client_management.php' ? 'active' : ''; ?>" href="client_management.php">Client Management</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'email_enquiries.php' ? 'active' : ''; ?>" href="email_enquiries.php">Email / Enquiries</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'user_management.php' ? 'active' : ''; ?>" href="user_management.php">User Management</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'users_staff.php' ? 'active' : ''; ?>" href="users_staff.php">Users (Staff)</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'report.php' ? 'active' : ''; ?>" href="report.php">Report</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'pets_report.php' ? 'active' : ''; ?>" href="pets_report.php">Pets Report</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'vaccination_report.php' ? 'active' : ''; ?>" href="vaccination_report.php">Vaccination Report</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo $current_page == 'settings.php' ? 'active' : ''; ?>" href="settings.php">Settings</a></li>
</ul>