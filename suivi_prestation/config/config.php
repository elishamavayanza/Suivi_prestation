<?php 
#include("config.php");
    class Db_Connexion{
       private $host = null;
       private $database = null;
       private $user =null;
       private $password = null;
       private $charset = null;
       private $port = null;
       public     $pdo =null;
        function __constructor(){
            $this->host = 'localhost';
            $this->database= '....';
            $this->user ='root';
            $this->password ='';
            $this->charset='utf8mb4';
            $this->port ='3306';
        }
    /*    public Db_Connexion($host,$data,$user,$pass,$char,$port){
            $this->host = $host;
            $this->database= $data;
            $this->user =$user;
            $this->password =$pass;
            $this->charset=$char;
            $this->port =$port;
        }*/

        public function setHost($_host){
            $this->host = $_host;
        }
        public function getHost(){
            return $this->host;
        }
        public function setDatabase($_database){
            $this->database =$_database;
        }
        public function getDatabase(){
            return $this->adatabase;
        }
        public function setUser($_user){
            $this->user=$_user;
        }
        public function getUser(){
            return $user;
        }

        public function setPassword($_password){
            $this->password = $_password;
        }
        public function getPassword(){
            return $this->password;
        }
        public function setCharset($_charset){
            $this->charset=$_charset;
        }
        public function getCharset(){
            return $this->charset;
        }
        public function setPort($_port){
            $this->port=$_port;
        }
        public function getPort(){
            return $this->port;
        }
/*
        public function connexion(){
            $pdo =null;
            $dsn ="mysql:host=$host;port=$port;dbname=$database;charset=$charset";
            try{ 
                $pdo = new PDO($dsn,$user,$password);       
            }catch(PDOException $e){
                printf("Échec de la connexion : %s\n", $e->getMessage());       
            }
        }
        */
        public function connexion($host,$db,$user,$pass,$char,$port){
        
            $this->host = $host;
            $this->database= $db;
            $this->user =$user;
            $this->password =$pass;
            $this->charset=$char;
            $this->port =$port;
            $dsn ="mysql:host=$host;port=$port;dbname=$db;charset=$char";
            try{ 
                $this->pdo = new PDO($dsn,$user,$pass);       
            }catch(PDOException $e){
                printf("Échec de la connexion : %s\n", $e->getMessage());       
            }
        }

    }

    $con = new Db_Connexion();
    $con->connexion('localhost','db_evaluation','root','','utf8mb4','3306');
    #var_dump($con->pdo);
    
    #var_dump($con);
    #print"test--".($con->getHost());