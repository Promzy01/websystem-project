<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
require_once __DIR__ . '/database/uni_db_setup.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $uni_id = intval($_POST['uni_id'] ?? 0);

    if ($uni_id > 0) {
        $stmt = $pdo->prepare("DELETE FROM user_unis WHERE user_id = ? AND uni_id = ?");
        $stmt->execute([$user_id, $uni_id]);
    }
}
header("Location: dashboard.php");
exit;