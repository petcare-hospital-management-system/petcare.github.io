<?php
include "../../config/db.php";
include "../../Include/navbar.php";

// Fetch medical history records
try {
    $query = "SELECT  pet_id, diagnosis, treatment, visit_date FROM medical_history ORDER BY visit_date DESC";
    $stmt = $pdo->query($query);
    $medical_history = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">   
         <h2 class="mb-4">Medical History</h2>
            <a href="add_medical_history.php" class="btn btn-success">+ Add Medical Record</a>
    </div> 

    <!-- Search Bar -->
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search records...">
        
    <!-- Medical History Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th onclick="sortTable(0)">Pet ID</th>
                <th onclick="sortTable(1)">Diagnosis</th>
                <th onclick="sortTable(2)">Treatment</th>
                <th onclick="sortTable(3)">Visit Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="medicalTable">
            <?php foreach ($medical_history as $row) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['pet_id']); ?></td>
                    <td><?= htmlspecialchars($row['diagnosis']); ?></td>
                    <td><?= htmlspecialchars($row['treatment']); ?></td>
                    <td><?= htmlspecialchars($row['visit_date']); ?></td>
                    <td>
                        <a href="edit_medical.php?id=<?= urlencode($row['pet_id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_medical.php?id=<?= urlencode($row['pet_id']); ?>" 
                            class="btn btn-danger btn-sm" 
                            onclick="return confirm('Are you sure you want to delete this medical history record?');">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
// Search Functionality
$(document).ready(function(){
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#medicalTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});

// Sorting Functionality
function sortTable(columnIndex) {
    var table = document.querySelector("table");
    var rows = Array.from(table.rows).slice(1);
    rows.sort((rowA, rowB) => {
        return rowA.cells[columnIndex].innerText.localeCompare(rowB.cells[columnIndex].innerText);
    });
    rows.forEach(row => table.appendChild(row));
}
</script>

</body>
</html>