<?php 
function getConnection() {
    $host = 'localhost';
    $db = 'suivi_prestation';
    $user = 'admin';  // 新创建的用户
    $pass = 'admin';  // 新用户的密码
    $char = 'utf8mb4';
    $port = '3306';
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$char";
    
    try { 
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        printf("Échec de la connexion : %s\n", $e->getMessage());
        return null;
    }
}

$pdo = getConnection();