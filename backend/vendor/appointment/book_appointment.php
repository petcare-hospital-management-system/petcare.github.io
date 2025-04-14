<?php
// Include the PDO connection details.
$host = 'localhost';
$db = 'petcare';
$user = 'root'; // or your DB username
$pass = '';     // or your DB password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

session_start();
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die('You must be logged in to book an appointment.');
}


// Retrieve pet_id from URL (GET parameter)
$pet_id = isset($_GET['pet_id']) ? trim($_GET['pet_id']) : '';
// Initialize a variable to hold messages
$message = "";

// Process the form submission when the form is posted.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve pet_id from POST (hidden field) to ensure it's carried over
    $pet_id = isset($_POST['pet_id']) ? trim($_POST['pet_id']) : '';
    $reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';
    // Retrieve additional field: appointment date.
    $appointment_date = isset($_POST['appointment_date']) ? trim($_POST['appointment_date']) : '';

    // Validate that required fields are provided.
    if ($pet_id !== '' && $reason !== '' && $appointment_date !== '') {
        // Prepare the SQL insert statement using PDO
        $sql = "INSERT INTO appointments (pet_id,user_id, reason, appointment_date) VALUES (:pet_id,:user_id, :reason, :appointment_date)";
        $stmt = $pdo->prepare($sql);

        try {
            // Bind the parameters and execute the statement.
            $stmt->execute([
    ':pet_id' => $pet_id,
    ':user_id' => $user_id,
    ':reason' => $reason,
    ':appointment_date' => $appointment_date,
]);

            $message = "Appointment booked successfully!";
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    } else {
        $message = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Book Appointment</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 2em;
            }

            form {
                max-width: 500px;
                padding: 1em;
                border: 1px solid #ccc;
                border-radius: 1em;
            }

            label {
                display: block;
                margin-top: 1em;
            }

            input[type="text"],
            input[type="date"] {
                width: 100%;
                padding: 0.5em;
            }

            input[type="submit"] {
                margin-top: 1em;
                padding: 0.7em 1.5em;
                background-color: #4CAF50;
                border: none;
                color: white;
                border-radius: 0.3em;
                cursor: pointer;
            }

            .message {
                font-weight: bold;
                margin-bottom: 1em;
            }
        </style>
    </head>

    <body>
        <h1>Book Your Appointment</h1>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <!-- Hidden input to pass the pet_id from the URL through the form -->
            <input type="hidden" name="pet_id" value="<?php echo htmlspecialchars($pet_id); ?>">

            <p>
<strong>Booking for: <?php echo htmlspecialchars($petName ?? 'Pet ID: ' . $pet_id); ?></strong>
            </p>

            <label for="reason">Reason for Appointment:</label>
            <input type="text" id="reason" name="reason" required>

            <label for="appointment_date">Appointment Date:</label>
            <input type="date" id="appointment_date" name="appointment_date" required>

            <!-- Add any additional fields as needed -->

            <input type="submit" value="Book Appointment">
        </form>
    </body>

</html>