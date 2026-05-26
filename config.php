=== config.php ===
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'secure_auth_db');
define('DB_USER', 'auth_user');
define('DB_PASS', '57G18qrtg');
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    error_log("Error PDO: " . $e->getMessage());
    die("DB connection error");
}
