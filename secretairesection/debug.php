<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Debug Information</h2>\n";

// Test 1: Session
echo "<h3>Session Test</h3>\n";
session_start();
echo "Session started<br>\n";
$_SESSION['username'] = 'test_user';
$_SESSION['role'] = 'secretaire';
echo "Session variables set<br>\n";

// Test 2: Database Connection
echo "<h3>Database Connection Test</h3>\n";
include '../config/connexion.php';

if ($pdo) {
    echo "Database connected successfully<br>\n";
    
    // Test queries
    $queries = [
        "SELECT COUNT(*) as total FROM utilisateur WHERE role = 'Enseignant'" => "Teachers count",
        "SELECT COUNT(*) as total FROM cours" => "Courses count",
        "SELECT COUNT(*) as total FROM entetefiche" => "Prestations count",
        "SELECT COUNT(*) as total FROM cours WHERE statut = 'fini'" => "Finished courses count"
    ];
    
    foreach ($queries as $query => $description) {
        try {
            echo "Testing: $description<br>\n";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            echo "$description result: " . $result['total'] . "<br>\n";
        } catch (Exception $e) {
            echo "Error in $description: " . $e->getMessage() . "<br>\n";
        }
    }
} else {
    echo "Database connection failed<br>\n";
}

echo "<h3>Redirect Test</h3>\n";
echo "<a href='index.php'>Go to Secretary Index</a><br>\n";
?>