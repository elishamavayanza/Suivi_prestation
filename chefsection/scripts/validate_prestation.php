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
        
        // Mettre à jour le statut de l'en-tête de la fiche
        $sql2 = "UPDATE entetefiche SET statut = 'validé' WHERE id = ?";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute([$ficheId]);
        
        // Valider la transaction
        $pdo->commit();
        
        $_SESSION['success_message'] = "Fiche de prestation validée avec succès.";
        header("Location: ../prestation.php");
        exit();
    } catch (PDOException $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollback();
        $_SESSION['error_message'] = "Erreur lors de la validation : " . $e->getMessage();
        header("Location: ../prestation.php");
        exit();
    }
} else {
    header("Location: ../prestation.php");
    exit();
}
?>