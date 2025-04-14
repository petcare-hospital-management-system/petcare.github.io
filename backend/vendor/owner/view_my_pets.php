<?php
require_once '../../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../auth/patient_login.php");
    exit();
}
$user_id = $_SESSION['user_id'] ?? null;
$userName = $_SESSION['name'] ?? 'Owner';
if ($user_id) {
    $petStmt = $pdo->prepare("SELECT COUNT(*) FROM pets WHERE owner_id = ?");
    if ($petStmt->execute([$user_id])) {
        $petTotal = $petStmt->fetchColumn();
    }

    $petsStmt = $pdo->prepare("SELECT * FROM pets WHERE owner_id = ?");
    if ($petsStmt->execute([$user_id])) {
        $pets = $petsStmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $pets = [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Pets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
    background: #f8f9fa;
}

.pet-card {
    transition: transform 0.2s;
}

.pet-card:hover {
    transform: scale(1.02);
}

.card-img-top {
    width: 100%;
    height: 200px;       /* You can adjust the height */
    object-fit: contain;
background-color: #f0f0f0; /* Optional: adds a light background if the image doesn't fill the box */
;
    display: block;
    margin: 0;           /* Remove any default margin */
    border-radius: 0.5rem 0.5rem 0 0;
}



    </style>
</head>
<body>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fa-solid fa-dog"></i> My Pets</h3>
        <a href="../owner/owner_dashboard.php" class="btn btn-outline-primary">Back to Dashboard</a>
    </div>

    <?php if ($pets): ?>
        <div class="row">
            <?php foreach ($pets as $pet): ?>
                <div class="col-md-4 mb-4">
                    <div class="card pet-card shadow-sm">
                    <?php
                         $photoSrc = !empty($pet['photo']) ? "../pet/" . $pet['photo'] : "../pet/pet photos/default.png";
                    ?>
                            <img src="<?= htmlspecialchars($photoSrc) ?>" class="card-img-top" alt="Pet Photo">
                            <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($pet['pet_name']) ?></h5>
                            <p class="card-text mb-1"><strong>Breed:</strong> <?= htmlspecialchars($pet['breed']) ?></p>
                            <p class="card-text mb-1"><strong>Age:</strong> <?= htmlspecialchars($pet['age']) ?> years</p>
                            <p class="card-text mb-2"><strong>Gender:</strong> <?= htmlspecialchars($pet['gender']) ?></p>

                            <a href="view_pet.php?pet_id=<?= $pet['pet_id'] ?>" class="btn btn-sm btn-secondary"><i class="fa fa-eye"></i> View medical_history</a>
                            <a href="../appointment/book_appointment.php?pet_id=<?= $pet['pet_id'] ?>" class="btn btn-sm btn-primary"><i class="fa fa-calendar"></i> Book</a>
                            <a href="../pet/edit_pet.php?pet_id=<?= $pet['pet_id'] ?>" class="btn btn-sm btn-warning"><i class="fa fa-pen"></i> Edit</a>
                            <a href="delete_pet.php?pet_id=<?= $pet['pet_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this pet?');"><i class="fa fa-trash"></i> Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            No pets found. <a href="add_pet.php" class="btn btn-success btn-sm ms-2">Add Pet</a>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
