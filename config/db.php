<?php
// 1. Check if Docker injected an environment variable. 
// 2. If it is blank (Legacy Host), fall back to your old local credentials.
$host = getenv('DB_HOST')     ?: '127.0.0.1'; 
$db   = getenv('DB_NAME')     ?: 'your_legacy_db_name';
$user = getenv('DB_USER')     ?: 'your_legacy_db_user';
$pass = getenv('DB_PASSWORD') ?: 'your_legacy_db_password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
     $pdo = new PDO($dsn, $user, $pass, [
         PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
     ]);
} catch (\PDOException $e) {
     die("DB Connection failed: " . $e->getMessage());
}

