<?php
session_start();
echo "Session status: " . session_status() . "\n";

// Test database connection
include '../config/connexion.php';

if ($pdo) {
    echo "Database connection successful\n";
    
    // Test a simple query
    try {
        $stmt = $pdo->query("SELECT 1 as test");
        $result = $stmt->fetch();
        echo "Simple query result: " . $result['test'] . "\n";
    } catch (Exception $e) {
        echo "Query error: " . $e->getMessage() . "\n";
    }
    
    // Test the queries used in index.php
    try {
        // Compter le nombre d'enseignants
        $teachersCountQuery = "SELECT COUNT(*) as total FROM utilisateur WHERE role = 'Enseignant'";
        $teachersStmt = $pdo->prepare($teachersCountQuery);
        $teachersStmt->execute();
        $teachersCount = $teachersStmt->fetch()['total'];
        echo "Teachers count: " . $teachersCount . "\n";
    } catch (Exception $e) {
        echo "Teachers count error: " . $e->getMessage() . "\n";
    }
    
    try {
        // Compter le nombre de cours programmés
        $coursesCountQuery = "SELECT COUNT(*) as total FROM cours";
        $coursesStmt = $pdo->prepare($coursesCountQuery);
        $coursesStmt->execute();
        $coursesCount = $coursesStmt->fetch()['total'];
        echo "Courses count: " . $coursesCount . "\n";
    } catch (Exception $e) {
        echo "Courses count error: " . $e->getMessage() . "\n";
    }
    
} else {
    echo "Database connection failed\n";
}
?>