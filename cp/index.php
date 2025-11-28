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

// Récupérer les statistiques
try {
    // Nombre total de cours
    $sql_cours = "SELECT COUNT(*) as total FROM cours";
    $stmt_cours = $pdo->prepare($sql_cours);
    $stmt_cours->execute();
    $total_cours = $stmt_cours->fetch()['total'];
    
    // Nombre de fiches de prestation
    $sql_prestations = "SELECT COUNT(*) as total FROM entetefiche";
    $stmt_prestations = $pdo->prepare($sql_prestations);
    $stmt_prestations->execute();
    $total_prestations = $stmt_prestations->fetch()['total'];
    
    // Nombre d'enseignants
    $sql_enseignants = "SELECT COUNT(*) as total FROM users WHERE role='Enseignant'";
    $stmt_enseignants = $pdo->prepare($sql_enseignants);
    $stmt_enseignants->execute();
    $total_enseignants = $stmt_enseignants->fetch()['total'];
    
    // Nombre de fiches de prestation approuvées
    $sql_approved = "SELECT COUNT(*) as total FROM entetefiche WHERE statut='approuvé'";
    $stmt_approved = $pdo->prepare($sql_approved);
    $stmt_approved->execute();
    $approved_prestations = $stmt_approved->fetch()['total'];
    
} catch (Exception $e) {
    $error_message = "Erreurlors du chargement des statistiques: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bordCP</title>
    <link rel="stylesheet" href="cp_enhanced_user_experience.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="cp-header">
<h1><i class="fas fa-user-tie"></i> Interface Chef de Programme (CP)</h1>
        <div class="header-actions">
            <a href="../index.php" class="btn btn-outline"><i class="fas fa-home"></i> <span>Accueil</span></a>
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
                    <li><a href="index.php" class="active"><i class="fas fa-home"></i> <span>Tableau de bord</span></a></li>
                    <li><a href="prestation.php"><i class="fas fa-file-invoice"></i><span>Gestion Prestations</span></a></li>
                    <li><a href="horaire.php"><i class="fas fa-clock"></i> <span>Consulter Horaires</span></a></li>
                    <li><a href="#"><i class="fas fa-chart-bar"></i><span>Rapports</span></a></li>
                    <li><a href="../print/ficheprestation.php" target="_blank"><i class="fas fa-print"></i> <span>Imprimer Rapports</span></a></li>
                </ul>
            </nav>
        </aside>

<!-- Main Content -->
        <main class="cp-main">
            <div class="content-header">
                <h2><i class="fas fa-tachometer-alt"></i> Tableau de bord</h2>
                <ul class="breadcrumb">
                    <li><a href="#">Accueil</a></li>
                    <li>Tableau de bord</li>
                </ul>
            </div>

            <?php if (!empty($success_message)): ?>
                <div class="cp-alert cp-alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
               </div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="cp-alert cp-alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
<!-- Statistiques -->
            <div class="stats-summary">
                <div class="stat-card">
                    <div class="stat-icon prestations">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div class="stat-value"><?php echo $total_prestations; ?></div>
                    <div class="stat-label">Fiches Prestation</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon horaires">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value"><?php echo $total_cours; ?></div>
                    <div class="stat-label">Cours Total</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon charges">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
<div class="stat-value"><?php echo $total_enseignants; ?></div>
                    <div class="stat-label">Enseignants</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon prestations">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-value"><?php echo $approved_prestations; ?></div>
                    <div class="stat-label">Prestations Approuvées</div>
                </div>
            </div>
            
            <!-- Section dernières prestations -->
            <div class="cp-section">
                <h3 class="section-title"><i class="fas fa-history"></i> Dernières Fiches de Prestation Soumises</h3>
                <?php
                try {
                    $sql_latest = "SELECT ef.*, c.nomComplet as cours_nom, u.username as enseignant_nom 
                                  FROM entetefiche ef 
                                  JOIN cours c ON ef.code_cours = c.code_cours 
                                  JOIN users u ON u.username = ef.enseignant
                                  ORDER BY ef.datecreation DESC 
                                  LIMIT 5";
                    $stmt_latest = $pdo->prepare($sql_latest);
                    $stmt_latest->execute();
                    $latest_prestations = $stmt_latest->fetchAll();
                } catch (Exception $e) {
                    $error_message = "Erreur lors du chargement des dernières prestations: " . $e->getMessage();
                }
                ?>
                
                <div class="cp-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Cours</th>
                                <th>Enseignant</th>
                                <th>Date</th>
                                <th>Heures</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($latest_prestations) > 0): ?>
                                <?php foreach ($latest_prestations as $prestation): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($prestation['cours_nom']); ?></td>
                                    <td><?php echo htmlspecialchars($prestation['enseignant_nom']); ?></td>
                                    <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($prestation['datecreation']))); ?></td>
                                    <td><?php echo htmlspecialchars($prestation['heure_debut'] . ' - ' .$prestation['heure_fin']); ?></td>
                                    <td>
                                        <?php if ($prestation['statut'] == 'approuvé'): ?>
                                            <span class="status-badge status-approved"><i class="fas fa-check"></i> Approuvé</span>
                                        <?php else: ?>
                                            <span class="status-badge status-pending"><i class="fas fa-clock"></i> En attente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="table-actions">
                                        <button class="btn btn-sm btn-outline" onclick="viewPrestation(<?php echo $prestation['id']; ?>)">
                                            <i class="fas fa-eye"></i> Voir
                                        </button>
                                        <?php if ($prestation['statut'] != 'approuvé'): ?>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="fiche_id" value="<?php echo $prestation['id']; ?>">
                                            <button type="submit" name="approve_prestation" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i> Approuver
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                   </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Aucune fiche de prestation soumise récemment.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
</table>
                </div>
            </div>
            
            <!-- Section horaires -->
            <div class="cp-section">
                <h3 class="section-title"><i class="fas fa-calendar-alt"></i> Horaires des Cours (Cette Semaine)</h3>
                <?php
                try{
                    // Récupérer les horaires de la semaine
                    $sql_horaires = "SELECT h.*, c.nomComplet as cours_nom 
                                    FROM horaire h
                                    JOIN cours c ON h.idcours = c.code_cours
                                    WHERE h.datejour >= CURDATE() 
                                      AND h.datejour <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                                    ORDER BY h.datejour ASC, h.jourheure ASC
                                    LIMIT 10";
                    $stmt_horaires = $pdo->prepare($sql_horaires);
                    $stmt_horaires->execute();
                    $horaires = $stmt_horaires->fetchAll();
                } catch (Exception $e) {
                    $error_message = "Erreur lors du chargement des horaires: " . $e->getMessage();
                }
                ?>
                
                <div class="cp-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Jour</th>
                                <th>Heure</th>
                                <th>Cours</th>
                                <th>Enseignant</th>
                                <th>Site</th>
                                <th>Promotion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($horaires) > 0): ?>
                                <?php foreach ($horaires as $horaire): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($horaire['datejour'])); ?></td>
                                    <td><?php echo htmlspecialchars($horaire['jourheure']); ?></td>
                                   <td><?php echo htmlspecialchars($horaire['cours_nom']); ?></td>
                                    <td><?php echo htmlspecialchars($horaire['enseignant']); ?></td>
                                    <td><?php echo htmlspecialchars($horaire['site']); ?></td>
                                    <td><?php echo htmlspecialchars($horaire['codepromotion']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Aucun horaire programmé pour cette semaine.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
</div>
            </div>
            
            <div class="cp-actions">
                <a href="prestation.php" class="btn btn-primary"><i class="fas fa-file-invoice"></i> Gérer les Prestations</a>
                <a href="form_prestation_cp.php" class="btn btn-info"><i class="fas fa-plus-circle"></i> Nouvelle Prestation</a>
                <a href="horaire.php" class="btn btn-info"><i class="fas fa-clock"></i> Consulter tous les Horaires</a>
                <a href="../print/ficheprestation.php" target="_blank" class="btn btn-success"><i class="fas fa-print"></i> Imprimer Rapport</a>
            </div>
        </main>
    </div>

    <footer class="cp-footer">
        <p>&copy; 2025 Système de Suivi de Prestation - Tous droits réservés</p>
    </footer>

    <script>
        function viewPrestation(id) {
            alert("Affichage de la fiche de prestation #" + id +". Cette fonctionnalité sera implémentée.");
        }
        
        // Marquer une fiche comme approuvée
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_prestation'])): ?>
        document.addEventListener('DOMContentLoaded', function() {
            location.reload();
});
        <?php endif; ?>
    </script>
</body>
</html>