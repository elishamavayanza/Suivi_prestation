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

// Paramètres de filtrage
$type_rapport = isset($_GET['type_rapport']) ? $_GET['type_rapport'] : 'prestations';
$date_debut = isset($_GET['date_debut']) ? $_GET['date_debut'] : date('Y-m-01');
$date_fin = isset($_GET['date_fin']) ? $_GET['date_fin'] : date('Y-m-t');

// Récupérer les données selon le type de rapport
try {
    if ($type_rapport == 'prestations') {
        // Rapport sur les fiches de prestation
        $sql_data = "SELECT ef.*, c.nomComplet as cours_nom, e.nom as enseignant_nom, e.postnom as enseignant_postnom, e.prenom as enseignant_prenom, p.nomComplet as promotion_nom, m.nomComplet as mention_nom
                      FROM entetefiche ef 
                      JOIN cours c ON ef.code_cours = c.code_cours 
                      JOIN enseignant e ON ef.matricule_enseignant = e.matriculeEnseignant
                      JOIN promotion p ON ef.code_promotion = p.sigle_promotion
                      JOIN mention m ON ef.code_mention = m.code_mention
                      WHERE DATE(ef.datecreation) BETWEEN ? AND ?
                      ORDER BY ef.datecreation DESC";
        $stmt_data = $pdo->prepare($sql_data);
        $stmt_data->execute([$date_debut, $date_fin]);
        $rapport_data = $stmt_data->fetchAll();
    } elseif ($type_rapport == 'horaires') {
        // Rapport sur les horaires
        $sql_data = "SELECT h.*, c.nomComplet as cours_nom, e.nom as enseignant_nom, e.postnom as enseignant_postnom, e.prenom as enseignant_prenom, p.nomComplet as promotion_nom, m.nomComplet as mention_nom
                      FROM horaire h
                      JOIN cours c ON h.idcours = c.code_cours
                      LEFT JOIN enseignant e ON h.enseignant = e.matriculeEnseignant
                      JOIN promotion p ON h.codepromotion = p.sigle_promotion
                      JOIN mention m ON h.codemention = m.code_mention
                      WHERE DATE(h.datejour) BETWEEN ? AND ?
                      ORDER BY h.datejour DESC";
        $stmt_data = $pdo->prepare($sql_data);
        $stmt_data->execute([$date_debut, $date_fin]);
        $rapport_data = $stmt_data->fetchAll();
    } else {
        // Rapport global
        $rapport_data = [];
    }
} catch (Exception $e) {
    $error_message = "Erreur lors du chargement des données: " . $e->getMessage();
    $rapport_data = [];
}

