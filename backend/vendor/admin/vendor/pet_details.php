<?php
// Include database connection
require_once '../includes/config.php'; // Connects and gives $pdo

// Fetch pet details by name from the database
$query = "SELECT * FROM pets";
$stmt = $pdo->prepare($query);
// $stmt->bindParam( PDO::PARAM_STR);
$stmt->execute();
$pets = $stmt->fetchAll();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Pet List</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .admin-wrapper {
            max-width: 1200px;
            margin: 40px auto;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }
        .card-header {
            background-color: #343a40;
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        table th {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>

<div class="admin-wrapper">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fa-solid fa-paw"></i> Pet List</h4>
            <a href="../../../vendor/pet/add_pet.php" class="btn btn-light btn-sm"><i class="fa-solid fa-plus"></i> Add Pet</a>
        </div>
        <div class="card-body">
            <?php if (!empty($pets)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Breed</th>
                                <th>Gender</th>
                                <th>Owner</th>
                                <th>Registered At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pets as $index => $pet): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($pet['pet_name']); ?></td>
                                    <td><?= htmlspecialchars($pet['age']); ?> years</td>
                                    <td><?= htmlspecialchars($pet['breed']); ?></td>
                                    <td><?= ucfirst($pet['gender']); ?></td>
                                    <td><?= htmlspecialchars($pet['owner_name']); ?></td>
                                    <td><?= date('Y-m-d', strtotime($pet['created_at'])); ?></td>
                                    <td>
                                        <a href="../../../vendor/pet/view_pet.php?id=<?= $pet['pet_id'] ?>" class="btn btn-sm btn-primary"><i class="fa-solid fa-eye"></i></a>
                                        <a href="../../../vendor/pet/edit_pet.php?id=<?= $pet['pet_id'] ?>" class="fa-solid fa-pen-to-square">
                                        <a href="../../../vendor/pet/delete_pet.php?id=<?= $pet['pet_id']?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this pet?');"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center">No pet records found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
