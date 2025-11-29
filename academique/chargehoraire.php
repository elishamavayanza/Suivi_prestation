<?php
session_start();
if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'Academique' && $_SESSION['role'] != 'SGA')) {
    header("Location: ../login.php");
    exit();
}

include '../config/connexion.php';

// Récupérer les données pour les charges horaires
$charges_horaires = [];
$statistiques_departements = [];

try {
    // Récupérer toutes les charges horaires avec les informations associées
    $stmt = $pdo->prepare("
        SELECT ch.*, 
               e.nom as enseignant_nom, 
               e.postnom as enseignant_postnom,
               e.prenom as enseignant_prenom,
               c.nomComplet as cours_nom,
               m.sigle as mention_sigle,
               p.sigle_promotion as promotion_sigle
        FROM chargehoraire ch
        LEFT JOIN enseignant e ON ch.matricule = e.matriculeEnseignant
        LEFT JOIN cours c ON ch.codecours = c.code_cours
        LEFT JOIN promotion p ON ch.codepromotion = p.sigle_promotion
        LEFT JOIN mention m ON c.code_mention = m.code_mention
        ORDER BY e.nom
    ");
    $stmt->execute();
    $charges_horaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculer les statistiques par département (mention)
    $stats = [];
    foreach ($charges_horaires as $charge) {
        $departement = $charge['mention_sigle'] ?? 'Non défini';
        if (!isset($stats[$departement])) {
            $stats[$departement] = [
                'nombre_enseignants' => [],
                'charge_totale' => 0
            ];
        }
        
        // Ajouter l'enseignant à la liste (sans doublon)
        $enseignant_id = $charge['matricule'] ?? '';
        if (!in_array($enseignant_id, $stats[$departement]['nombre_enseignants']) && $enseignant_id) {
            $stats[$departement]['nombre_enseignants'][] = $enseignant_id;
        }
        
        // Ajouter les heures (à partir du cours)
        $cours_heures = $charge['cours_nom'] ? 40 : 0; // Valeur par défaut
        $stats[$departement]['charge_totale'] += $cours_heures;
    }
    
    // Formater les statistiques pour l'affichage
    foreach ($stats as $departement => $data) {
        $statistiques_departements[] = [
            'departement' => $departement,
            'nombre_enseignants' => count($data['nombre_enseignants']) . ' enseignants',
            'charge_totale' => $data['charge_totale'] . ' heures',
            'charge_moyenne' => $data['nombre_enseignants'] ? 
                              round($data['charge_totale'] / count($data['nombre_enseignants'])) . ' heures' : 
                              '0 heures'
        ];
    }
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des données: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charges Horaire - Service Académique</title>
    <link rel="stylesheet" href="academic_admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="academic-header">
        <h1><i class="fas fa-hourglass-half"></i> Charges Horaire - Service Académique</h1>
        <div class="header-actions">
            <div class="user-info">
                <div class="user-avatar"><?php echo substr($_SESSION['username'], 0, 1); ?></div>
                <span><?php echo $_SESSION['username']; ?> (SGA)</span>
            </div>
            <a href="index.php"><i class="fas fa-home"></i> <span>Accueil</span></a>
            <a href="../script/logout.php"><i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span></a>
        </div>
    </header>

    <!-- Container -->
    <div class="academic-container">
        <!-- Sidebar -->
        <aside class="academic-sidebar">
            <div class="academic-sidebar-header">
                <img src="../image/logo.jpg" alt="Logo">
                <h2>Service Académique</h2>
                <p><?php echo $_SESSION['username']; ?></p>
            </div>
            <nav class="academic-nav-menu">
                <ul>
                    <li><a href="index.php"><i class="fas fa-home"></i> <span>Tableau de bord</span></a></li>
                    <li><a href="horaire.php"><i class="fas fa-clock"></i> <span>Gestion des Horaires</span></a></li>
                    <li><a href="chargehoraire.php" class="active"><i class="fas fa-hourglass-half"></i> <span>Charges Horaire</span></a></li>
                    <li><a href="academique_prestation.php"><i class="fas fa-file-invoice"></i> <span>Fiches de Prestation</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="academic-main">
            <div class="content-header">
                <h2>Charges Horaire</h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Accueil</a></li>
                    <li>Charges Horaire</li>
                </ul>
            </div>

            <div class="academic-alert academic-alert-info">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>Service Académique:</strong> 
                    En tant que membre du service académique, vous pouvez consulter et valider les charges horaires des enseignants.
                </div>
            </div>

            <div class="academic-section">
                <h3 class="section-title"><i class="fas fa-tasks"></i> Vue d'ensemble des Charges Horaire</h3>
                <p>Consultation des charges horaires de tous les départements.</p>
                
                <div class="academic-actions">
                    <button class="btn btn-primary"><i class="fas fa-filter"></i> Filtrer par Département</button>
                    <button class="btn btn-success"><i class="fas fa-filter"></i> Filtrer par Enseignant</button>
                    <button class="btn btn-info"><i class="fas fa-download"></i> Exporter en PDF</button>
                </div>

                <div class="academic-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Enseignant</th>
                                <th>Département</th>
                                <th>Cours</th>
                                <th>Volume Horaire</th>
                                <th>Période</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($charges_horaires)): ?>
                                <?php foreach ($charges_horaires as $charge): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($charge['enseignant_nom'] ?? ''); ?> 
                                        <?php echo htmlspecialchars($charge['enseignant_postnom'] ?? ''); ?> 
                                        <?php echo htmlspecialchars($charge['enseignant_prenom'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($charge['mention_sigle'] ?? 'Non défini'); ?></td>
                                    <td><?php echo htmlspecialchars($charge['cours_nom'] ?? $charge['codecours']); ?></td>
                                    <td><?php echo htmlspecialchars($charge['cours_nom'] ? '40 heures' : '0 heures'); ?></td>
                                    <td>Semestre 1</td>
                                    <td><span class="status-badge status-approved"><i class="fas fa-check"></i> Validé</span></td>
                                    <td class="table-actions">
                                        <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Détails</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">Aucune charge horaire trouvée</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="academic-section">
                <h3 class="section-title"><i class="fas fa-chart-bar"></i> Statistiques des Charges Horaire</h3>
                <p>Répartition des charges horaires par département.</p>
                
                <div class="academic-actions">
                    <button class="btn btn-primary"><i class="fas fa-chart-pie"></i> Vue Camembert</button>
                    <button class="btn btn-success"><i class="fas fa-chart-bar"></i> Vue Histogramme</button>
                </div>
                
                <div class="academic-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Département</th>
                                <th>Nombre d'enseignants</th>
                                <th>Charge totale (heures)</th>
                                <th>Charge moyenne (heures)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($statistiques_departements)): ?>
                                <?php foreach ($statistiques_departements as $stat): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($stat['departement']); ?></td>
                                    <td><?php echo htmlspecialchars($stat['nombre_enseignants']); ?></td>
                                    <td><?php echo htmlspecialchars($stat['charge_totale']); ?></td>
                                    <td><?php echo htmlspecialchars($stat['charge_moyenne']); ?></td>
                                    <td class="table-actions">
                                        <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Détails</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">Aucune statistique disponible</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <footer class="academic-footer">
        <p>&copy; 2025 Système de Suivi de Prestation. Tous droits réservés.</p>
    </footer>
</body>
</html>