// Statistiques générales
try {
    // Nombre total de prestations
    $sql_total_prestations = "SELECT COUNT(*) as total FROM entetefiche";
    $stmt_total_prestations = $pdo->prepare($sql_total_prestations);
    $stmt_total_prestations->execute();
    $total_prestations = $stmt_total_prestations->fetch()['total'];
    
    // Nombre total d'horaires
    $sql_total_horaires = "SELECT COUNT(*) as total FROM horaire";
    $stmt_total_horaires = $pdo->prepare($sql_total_horaires);
    $stmt_total_horaires->execute();
    $total_horaires = $stmt_total_horaires->fetch()['total'];
    
    // Nombre d'enseignants
    $sql_total_enseignants = "SELECT COUNT(*) as total FROM enseignant";
    $stmt_total_enseignants = $pdo->prepare($sql_total_enseignants);
    $stmt_total_enseignants->execute();
    $total_enseignants = $stmt_total_enseignants->fetch()['total'];
    
    // Nombre de cours
    $sql_total_cours = "SELECT COUNT(*) as total FROM cours";
    $stmt_total_cours = $pdo->prepare($sql_total_cours);
    $stmt_total_cours->execute();
    $total_cours = $stmt_total_cours->fetch()['total'];
    
} catch (Exception $e) {
    $error_message = "Erreur lors du chargement des statistiques: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports - CP</title>
    <link rel="stylesheet" href="cp_enhanced_user_experience.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="cp-header">
        <h1><i class="fas fa-chart-bar"></i> Rapports et Statistiques</h1>
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
                    <li><a href="prestation.php"><i class="fas fa-file-invoice"></i> <span>Gestion Prestations</span></a></li>
                    <li><a href="horaire.php"><i class="fas fa-clock"></i> <span>Consulter Horaires</span></a></li>
                    <li><a href="rapports.php" class="active"><i class="fas fa-chart-bar"></i> <span>Rapports</span></a></li>
                    <li><a href="../print/ficheprestation.php" target="_blank"><i class="fas fa-print"></i> <span>Imprimer Rapports</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="cp-main">
            <div class="content-header">
                <h2><i class="fas fa-chart-line"></i> Rapports et Analyses</h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Tableau de bord</a></li>
                    <li>Rapports</li>
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
            
            <!-- Statistiques générales -->
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
                    <div class="stat-value"><?php echo $total_horaires; ?></div>
                    <div class="stat-label">Créneaux Horaire</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon charges">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-value"><?php echo $total_enseignants; ?></div>
                    <div class="stat-label">Enseignants</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon courses">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-value"><?php echo $total_cours; ?></div>
                    <div class="stat-label">Cours</div>
                </div>
            </div>
            
            <!-- Filtres de rapport -->
            <div class="cp-section">
                <h3 class="section-title"><i class="fas fa-filter"></i> Générer un rapport</h3>
                <form method="GET" class="cp-form">
                    <div class="form-row">
                        <div class="form-col">
                            <label for="type_rapport"><i class="fas fa-chart-bar"></i> Type de rapport:</label>
                            <select id="type_rapport" name="type_rapport" class="form-control">
                                <option value="prestations" <?php echo ($type_rapport == 'prestations') ? 'selected' : ''; ?>>Fiches de Prestation</option>
                                <option value="horaires" <?php echo ($type_rapport == 'horaires') ? 'selected' : ''; ?>>Horaires</option>
                            </select>
                        </div>
                        <div class="form-col">
                            <label for="date_debut"><i class="fas fa-calendar-start"></i> Date de début:</label>
                            <input type="date" id="date_debut" name="date_debut" class="form-control" value="<?php echo htmlspecialchars($date_debut); ?>">
                        </div>
                        <div class="form-col">
                            <label for="date_fin"><i class="fas fa-calendar-end"></i> Date de fin:</label>
                            <input type="date" id="date_fin" name="date_fin" class="form-control" value="<?php echo htmlspecialchars($date_fin); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-chart-line"></i> Générer le rapport</button>
                        <a href="rapports.php" class="btn btn-outline"><i class="fas fa-undo"></i> Réinitialiser</a>
                    </div>
                </form>
            </div>
            
            <!-- Résultats du rapport -->
            <div class="cp-section">
                <h3 class="section-title">
                    <i class="fas fa-table"></i> 
                    <?php 
                    if ($type_rapport == 'prestations') echo "Rapport des Fiches de Prestation";
                    elseif ($type_rapport == 'horaires') echo "Rapport des Horaires";
                    else echo "Rapport";
                    ?>
                    (<?php echo date('d/m/Y', strtotime($date_debut)); ?> - <?php echo date('d/m/Y', strtotime($date_fin)); ?>)
                </h3>
                
                <?php if (!empty($rapport_data)): ?>
                <div class="cp-table">
                    <table>
                        <thead>
                            <?php if ($type_rapport == 'prestations'): ?>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Cours</th>
                                <th>Enseignant</th>
                                <th>Promotion</th>
                                <th>Mention</th>
                                <th>Statut</th>
                            </tr>
                            <?php elseif ($type_rapport == 'horaires'): ?>
                            <tr>
                                <th>Date</th>
                                <th>Heure</th>
                                <th>Cours</th>
                                <th>Enseignant</th>
                                <th>Promotion</th>
                                <th>Mention</th>
                                <th>Site</th>
                            </tr>
                            <?php endif; ?>
                        </thead>
                        <tbody>
                            <?php foreach ($rapport_data as $item): ?>
                            <?php if ($type_rapport == 'prestations'): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['id']); ?></td>
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($item['datecreation']))); ?></td>
                                <td><?php echo htmlspecialchars($item['cours_nom']); ?></td>
                                <td><?php echo htmlspecialchars($item['enseignant_nom'] . ' ' . $item['enseignant_postnom'] . ' ' . $item['enseignant_prenom']); ?></td>
                                <td><?php echo htmlspecialchars($item['promotion_nom']); ?></td>
                                <td><?php echo htmlspecialchars($item['mention_nom']); ?></td>
                                <td>
                                    <?php if ($item['statut'] == 'approuvé'): ?>
                                        <span class="status-badge status-approved"><i class="fas fa-check"></i> Approuvé</span>
                                    <?php elseif ($item['statut'] == 'envoyé'): ?>
                                        <span class="status-badge status-sent"><i class="fas fa-paper-plane"></i> Envoyé</span>
                                    <?php else: ?>
                                        <span class="status-badge status-pending"><i class="fas fa-clock"></i> En attente</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php elseif ($type_rapport == 'horaires'): ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($item['datejour'])); ?></td>
                                <td><?php echo htmlspecialchars($item['jourheure']); ?></td>
                                <td><?php echo htmlspecialchars($item['cours_nom']); ?></td>
                                <td><?php echo htmlspecialchars(!empty($item['enseignant_nom']) ? ($item['enseignant_nom'] . ' ' . $item['enseignant_postnom'] . ' ' . $item['enseignant_prenom']) : $item['enseignant']); ?></td>
                                <td><?php echo htmlspecialchars($item['promotion_nom']); ?></td>
                                <td><?php echo htmlspecialchars($item['mention_nom']); ?></td>
                                <td><?php echo htmlspecialchars($item['site']); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="cp-actions">
                    <p><strong><?php echo count($rapport_data); ?></strong> enregistrement(s) trouvé(s)</p>
                    <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Imprimer le rapport</button>
                    <a href="../print/<?php echo $type_rapport; ?>.php?date_debut=<?php echo $date_debut; ?>&date_fin=<?php echo $date_fin; ?>" target="_blank" class="btn btn-success"><i class="fas fa-file-pdf"></i> Exporter en PDF</a>
                </div>
                <?php else: ?>
                <div class="cp-alert cp-alert-info">
                    <i class="fas fa-info-circle"></i> Aucune donnée trouvée pour la période sélectionnée.
                </div>
                <?php endif; ?>
            </div>
            
            <div class="cp-actions">
                <a href="index.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Retour au tableau de bord</a>
            </div>
        </main>
    </div>

    <footer class="cp-footer">
        <p>&copy; 2025 Système de Suivi de Prestation - Tous droits réservés</p>
    </footer>
</body>
</html>