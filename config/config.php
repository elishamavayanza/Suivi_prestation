<?php
//#include("config.php");
class Db_Connexion{
    private $host = null;
    private $database = null;
    private $user = null;
    private $password = null;
    private $charset = null;
    private $port = null;
    public $pdo = null;

    function __construct(){
        $this->host = 'localhost';
        $this->database = 'suivi_prestation';
        $this->user = 'admin';
        $this->password = 'admin';
        $this->charset = 'utf8mb4';
        $this->port = '3306';
    }

    public function setHost($_host){
        $this->host = $_host;
    }
    public function getHost(){
        return $this->host;
    }
    public function setDatabase($_database){
        $this->database = $_database;
    }
    public function getDatabase(){
        return $this->database; // Correction: était $this->adatabase
    }
    public function setUser($_user){
        $this->user = $_user;
    }
    public function getUser(){
        return $this->user; // Correction: était return $user
    }
    public function setPassword($_password){
        $this->password = $_password;
    }
    public function getPassword(){
        return $this->password;
    }
    public function setCharset($_charset){
        $this->charset = $_charset;
    }
    public function getCharset(){
        return $this->charset;
    }
    public function setPort($_port){
        $this->port = $_port;
    }
    public function getPort(){
        return $this->port;
    }

    // Méthode de connexion utilisant les propriétés de la classe
    public function connect(){
        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->database};charset={$this->charset}";
        try{
            $this->pdo = new PDO($dsn, $this->user, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch(PDOException $e){
            printf("Échec de la connexion : %s\n", $e->getMessage());
            return null;
        }
    }

    // Méthode alternative avec paramètres
    public function connexion($host = null, $db = null, $user = null, $pass = null, $char = null, $port = null){
        // Utilise les paramètres fournis ou les valeurs par défaut
        $this->host = isset($host) ? $host : $this->host;
        $this->database = isset($db) ? $db : $this->database;
        $this->user = isset($user) ? $user : $this->user;
        $this->password = isset($pass) ? $pass : $this->password;
        $this->charset = isset($char) ? $char : $this->charset;
        $this->port = isset($port) ? $port : $this->port;

        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->database};charset={$this->charset}";
        try{
            $this->pdo = new PDO($dsn, $this->user, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch(PDOException $e){
            printf("Échec de la connexion : %s\n", $e->getMessage());
            return null;
        }
    }
}

// Utilisation
$con = new Db_Connexion();
$con->connexion(); // Utilise les valeurs par défaut (suivi_prestation, admin, admin)

// Ou directement avec la méthode connect()
// $con->connect();

// Vérification de la connexion
if($con->pdo) {
    echo "Connexion à la base de données 'suivi_prestation' réussie!";
} else {
    echo "Échec de la connexion à la base de données";
}

//var_dump($con->pdo);
?>