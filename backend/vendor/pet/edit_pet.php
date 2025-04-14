<?php 
include "../../config/db.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Secure fetch
    $stmt = $pdo->prepare("SELECT * FROM pets WHERE pet_id = ?");
    $stmt->execute([$id]);
    $pet = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pet) {
        // Redirect if pet not found
        header("Location: ../admin/vendor/pet_details.php?error=PetNotFound");
        exit();
    }
} else {
    // Redirect if id is missing
    header("Location: ../admin/vendor/pet_details.php?error=InvalidID");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $owner_name = $_POST['owner_name'];
    $pet_name = $_POST['pet_name'];
    $species = $_POST['species'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];

    // Photo logic
    $photoPath = $pet['photo']; // keep existing photo unless replaced

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../pet/pet photos/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Delete old file if exists
        if (!empty($photoPath) && file_exists($uploadDir . basename($photoPath))) {
            unlink($uploadDir . basename($photoPath));
        }

        $extension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $filename = uniqid('pet_', true) . '.' . $extension;
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
            $photoPath = 'pet photos/' . $filename; // Save relative path
        }
    }

    $sql = "UPDATE pets SET owner_name = ?, pet_name = ?, species = ?, breed = ?, age = ?, gender = ?, photo = ? WHERE pet_id = ?";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$owner_name, $pet_name, $species, $breed, $age, $gender, $photoPath, $id])) {
        echo "<div class='alert alert-success text-center'>Pet updated successfully. <a href='../admin/vendor/pet_details.php' class='btn btn-sm btn-success'>Go Back</a></div>";
    } else {
        echo "<div class='alert alert-danger text-center'>Error updating pet.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Pet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Edit Pet</h4>
            </div>
            <div class="card-body">
            <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Pet Photo</label>
            <input type="file" name="photo" class="form-control" accept="image/*">
<?php if (!empty($pet['photo'])): ?>
    <img src="../pet/<?= htmlspecialchars($pet['photo']) ?>" class="mt-2" style="height: 120px; border-radius: 0.5rem;">
<?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Owner Name</label>
            <input type="text" name="owner_name" class="form-control" value="<?= htmlspecialchars($pet['owner_name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Pet Name</label>
            <input type="text" name="pet_name" class="form-control" value="<?= htmlspecialchars($pet['pet_name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Species</label>
            <input type="text" name="species" class="form-control" value="<?= htmlspecialchars($pet['species']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Breed</label>
            <input type="text" name="breed" class="form-control" value="<?= htmlspecialchars($pet['breed']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Age</label>
            <input type="number" name="age" class="form-control" value="<?= htmlspecialchars($pet['age']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select">
                <option value="Male" <?= $pet['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $pet['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
            </select>
        </div>
        <div class="d-flex justify-content-between">
            <a href="../admin/vendor/pet_details.php" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Pet</button>
        </div>
        
    </form>
</div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
