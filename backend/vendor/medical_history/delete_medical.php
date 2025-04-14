<?php
// Include database connection
include "../../config/db.php";

// Check if the request method is POST and the medical history ID is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    try {
        // Prepare the SQL statement to delete the medical history record
        $query = "DELETE FROM medical_history WHERE id = :id";
        $stmt = $pdo->prepare($query);

        // Bind the ID parameter
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        // Execute the query
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Medical history record deleted successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete medical history record."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>