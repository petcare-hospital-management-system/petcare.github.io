<?php
require 'includes/config.php';

foreach ($_POST as $key => $value) {
    $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
        ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
    $stmt->execute([$key, $value]);
}

// Handle logo upload
if (!empty($_FILES['invoice_logo']['name'])) {
    $filename = 'invoice_logo_' . time() . '.png';
    move_uploaded_file($_FILES['invoice_logo']['tmp_name'], "../uploads/" . $filename);
    $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('invoice_logo', ?)
        ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
    $stmt->execute([$filename]);
}

header("Location: settings.php");
exit;
