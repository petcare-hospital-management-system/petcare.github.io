<?php
session_start();
include "../../config/db.php";
include "../../Include/navbar.php";

$pet_id = $_GET['pet_id'] ?? null;
if (!$pet_id || !is_numeric($pet_id)) {
    die("Error: Invalid or missing pet ID.");
}

// Fetch appointments for the pet
try {
    $stmt = $pdo->prepare("SELECT * FROM appointments WHERE pet_id = :pet_id");
    $stmt->bindParam(":pet_id", $pet_id, PDO::PARAM_INT);
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#appointmentsTable').DataTable();

            $('#filterStatus').on('change', function() {
                let filterValue = $(this).val();
                if (filterValue === "all") {
                    table.search('').draw();
                } else {
                    table.column(2).search(filterValue, true, false).draw();
                }
            });
        });
    </script>

    <style>
        .status-completed { color: green; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
        .status-cancelled { color: red; font-weight: bold; }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Appointments</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Upcoming & Past Appointments</h4>
        <select id="filterStatus" class="form-select w-auto">
            <option value="all">All</option>
            <option value="Upcoming">Upcoming</option>
            <option value="Past">Past</option>
        </select>
        <a href="add_appointment.php?pet_id=<?= htmlspecialchars($pet_id); ?>" class="btn btn-success">+ Add Appointment</a>
    </div>

    <table id="appointmentsTable" class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Appointment Date</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        foreach ($appointments as $row) {
            // Defensive checks
            $status = $row['status'] ?? 'Pending';
            $appointment_date = $row['appointment_date'] ?? 'N/A';
            $reason = $row['reason'] ?? 'N/A';

            // Status color classes
            $statusClass = "";
            if ($status === "Completed") $statusClass = "status-completed";
            elseif ($status === "Pending") $statusClass = "status-pending";
            elseif ($status === "Cancelled") $statusClass = "status-cancelled";

            // Determine if appointment is upcoming or past
            $appointmentTimestamp = strtotime($appointment_date);
            $today = strtotime(date("Y-m-d"));
            $isUpcoming = ($appointmentTimestamp >= $today) ? "Upcoming" : "Past";
        ?>
            <tr>
                <td><?= htmlspecialchars($appointment_date); ?></td>
                <td><?= htmlspecialchars($reason); ?></td>
                <td class="<?= $statusClass; ?>"><?= htmlspecialchars($status); ?> (<?= $isUpcoming; ?>)</td>
                <td>
                    <a href="edit_appointment.php?id=<?= htmlspecialchars($row['pet_id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_appointment.php?id=<?= htmlspecialchars($row['pet_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this appointment?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
