<?php 
      $host = 'localhost';
        $db= 'suivi_prestation';
       $user ='root';
       $pass = '';
       $char = 'utf8mb4';
       $port = '3306';
        $pdo =null;
     $dsn ="mysql:host=$host;port=$port;dbname=$db;charset=$char";
        try{ 
            $pdo = new PDO($dsn,$user,$pass);       
        }catch(PDOException $e){
            printf("Échec de la connexion : %s\n", $e->getMessage());       
        }