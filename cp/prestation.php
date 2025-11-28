<?php
session_start();

// Vérifier si l'utilisateur est connecté et s'il est CP
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Chefpromotion') {
    header("Location: ../login.php");
    exit();
}

include("../script/config.php");

// Messages d'alerte
$success_message = "";
$error_message = "";

// Marquer une fiche comme terminée/approuvée
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_prestation'])) {
    try {
        $fiche_id = $_POST['fiche_id'];
        $sql_update= "UPDATE entetefiche SET statut='approuvé', date_approbation=NOW() WHERE id=?";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([$fiche_id]);
        
        $success_message = "Fiche de prestation approuvée avec succès.";
        
    } catch (Exception $e) {
        $error_message = "Erreur lors de l'approbation de la fiche: " . $e->getMessage();
    }
}

// Envoyer une fiche à la section
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_to_section'])) {
    try {
        $fiche_id = $_POST['fiche_id'];
        $sql_update = "UPDATE entetefiche SET statut='envoyé', date_envoi=NOW() WHERE id=?";
        $stmt_update = $pdo->prepare($sql_update);
$stmt_update->execute([$fiche_id]);
        
        $success_message = "Fiche de prestation envoyée à la section avec succès.";
        
    } catch (Exception $e) {
        $error_message = "Erreur lors de l'envoi de la fiche: " . $e->getMessage();
    }
}

