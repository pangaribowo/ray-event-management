<?php
// Set explicit content type header
header('Content-Type: text/html; charset=UTF-8');

// Get the route parameter
$route = $_GET['route'] ?? 'index';
$path = $_GET['path'] ?? '';

// Set up the document root context
$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__);

// Parse and reconstruct query string to handle original parameters
$originalQuery = $_SERVER['QUERY_STRING'] ?? '';
if (!empty($originalQuery)) {
    // Remove our internal routing parameters and keep original ones
    $queryParts = [];
    parse_str($originalQuery, $allParams);
    
    foreach ($allParams as $key => $value) {
        if ($key !== 'route' && $key !== 'path') {
            $_GET[$key] = $value;
            $queryParts[] = urlencode($key) . '=' . urlencode($value);
        }
    }
    
    $cleanQuery = implode('&', $queryParts);
    $_SERVER['REQUEST_URI'] = '/' . $path . (!empty($cleanQuery) ? '?' . $cleanQuery : '');
} else {
    $_SERVER['REQUEST_URI'] = '/' . $path;
}

// Change to the parent directory to access all project files
chdir(dirname(__DIR__));

// Handle different routes
switch ($route) {
    case 'test-db':
        // Include database test file
        if (file_exists('test-db-connection.php')) {
            include 'test-db-connection.php';
        } else {
            http_response_code(404);
            echo "Database test file not found";
        }
        break;
        
    case 'login':
        // Include login.php
        if (file_exists('login.php')) {
            include 'login.php';
        } else {
            http_response_code(404);
            echo "Login page not found";
        }
        break;
        
    case 'index':
    default:
        // Include index.php
        if (file_exists('index.php')) {
            include 'index.php';
        } else {
            http_response_code(404);
            echo "Application not found";
        }
        break;
}
?>
