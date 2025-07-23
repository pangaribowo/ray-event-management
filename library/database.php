<?php
require_once 'config.php';

// Pilih database berdasarkan environment
$isLocal = (getEnv('APP_ENV') === 'development');

if ($isLocal) {
    // MySQL untuk development lokal
    $dbConn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
    if ($dbConn->connect_error) {
        die("Koneksi Database Gagal: " . $dbConn->connect_error);
    }
    $dbType = 'mysql';
} else {
    // PostgreSQL untuk production (Supabase)
    try {
        $dsn = "pgsql:host=$dbHost;port=" . getEnv('DB_PORT', '5432') . ";dbname=$dbName";
        $dbConn = new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        $dbType = 'postgresql';
    } catch (PDOException $e) {
        die("Koneksi Database Gagal: " . $e->getMessage());
    }
}

function dbQuery($sql)
{
	global $dbConn, $dbType;
	
	if ($dbType === 'mysql') {
		$result = mysqli_query($dbConn, $sql);
		return $result;
	} else {
		// PostgreSQL dengan PDO
		try {
			$result = $dbConn->query($sql);
			return $result;
		} catch (PDOException $e) {
			error_log("Database Query Error: " . $e->getMessage());
			return false;
		}
	}
}

function dbPrepare($sql, $params = [])
{
	global $dbConn, $dbType;
	
	if ($dbType === 'mysql') {
		$stmt = mysqli_prepare($dbConn, $sql);
		if (!empty($params)) {
			$types = str_repeat('s', count($params));
			mysqli_stmt_bind_param($stmt, $types, ...$params);
		}
		return $stmt;
	} else {
		// PostgreSQL dengan PDO
		try {
			$stmt = $dbConn->prepare($sql);
			if (!empty($params)) {
				$stmt->execute($params);
			}
			return $stmt;
		} catch (PDOException $e) {
			error_log("Database Prepare Error: " . $e->getMessage());
			return false;
		}
	}
}

function dbAffectedRows()
{
	global $dbConn, $dbType;
	
	if ($dbType === 'mysql') {
		return mysqli_affected_rows($dbConn);
	} else {
		// Untuk PDO, gunakan rowCount() pada statement
		return 0; // Akan di-handle di statement
	}
}

function dbFetchArray($result, $resultType = null) {
	global $dbType;
	
	if ($dbType === 'mysql') {
		return mysqli_fetch_array($result, $resultType ?: MYSQLI_NUM);
	} else {
		return $result->fetch(PDO::FETCH_NUM);
	}
}

function dbFetchAssoc($result)
{
	global $dbType;
	
	if ($dbType === 'mysql') {
		return mysqli_fetch_assoc($result);
	} else {
		return $result->fetch(PDO::FETCH_ASSOC);
	}
}

function dbFetchRow($result) 
{
	global $dbType;
	
	if ($dbType === 'mysql') {
		return mysqli_fetch_row($result);
	} else {
		return $result->fetch(PDO::FETCH_NUM);
	}
}

function dbFreeResult($result)
{
	global $dbType;
	
	if ($dbType === 'mysql') {
		return mysqli_free_result($result);
	} else {
		$result->closeCursor();
		return true;
	}
}

function dbNumRows($result)
{
	global $dbType;
	
	if ($dbType === 'mysql') {
		return mysqli_num_rows($result);
	} else {
		return $result->rowCount();
	}
}

function dbSelect($dbName)
{
	global $dbType;
	
	if ($dbType === 'mysql') {
		return mysqli_select_db($dbName);
	} else {
		// PostgreSQL tidak perlu select database
		return true;
	}
}

function dbInsertId()
{
	global $dbConn, $dbType;
	
	if ($dbType === 'mysql') {
		return mysqli_insert_id($dbConn);
	} else {
		return $dbConn->lastInsertId();
	}
}
?>