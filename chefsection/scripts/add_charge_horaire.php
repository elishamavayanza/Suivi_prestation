<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Chefdesection') {
    header("Location: ../../login.php");
    exit();
}

include '../../config/connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enseignant = $_POST['enseignant'];
    $cours = $_POST['cours'];
    $promotion = $_POST['promotion'];
    $observation = $_POST['observation'];
    $chargeId = $_POST['chargeId'];

    try {
        if (!empty($chargeId)) {
            // Modification d'une charge existante
            $sql = "UPDATE chargehoraire SET matricule = ?, codecours = ?, codepromotion = ?, observation = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$enseignant, $cours, $promotion, $observation, $chargeId]);
            
            $_SESSION['success_message'] = "Charge horaire modifiée avec succès.";
        } else {
            // Ajout d'une nouvelle charge
            $sql = "INSERT INTO chargehoraire (matricule, codecours, codepromotion, observation) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$enseignant, $cours, $promotion, $observation]);
            
            $_SESSION['success_message'] = "Charge horaire ajoutée avec succès.";
        }
        
        header("Location: ../chargehoraire.php?success=1");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erreur lors de l'enregistrement : " . $e->getMessage();
        header("Location: ../chargehoraire.php?error=1");
        exit();
    }
} else {
    header("Location: ../chargehoraire.php");
    exit();
}
?>