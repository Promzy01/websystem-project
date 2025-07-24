<?php
// database/uni_db_setup.php

// 1) Define your credentials from cPanel → MySQL Databases
$host   = 'localhost';
$dbname = 'db name';       // <= this for the database name
$user   = 'user';   // <= yothis is for the database user
$pass   = 'Pass'; // <= the user password
$charset = 'utf8mb4';


// 2) Build DSN & PDO options
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// 3) Connect
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // getMessage() and show a friendly page
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
