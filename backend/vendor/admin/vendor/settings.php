<?php
require_once '../includes/config.php';
session_start();

// Ensure admin access
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Helper to save or update a setting
function saveSetting($pdo, $key, $value) {
    $stmt = $pdo->prepare("REPLACE INTO settings (setting_key, setting_value) VALUES (:key, :value)");
    $stmt->execute([':key' => $key, ':value' => $value]);
}

// Helper to save a logo and return its path
function saveLogo($inputName, $prefix, $existingPath = null) {
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $extension = strtolower(pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION));
    $fileName = $prefix . '_' . time() . '.' . $extension;
    $uploadDir = 'uploads/logos/';
    $uploadPath = $uploadDir . $fileName;

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $uploadPath)) {
        // Delete old file if it exists and is different
        if ($existingPath && file_exists($existingPath) && $existingPath !== $uploadPath) {
            unlink($existingPath);
        }
        return $uploadPath;
    }

    return false;
}
// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save basic text settings
    $fields = [
        'title', 'short_title', 'company_name', 'company_address', 'company_phone',
        'currency_code', 'currency_symbol', 'email_sender_name', 'email_driver',
        'email_host', 'email_port', 'email_username', 'email_password', 'email_encryption'
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            saveSetting($pdo, $field, $_POST[$field]);
        }
    }

    // Save logos
    $logos = [
        'logo_website' => 'logo_website',
        'logo_login' => 'logo_login',
        'logo_invoice' => 'logo_invoice',
    ];

    foreach ($logos as $inputName => $prefix) {
        $existing = $settings[$inputName] ?? null;
        $path = saveLogo($inputName, $prefix, $existing);
        if ($path) {
            saveSetting($pdo, $inputName, $path);
        }
        }
        

    header("Location: settings.php?success=1");
    exit();
}

// Load current settings
$stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
$settings = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hospital Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2>Hospital Settings</h2>
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Settings saved successfully!</div>
    <?php endif; ?>

    <form action="settings.php" method="POST" enctype="multipart/form-data">
        <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button">General</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="logos-tab" data-bs-toggle="tab" data-bs-target="#logos" type="button">Logos & Branding</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button">Email</button>
            </li>
        </ul>

        <div class="tab-content border p-4 bg-white">
            <!-- General -->
            <div class="tab-pane fade show active" id="general" role="tabpanel">
                <div class="mb-3">
                    <label class="form-label">Hospital Title</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($settings['title'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Short Title</label>
                    <input type="text" name="short_title" class="form-control" value="<?= htmlspecialchars($settings['short_title'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="company_name" class="form-control" value="<?= htmlspecialchars($settings['company_name'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Company Address</label>
                    <input type="text" name="company_address" class="form-control" value="<?= htmlspecialchars($settings['company_address'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Company Phone</label>
                    <input type="text" name="company_phone" class="form-control" value="<?= htmlspecialchars($settings['company_phone'] ?? '') ?>">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Currency Code</label>
                        <input type="text" name="currency_code" class="form-control" value="<?= htmlspecialchars($settings['currency_code'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Currency Symbol</label>
                        <input type="text" name="currency_symbol" class="form-control" value="<?= htmlspecialchars($settings['currency_symbol'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <!-- Logos -->
            <div class="tab-pane fade" id="logos" role="tabpanel">
                <?php
                $logoFields = [
                    'logo_website' => 'Website Logo',
                    'logo_login' => 'Login Page Logo',
                    'logo_invoice' => 'Invoice Logo'
                ];
                ?>
                <?php foreach ($logoFields as $key => $label): ?>
                    <div class="mb-4">
                        <label class="form-label"><?= $label ?></label><br>
                        <?php if (!empty($settings[$key])): ?>
                            <img src="<?= $settings[$key] ?>" alt="Logo" style="max-height: 100px; display: block; margin-bottom: 10px;">
                        <?php endif; ?>
                        <input type="file" name="<?= $key ?>" class="form-control" accept="image/*">
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Email -->
            <div class="tab-pane fade" id="email" role="tabpanel">
                <div class="mb-3">
                    <label class="form-label">Sender Name</label>
                    <input type="text" name="email_sender_name" class="form-control" value="<?= htmlspecialchars($settings['email_sender_name'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Driver</label>
                    <input type="text" name="email_driver" class="form-control" value="<?= htmlspecialchars($settings['email_driver'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Host</label>
                    <input type="text" name="email_host" class="form-control" value="<?= htmlspecialchars($settings['email_host'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Port</label>
                    <input type="text" name="email_port" class="form-control" value="<?= htmlspecialchars($settings['email_port'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="email_username" class="form-control" value="<?= htmlspecialchars($settings['email_username'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="email_password" class="form-control" value="<?= htmlspecialchars($settings['email_password'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Encryption</label>
                    <input type="text" name="email_encryption" class="form-control" value="<?= htmlspecialchars($settings['email_encryption'] ?? '') ?>">
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Save Settings</button>
        <a href="../admin_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
