<?php
ini_set('display_errors', 'on');

// Load environment variables from .env file (simple parser)
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Skip comments
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enhanced environment variable function
if (!function_exists('getEnv')) {
    function getEnv($key, $default = null) {
        // Check $_ENV first (for Vercel)
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }
        
        // Check $_SERVER (for some hosting providers)
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }
        
        // Check getenv() as fallback
        $value = getenv($key);
        return $value !== false ? $value : $default;
    }
}

// Determine environment
$appEnv = getEnv('APP_ENV', 'development');
$isProduction = ($appEnv === 'production');
$isLocal = ($appEnv === 'development');

// Set error reporting based on environment
if ($isProduction) {
    ini_set('display_errors', 'off');
    error_reporting(0);
} else {
    ini_set('display_errors', 'on');
    error_reporting(E_ALL);
}

// Database connection config
if ($isLocal) {
    // Local development (MySQL)
    $dbHost = getEnv('DB_HOST_LOCAL', 'db');
    $dbUser = getEnv('DB_USER_LOCAL', 'root');
    $dbPass = getEnv('DB_PASSWORD_LOCAL', 'root');
    $dbName = getEnv('DB_NAME_LOCAL', 'db_event_projek');
    $dbPort = getEnv('DB_PORT_LOCAL', '3306');
} else {
    // Production (PostgreSQL via Supabase)
    $dbHost = getEnv('DB_HOST');
    $dbUser = getEnv('DB_USER', 'postgres');
    $dbPass = getEnv('DB_PASSWORD');
    $dbName = getEnv('DB_NAME', 'postgres');
    $dbPort = getEnv('DB_PORT', '5432');
}

/*
$dbHost = 'localhost';
$dbUser = 'tousifkh_calenda';
$dbPass = 'ce=rgfq=C6LB';
$dbName = 'tousifkh_calendar';
*/
//Project data
$site_title 	= 'Online Banking - www.TechZoo.org';
$email_id 		= 'customerservice@hlbonline.pro';

$thisFile = str_replace('\\', '/', __FILE__);
$docRoot = $_SERVER['DOCUMENT_ROOT'];

$webRoot  = str_replace(array($docRoot, 'library/config.php'), '', $thisFile);
$srvRoot  = str_replace('library/config.php', '', $thisFile);

define('WEB_ROOT', $webRoot);
define('SRV_ROOT', $srvRoot);


if (isset($_POST)) {
    foreach ($_POST as $key => $value) {
		$_POST[$key] = trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
	}
}

if (isset($_GET)) {
    foreach ($_GET as $key => $value) {
		$_GET[$key] = trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
	}

}

require_once 'database.php';
require_once 'common.php';

?>