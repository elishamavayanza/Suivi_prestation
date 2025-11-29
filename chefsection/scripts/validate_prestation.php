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
        
        // Mettre à jour toutes les entrées de la fiche de prestation comme validées
        $sql = "UPDATE contenufiche SET signatureCP = 'OK' WHERE identetefiche = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ficheId]);
        
        // Valider la transaction
        $pdo->commit();
        
        $_SESSION['success_message'] = "Fiche de prestation validée avec succès.";
        header("Location: ../prestation.php?validation_filter=pending&success=1");
        exit();
    } catch (PDOException $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollback();
        $_SESSION['error_message'] = "Erreur lors de la validation : " . $e->getMessage();
        header("Location: ../prestation.php?validation_filter=pending&error=1");
        exit();
    }
} else {
    header("Location: ../prestation.php");
    exit();
}
?>