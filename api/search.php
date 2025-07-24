<?php
require_once __DIR__ . '/../database/uni_db_setup.php';

$search = trim($_GET['q'] ?? '');

if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM unis WHERE name LIKE ? ORDER BY name ASC");
    $stmt->execute(["%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM unis ORDER BY name ASC");
}

$universities = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode($universities);