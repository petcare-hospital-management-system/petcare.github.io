<?php
// Include database connection
require_once "../../config/db.php"; // ensure this path is correct

// Fetch pet details by name from the database
$query = "SELECT * FROM pets";
$stmt = $pdo->prepare($query);
// $stmt->bindParam( PDO::PARAM_STR);
$stmt->execute();
$pet = $stmt->fetch();

if (!$pet) {
    echo "Pet not found.";
    exit;
}
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional: Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
        }
        .card {
            border-radius: 1rem;
        }
        .table th {
            width: 30%;
            background-color: #f1f1f1;
        }
        .back-btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center rounded-top">
                        <h3><i class="fa-solid fa-paw"></i> Pet Details</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <th><i class="fa-solid fa-dog"></i> Name</th>
                                <td><?php echo htmlspecialchars($pet['pet_name']); ?></td>
                            </tr>
                            <tr>
                                <th><i class="fa-solid fa-hourglass-half"></i> Age</th>
                                <td><?php echo htmlspecialchars($pet['age']); ?> years</td>
                            </tr>
                            <tr>
                                <th><i class="fa-solid fa-dna"></i> Breed</th>
                                <td><?php echo htmlspecialchars($pet['breed']); ?></td>
                            </tr>
                            <tr>
                                <th><i class="fa-solid fa-user"></i> Owner</th>
                                <td><?php echo htmlspecialchars($pet['owner_name']); ?></td>
                            </tr>
                        </table>
                        <a href="pet_list.php" class="btn btn-outline-primary w-100 back-btn">
                            <i class="fa-solid fa-arrow-left"></i> Back to Pet List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
śś