<?php
include 'config.php'; // Database connection

header('Content-Type: application/json');

$sql = "SELECT id, appointment_date AS start, purpose AS title FROM appointments";
$result = $pdo->query($sql);

if (!$result) {
    die(json_encode(["error" => "Query Failed: " . $pdo->error])); // Debugging SQL error
}

$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = [
        "id"    => $row['id'],
        "title" => $row['title'],
        "start" => date("Y-m-d\TH:i:s", strtotime($row['start'])) // Convert format
    ];
}

echo json_encode($appointments);
?>
