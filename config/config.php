<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$localConfig = __DIR__ . '/local.php';
if (is_file($localConfig)) {
    require_once $localConfig;
}

define('APP_NAME', 'CampusCare');
define('BASE_URL', '/student-complaint-management-system');
define('UPLOAD_DIR', dirname(__DIR__) . '/uploads/');
define('MAX_UPLOAD_BYTES', 2 * 1024 * 1024);

define('DB_HOST', defined('SCM_DB_HOST') ? SCM_DB_HOST : (getenv('SCM_DB_HOST') ?: '127.0.0.1'));
define('DB_NAME', defined('SCM_DB_NAME') ? SCM_DB_NAME : (getenv('SCM_DB_NAME') ?: 'student_complaint_management'));
define('DB_USER', defined('SCM_DB_USER') ? SCM_DB_USER : (getenv('SCM_DB_USER') ?: 'root'));
define('DB_PASS', defined('SCM_DB_PASS') ? SCM_DB_PASS : (getenv('SCM_DB_PASS') ?: ''));

function db(): mysqli
{
    static $connection;
    if ($connection instanceof mysqli) {
        return $connection;
    }

    mysqli_report(MYSQLI_REPORT_OFF);
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($connection->connect_errno) {
        error_log('Database connection failed: ' . $connection->connect_error);
        http_response_code(500);
        exit('Database connection is unavailable. Please check config/local.php.');
    }
    $connection->set_charset('utf8mb4');
    return $connection;
}
