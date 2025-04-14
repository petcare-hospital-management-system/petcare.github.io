<?php
session_start();
session_destroy(); // Destroy all sessions

// Redirect to login page after logout
header("Location: ../doctor/DoctorpagePatientpage.php");

?>
