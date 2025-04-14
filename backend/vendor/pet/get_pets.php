<?php
header("Content-Type: application/json");
include "../../config/db.php";

$sql = "SELECT * FROM pets";
$result = $pdo->query($sql);

$pets = [];

while ($row = $result->fetch_assoc()) {
    $pets[] = $row;
} 

echo json_encode($pets);

$pdo->close();
?> 

