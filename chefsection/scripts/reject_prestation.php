<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Chefdesection') {
    header("Location: ../../login.php");
    exit();
}

include '../../config/connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ficheId'])) {
    $ficheId = $_POST['ficheId'];
    
    try {
        // Commencer une transaction
        $pdo->beginTransaction();
        
        // Mettre à jour toutes les entrées de la fiche de prestation comme rejetées
        $sql = "UPDATE contenufiche SET signatureCP = 'REJETEE' WHERE identetefiche = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ficheId]);
        
        // Valider la transaction
        $pdo->commit();
        
        $_SESSION['success_message'] = "Fiche de prestation rejetée avec succès.";
        header("Location: ../prestation.php?validation_filter=pending&success=1");
        exit();
    } catch (PDOException $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollback();
        $_SESSION['error_message'] = "Erreur lors du rejet : " . $e->getMessage();
        header("Location: ../prestation.php?validation_filter=pending&error=1");
        exit();
    }
} else {
    header("Location: ../prestation.php");
    exit();
}
?>