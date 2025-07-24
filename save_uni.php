<?php
session_start();

// Redirect user if not logged in, for useer to log in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

require_once __DIR__ . '/database/uni_db_setup.php';

// Check if form was submitted adiquatly 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $currentUserId = $_SESSION['user_id'];
    $uniId = intval($_POST['uni_id'] ?? 0);

    if ($uniId > 0) {
        // to chech  if the university is already saved by this user yes
        $checkStmt = $pdo->prepare("
            SELECT * 
            FROM user_unis
            WHERE user_id = ? AND uni_id = ?
        ");
        $checkStmt->execute([$currentUserId, $uniId]);
        $existingEntry = $checkStmt->fetch();

        if (!$existingEntry) {
            // yes this is to Insert a new record into the user_unis table
            $insertStmt = $pdo->prepare("
                INSERT INTO user_unis (user_id, uni_id)
                VALUES (?, ?)
            ");
            $insertStmt->execute([$currentUserId, $uniId]);
        }
    }
}

// Redirect back to index page and home page
header("Location: index.php");
exit;