// Récupérer toutes les fiches de prestation
try {
    $sql_fiches = "SELECT ef.*, c.nomComplet as cours_nom, u.username as enseignant_nom 
                  FROM entetefiche ef 
                  JOIN cours c ON ef.code_cours = c.code_cours 
                  JOIN users u ON u.username = ef.enseignant
                  ORDER BY ef.datecreation DESC";
$stmt_fiches = $pdo->prepare($sql_fiches);
    $stmt_fiches->execute();
    $fiches = $stmt_fiches->fetchAll();
    
} catch (Exception $e) {
    $error_message = "Erreur lors du chargement des fiches de prestation:" . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Prestations - CP</title>
     <link rel="stylesheet" href="cp_enhanced_user_experience.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="cp-header">
        <h1><i class="fas fa-file-invoice"></i> Gestion des Prestations</h1>
        <div class="header-actions">
            <a href="index.php" class="btn btn-outline"><i class="fas fa-home"></i> <span>Tableau de bord</span></a>
            <a href="../script/logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span></a>
        </div>
    </header>

    <!-- Container -->
    <div class="cp-container">
        <!-- Sidebar -->
        <aside class="cp-sidebar">
<div class="cp-sidebar-header">
                <img src="../image/logo.jpg" alt="Logo">
                <h2>Chef de Programme</h2>
                <p><?php echo $_SESSION['username']; ?></p>
            </div>
            <nav class="cp-nav-menu">
                <ul>
                    <li><a href="index.php"><i class="fas fa-home"></i> <span>Tableau de bord</span></a></li>
                    <li><a href="prestation.php" class="active"><i class="fas fa-file-invoice"></i> <span>Gestion Prestations</span></a></li>
                    <li><a href="horaire.php"><i class="fas fa-clock"></i> <span>Consulter Horaires</span></a></li>
                    <li><a href="#"><i class="fas fa-chart-bar"></i> <span>Rapports</span></a></li>
                    <li><a href="../print/ficheprestation.php" target="_blank"><i class="fas fa-print"></i> <span>Imprimer Rapports</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
<main class="cp-main">
            <div class="content-header">
                <h2><i class="fas fa-tasks"></i> Fiches de Prestation</h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Tableau de bord</a></li>
                    <li>Gestion Prestations</li>
                </ul>
            </div>

            <?php if (!empty($success_message)): ?>
                <div class="cp-alert cp-alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)):?>
                <div class="cp-alert cp-alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
           <!-- Liste des fiches de prestation -->
            <div class="cp-section">
                <h3 class="section-title"><i class="fas fa-list"></i> Toutes les Fiches de Prestation</h3>
                
                <div class="cp-table">
                    <table>
                        <thead>
<tr>
                                <th>ID</th>
                                <th>Cours</th>
                                <th>Enseignant</th>
                                <th>Date</th>
                                <th>Heures</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($fiches) > 0): ?>
                                <?php foreach ($fiches as $fiche): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($fiche['id']); ?></td>
                                    <td><?php echo htmlspecialchars($fiche['cours_nom']); ?></td>
                                    <td><?php echo htmlspecialchars($fiche['enseignant_nom']); ?></td>
                                    <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($fiche['datecreation']))); ?></td>
                                    <td><?php echo htmlspecialchars($fiche['heure_debut'] . ' - ' . $fiche['heure_fin']); ?></td>
                                   <td>
                                        <?php if ($fiche['statut'] == 'approuvé'): ?>
                                            <span class="status-badge status-approved"><i class="fas fa-check"></i> Approuvé</span>
                                        <?php elseif ($fiche['statut'] == 'envoyé'): ?>
                                           <span class="status-badge status-sent"><i class="fas fa-paper-plane"></i> Envoyé</span>
                                        <?php else: ?>
                                            <span class="status-badge status-pending"><i class="fas fa-clock"></i> En attente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="table-actions">
                                        <button class="btn btn-sm btn-outline" onclick="viewDetails(<?php echo $fiche['id']; ?>)">
                                            <i class="fas fa-eye"></i> Détails
                                        </button>
                                        
                                        <?php if ($fiche['statut'] == 'en_attente'): ?>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="fiche_id" value="<?php echo $fiche['id']; ?>">
                                            <button type="submit" name="approve_prestation" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i> Approuver
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                        
                                        <?php if ($fiche['statut'] == 'approuvé'): ?>
                                        <form method="POST" style="display: inline;">
                                           <input type="hidden" name="fiche_id" value="<?php echo $fiche['id']; ?>">
                                            <button type="submit" name="send_to_section" class="btn btn-sm btn-info">
                                                <i class="fas fa-paper-plane"></i> Envoyer à la Section
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                        
                                        <?php if ($fiche['statut'] == 'envoyé'): ?>
                                        <button class="btn btn-sm btn-outline" disabled>
                                            <i class="fas fa-check"></i> Déjà envoyé
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">Aucunefiche de prestation trouvée.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Instructions -->
            <div class="cp-section">
                <h3 class="section-title"><i class="fas fa-info-circle"></i> Procédure de gestion des prestations</h3>
                <div class="procedure-steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h4>Vérification des fiches</h4>
                            <p>Vérifiez chaque fiche de prestation soumise par les enseignants pour vous assurer qu'elle est complète et conforme.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h4>Approbation</h4>
                            <p>Approuvez lesfiches qui sont complètes et conformes aux exigences.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h4>Envoi à la section</h4>
                            <p>Envoyez les fiches approuvées à la section concernée pour traitement.</p>
                        </div>
                    </div>
                    <div class="step">
                       <div class="step-number">4</div>
                        <div class="step-content">
                            <h4>Archivage</h4>
                            <p>Archivez les fiches traitées pour référence future.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="cp-actions">
                <a href="form_prestation_cp.php" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Nouvelle Fiche de Prestation</a>
                <a href="index.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Retour au tableau de bord</a>
                <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Imprimer la liste</button>
                <a href="../print/ficheprestation.php" target="_blank" class="btn btn-success"><i class="fas fa-file-pdf"></i> Exporter en PDF</a>
            </div>
        </main>
    </div>

    <footer class="cp-footer">
        <p>&copy; 2025 Système de Suivi de Prestation - Tous droits réservés</p>
    </footer>

    <script>
        function viewDetails(id) {
            //Dans une implémentation réelle, cela chargerait les détails de la fiche
            alert("Affichage des détails de la fiche de prestation #" + id + ". Dans une version complète, cela montrerait les détails complets de la fiche.");
        }
        
        // Fonctionpour confirmer l'envoi à la section
        function confirmSend(id) {
            if (confirm("Êtes-vous sûr de vouloir envoyer cette fiche de prestation à la sectionconcernée ?")) {
                // Créer un formulaire dynamique pour soumettre l'action
                varform = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                
                var inputId = document.createElement('input');
                inputId.type = 'hidden';
                inputId.name = 'fiche_id';
                inputId.value = id;
                form.appendChild(inputId);
                
var inputAction = document.createElement('input');
                inputAction.type = 'hidden';
                inputAction.name = 'send_to_section';
                inputAction.value = '1';
                form.appendChild(inputAction);
                
               document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>