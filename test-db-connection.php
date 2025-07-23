<?php
// Test database connection for Supabase
header('Content-Type: text/html; charset=UTF-8');

echo "<h2>🔍 Database Connection Test</h2>";

// Load environment variables (simulate Vercel environment)
$env_vars = [
    'APP_ENV' => 'production',
    'DB_HOST' => 'db.ckzwvxjamosagksbylkh.supabase.co',
    'DB_PORT' => '5432',
    'DB_NAME' => 'postgres',
    'DB_USER' => 'postgres',
    'DB_PASSWORD' => 'z2smcCeHM2T5k33o'
];

foreach ($env_vars as $key => $value) {
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
    putenv("$key=$value");
}

echo "<h3>📋 Environment Variables</h3>";
foreach ($env_vars as $key => $value) {
    $display_value = ($key === 'DB_PASSWORD') ? str_repeat('*', strlen($value)) : $value;
    echo "<strong>$key:</strong> $display_value<br>";
}

// Test different connection approaches
echo "<h3>🧪 Connection Tests</h3>";

// Test 1: Basic PDO connection
echo "<h4>Test 1: Basic PDO Connection</h4>";
try {
    $dsn = "pgsql:host={$env_vars['DB_HOST']};port={$env_vars['DB_PORT']};dbname={$env_vars['DB_NAME']}";
    echo "<strong>DSN:</strong> $dsn<br>";
    
    $pdo = new PDO($dsn, $env_vars['DB_USER'], $env_vars['DB_PASSWORD'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "✅ <span style='color: green;'>Connection successful (without SSL)</span><br>";
    
    // Test query
    $result = $pdo->query("SELECT version()");
    $version = $result->fetch();
    echo "<strong>PostgreSQL Version:</strong> " . $version['version'] . "<br>";
    
} catch (PDOException $e) {
    echo "❌ <span style='color: red;'>Connection failed: " . $e->getMessage() . "</span><br>";
}

// Test 2: PDO with SSL required
echo "<h4>Test 2: PDO Connection with SSL Required</h4>";
try {
    $dsn = "pgsql:host={$env_vars['DB_HOST']};port={$env_vars['DB_PORT']};dbname={$env_vars['DB_NAME']};sslmode=require";
    echo "<strong>DSN:</strong> $dsn<br>";
    
    $pdo = new PDO($dsn, $env_vars['DB_USER'], $env_vars['DB_PASSWORD'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "✅ <span style='color: green;'>Connection successful (with SSL)</span><br>";
    
    // Test query
    $result = $pdo->query("SELECT version()");
    $version = $result->fetch();
    echo "<strong>PostgreSQL Version:</strong> " . $version['version'] . "<br>";
    
    // Test table existence
    $result = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name");
    $tables = $result->fetchAll();
    
    echo "<strong>Available Tables:</strong><br>";
    if (empty($tables)) {
        echo "⚠️ <span style='color: orange;'>No tables found in database</span><br>";
    } else {
        foreach ($tables as $table) {
            echo "- " . $table['table_name'] . "<br>";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ <span style='color: red;'>Connection failed: " . $e->getMessage() . "</span><br>";
}

// Test 3: PDO with SSL prefer
echo "<h4>Test 3: PDO Connection with SSL Prefer</h4>";
try {
    $dsn = "pgsql:host={$env_vars['DB_HOST']};port={$env_vars['DB_PORT']};dbname={$env_vars['DB_NAME']};sslmode=prefer";
    echo "<strong>DSN:</strong> $dsn<br>";
    
    $pdo = new PDO($dsn, $env_vars['DB_USER'], $env_vars['DB_PASSWORD'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "✅ <span style='color: green;'>Connection successful (with SSL prefer)</span><br>";
    
} catch (PDOException $e) {
    echo "❌ <span style='color: red;'>Connection failed: " . $e->getMessage() . "</span><br>";
}

// Test 4: Using Connection URI format
echo "<h4>Test 4: Connection URI Format</h4>";
try {
    $uri = "postgresql://{$env_vars['DB_USER']}:{$env_vars['DB_PASSWORD']}@{$env_vars['DB_HOST']}:{$env_vars['DB_PORT']}/{$env_vars['DB_NAME']}?sslmode=require";
    echo "<strong>URI:</strong> postgresql://{$env_vars['DB_USER']}:***@{$env_vars['DB_HOST']}:{$env_vars['DB_PORT']}/{$env_vars['DB_NAME']}?sslmode=require<br>";
    
    $pdo = new PDO($uri, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "✅ <span style='color: green;'>Connection successful (URI format)</span><br>";
    
} catch (PDOException $e) {
    echo "❌ <span style='color: red;'>Connection failed: " . $e->getMessage() . "</span><br>";
}

echo "<hr>";
echo "<p><strong>💡 Recommendation:</strong> Use the connection method that shows ✅ success above.</p>";
?>
