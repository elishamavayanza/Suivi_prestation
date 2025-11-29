<?php
session_start();
require_once("config.php");

// Vérifier si l'utilisateur est connecté et a le rôle AB
if (!isset($_SESSION['role']) || $_SESSION['role'] != "AB") {
    echo json_encode(array('status' => 'error', 'message' => 'Accès refusé'));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    try {
        $id = $_POST['id'];
        
        // Supprimer l'honoraire
        $sql = "DELETE FROM honoraire WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(array('status' => 'success', 'message' => 'Honoraire supprimé avec succès'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Honoraire non trouvé'));
        }
        
    } catch (Exception $e) {
        echo json_encode(array('status' => 'error', 'message' => 'Erreur lors de la suppression: ' . $e->getMessage()));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Requête invalide'));
}
?>