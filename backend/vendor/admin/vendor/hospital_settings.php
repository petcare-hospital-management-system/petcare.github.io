<?php
require_once '../includes/config.php';
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Save settings if form is submitted
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $short_title = trim($_POST['short_title']);
    $company_details = trim($_POST['company_details']);
    $currency_code = trim($_POST['currency_code']);
    $currency_symbol = trim($_POST['currency_symbol']);

    $stmt = $pdo->prepare("REPLACE INTO settings (setting_key, setting_value) VALUES 
        ('hospital_title', ?),
        ('hospital_short_title', ?),
        ('hospital_company_details', ?),
        ('hospital_currency_code', ?),
        ('hospital_currency_symbol', ?)");

    $success = $stmt->execute([$title, $short_title, $company_details, $currency_code, $currency_symbol]);
}

// Load existing settings
function getSetting($key, $pdo) {
    $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    return $stmt->fetchColumn();
}

$title = getSetting('hospital_title', $pdo);
$short_title = getSetting('hospital_short_title', $pdo);
$company_details = getSetting('hospital_company_details', $pdo);
$currency_code = getSetting('hospital_currency_code', $pdo);
$currency_symbol = getSetting('hospital_currency_symbol', $pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hospital Settings - General</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

</body>
</html>
