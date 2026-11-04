<?php
declare(strict_types=1);

$dbHost = '127.0.0.1';
$dbPort = '3307';
$dbName = 'campusloop';
$dbUser = 'root';
$dbPass = 'areej123'; 

$dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database connection failed. Check config.php, make sure MySQL is running, and import database.sql. Details: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function e(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}
function is_admin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
function require_login(): void {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}
function require_admin(): void {
    if (!is_admin()) {
        http_response_code(403);
        exit('Access denied. Please log in with an administrator account.');
    }
}
function verify_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(419);
        exit('Your session token expired. Go back, refresh the page, and try again.');
    }
}
function set_flash(string $message, string $type = 'success'): void {
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}
function redirect(string $url): never {
    header('Location: ' . $url);
    exit;
}
