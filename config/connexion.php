<?php
$host = 'localhost';
$db= 'suivi_prestation';
$user ='admin';
$pass = 'admin';
$char = 'utf8mb4';
$port = '3306';
$pdo =null;
$dsn ="mysql:host=$host;port=$port;dbname=$db;charset=$char";
try{
    $pdo = new PDO($dsn,$user,$pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    printf("Échec de la connexion : %s\n", $e->getMessage());
}
?>