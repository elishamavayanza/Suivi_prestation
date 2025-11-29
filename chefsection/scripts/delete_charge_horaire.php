<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Chefdesection') {
    header("Location: ../../login.php");
    exit();
}

include '../../config/connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    try {
        // Suppression de la charge horaire
        $sql = "DELETE FROM chargehoraire WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        
        $_SESSION['success_message'] = "Charge horaire supprimée avec succès.";
        header("Location: ../chargehoraire.php?success=1");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erreur lors de la suppression : " . $e->getMessage();
        header("Location: ../chargehoraire.php?error=1");
        exit();
    }
} else {
    header("Location: ../chargehoraire.php");
    exit();
}
?>