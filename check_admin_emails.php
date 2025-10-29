<?php
// Check admin users for email issues

$azureConfig = [
    'host' => 'treis-adiutor-mysql.mysql.database.azure.com',
    'port' => 3306,
    'database' => 'cms',
    'username' => 'treisadiutor',
    'password' => '2LR2iKDMbJgc$'
];

try {
    $pdo = new PDO(
        "mysql:host={$azureConfig['host']};port={$azureConfig['port']};dbname={$azureConfig['database']}",
        $azureConfig['username'],
        $azureConfig['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
        ]
    );

    echo "🔍 Checking admin users...\n";
    
    $admins = $pdo->query("
        SELECT id, fullName, email, role 
        FROM users 
        WHERE role = 'admin'
    ")->fetchAll(PDO::FETCH_ASSOC);

    if (empty($admins)) {
        echo "❌ No admin users found!\n";
    } else {
        echo "📋 Admin users:\n";
        foreach ($admins as $admin) {
            $emailStatus = empty($admin['email']) ? "❌ NO EMAIL" : "✅ {$admin['email']}";
            echo "  - ID: {$admin['id']}, Name: {$admin['fullName']}, Email: $emailStatus\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>