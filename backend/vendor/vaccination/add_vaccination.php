<?php
include "../../config/db.php";
include "../../Include/navbar.php"; // Include the navbar
session_start();

$pet_id = isset($_GET['pet_id']) ? $_GET['pet_id'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_id = $_POST['pet_id'];
    $vaccine_name = $_POST['vaccine_name'];
    $vaccination_date = $_POST['vaccination_date'];
    $next_due_date = $_POST['next_due_date'];

    if (!empty($pet_id) && !empty($vaccine_name) && !empty($vaccination_date)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO vaccinations (pet_id, vaccine_name, vaccination_date, next_due_date) VALUES (:pet_id, :vaccine_name, :vaccination_date, :next_due_date)");
            $stmt->bindParam(":pet_id", $pet_id, PDO::PARAM_INT);
            $stmt->bindParam(":vaccine_name", $vaccine_name, PDO::PARAM_STR);
            $stmt->bindParam(":vaccination_date", $vaccination_date, PDO::PARAM_STR);
            $stmt->bindParam(":next_due_date", $next_due_date, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "<script>alert('Vaccination booked successfully!'); window.location.href='vaccinations.php?pet_id=$pet_id';</script>";
            } else {
                echo "<script>alert('Error booking vaccination');</script>";
            }
        } catch (PDOException $e) {
            die("Database query failed: " . $e->getMessage());
        }
    } else {
        echo "<script>alert('Please fill all required fields.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vaccination</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add Vaccination</h2>
        <form method="POST">
            <input type="hidden" name="pet_id" value="<?= htmlspecialchars($pet_id); ?>">
            
            <div class="mb-3">
                <label class="form-label">Vaccine Name</label>
                <input type="text" name="vaccine_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Vaccination Date</label>
                <input type="date" name="vaccination_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Next Due Date (Optional)</label>
                <input type="date" name="next_due_date" class="form-control">
            </div>

            <button type="submit" class="btn btn-success">Add Vaccination</button>
            <a href="../doctor/doctor_dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>