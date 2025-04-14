<?php
header("Content-Type: application/json");
include "../../config/db.php";

if (isset($_GET['pet_id'])) {
    $pet_id = $_GET['pet_id'];
    $sql = "SELECT * FROM medical_history WHERE pet_id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bind_param("i", $pet_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $medical_history = [];
    while ($row = $result->fetch_assoc()) {
        $medical_history[] = $row;
    }

    echo json_encode($medical_history);
} else {
    echo json_encode(["message" => "Pet ID is required"]);
}

$pdo->close();
?>
