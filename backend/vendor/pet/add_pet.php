<?php
include "../../config/db.php";
include "../../Include/navbar.php"; // Include navbar

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle other inputs like pet name, breed, etc.
    $petName = $_POST['pet_name'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $ownerId = $_SESSION['name']; // or user_id if that's the column

    $photoPath = null;

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $targetDir = 'pet photos/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $fileName = uniqid() . "_" . basename($_FILES['photo']['name']);
        $targetFile = $targetDir . $fileName;

        move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile);
        $photoPath = $targetFile;
    }

    $stmt = $pdo->prepare("INSERT INTO pets (pet_name, breed, age, gender, owner_id, photo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$petName, $breed, $age, $gender, $ownerId, $photoPath]);

    header("Location: my_pets.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Pet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Add New Pet</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Owner Name:</label>
            <input type="text" name="owner_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Pet Name:</label>
            <input type="text" name="pet_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Species:</label>
            <input type="text" name="species" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Breed:</label>
            <input type="text" name="breed" class="form-control">
        </div>
        <div class="mb-3">
            <label>Age:</label>
            <input type="number" name="age" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Gender:</label>
            <select name="gender" class="form-control">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div class="mb-3">
        <label for="photo" class="form-label">Pet Photo</label>
        <input type="file" name="photo" class="form-control" accept="image/*">
    </div>
        <button type="submit" class="btn btn-success">Add Pet</button>
    </form>
</div>
</body>
</html>