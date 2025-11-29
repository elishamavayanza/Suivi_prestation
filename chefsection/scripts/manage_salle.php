<?php 
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Chefdesection') {
    header("Location: ../../login.php");
    exit();
}

include("../../config/connexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_salle':
                // Ajouter une nouvelle salle
                $nom_salle = $_POST['nom_salle'] ?? '';
                
                if (!empty($nom_salle)) {
                    // On ajoute la salle en l'attribuant à un horaire (par exemple le premier trouvé)
                    $firstHoraireQuery = "SELECT idhoraire FROM horaire LIMIT 1";
                    $stmt = $pdo->prepare($firstHoraireQuery);
                    $stmt->execute();
                    $horaire = $stmt->fetch();
                    
                    if ($horaire) {
                        $updateQuery = "UPDATE horaire SET site = ? WHERE idhoraire = ?";
                        $updateStmt = $pdo->prepare($updateQuery);
                        $updateStmt->execute([$nom_salle, $horaire['idhoraire']]);
                        
                        echo json_encode(['status' => 'success', 'message' => 'Salle ajoutée avec succès']);
                    } else {
                        // Si aucun horaire n'existe, on crée d'abord un horaire
                        $insertQuery = "INSERT INTO horaire (idcours, id, jourheure, codemention, codepromotion, enseignant, site, periode) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                        $insertStmt = $pdo->prepare($insertQuery);
                        $insertStmt->execute(['A définir', 0, 'A définir', 'A définir', 'A définir', 'A définir', $nom_salle, 'A définir']);
                        
                        echo json_encode(['status' => 'success', 'message' => 'Salle ajoutée avec succès']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Nom de salle requis']);
                }
                break;
                
            case 'update_salle':
                // Mettre à jour une salle
                $ancien_nom = $_POST['ancien_nom'] ?? '';
                $nouveau_nom = $_POST['nouveau_nom'] ?? '';
                
                if (!empty($ancien_nom) && !empty($nouveau_nom)) {
                    $updateQuery = "UPDATE horaire SET site = ? WHERE site = ?";
                    $updateStmt = $pdo->prepare($updateQuery);
                    $updateStmt->execute([$nouveau_nom, $ancien_nom]);
                    
                    echo json_encode(['status' => 'success', 'message' => 'Salle mise à jour avec succès']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Noms de salle requis']);
                }
                break;
                
            case 'delete_salle':
                // Supprimer une salle (mettre à vide le champ site)
                $nom_salle = $_POST['nom_salle'] ?? '';
                
                if (!empty($nom_salle)) {
                    $updateQuery = "UPDATE horaire SET site = '' WHERE site = ?";
                    $updateStmt = $pdo->prepare($updateQuery);
                    $updateStmt->execute([$nom_salle]);
                    
                    echo json_encode(['status' => 'success', 'message' => 'Salle supprimée avec succès']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Nom de salle requis']);
                }
                break;
                
            default:
                echo json_encode(['status' => 'error', 'message' => 'Action non reconnue']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Action requise']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);
}
?>