<?php
header("Content-Type: application/json");
include "../../config/db.php";

if (isset($_GET['pet_id'])) {
    $pet_id = $_GET['pet_id'];
    $sql = "SELECT * FROM vaccinations WHERE pet_id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bind_param("i", $pet_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $vaccinations = [];
    while ($row = $result->fetch_assoc()) {
        $vaccinations[] = $row;
    }

    echo json_encode($vaccinations);
} else {
    echo json_encode(["message" => "Pet ID is required"]);
}

$pdo->close();
?>
