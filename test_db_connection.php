<?php
try {
    $host = 'db.agojrxautplzmceawmix.supabase.co';
    $port = '5432';
    $dbname = 'postgres';
    $username = 'postgres';
    $password = '*FQdXt9RDt!yk$B';
    
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    
    echo "Attempting to connect to Supabase...\n";
    echo "DSN: $dsn\n";
    echo "Host resolution test:\n";
    
    // Test if we can resolve the host
    $ip = gethostbyname($host);
    echo "Host '$host' resolves to: $ip\n";
    
    if ($ip === $host) {
        echo "❌ DNS resolution failed!\n";
        exit(1);
    }
    
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT => 30,
    ]);
    
    echo "✅ Connection successful!\n";
    
    // Test a simple query
    $stmt = $pdo->query('SELECT version()');
    $version = $stmt->fetch();
    echo "PostgreSQL Version: " . $version['version'] . "\n";
    
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";
    echo "Error Code: " . $e->getCode() . "\n";
}
?>