<?php
$pdo = new PDO('mysql:host=treis-adiutor-mysql.mysql.database.azure.com;port=3306;dbname=cms', 'treisadiutor', '2LR2iKDMbJgc$', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false]);
$result = $pdo->query('SHOW COLUMNS FROM users');
while ($row = $result->fetch()) {
    echo $row['Field'] . ' (' . $row['Type'] . ')' . PHP_EOL;
}
echo "\nSample row:\n";
$user = $pdo->query('SELECT * FROM users LIMIT 1')->fetch(PDO::FETCH_ASSOC);
foreach ($user as $key => $value) {
    echo "$key: $value\n";
}
?>