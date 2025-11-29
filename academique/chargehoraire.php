<?php
session_start();
if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'Academique' && $_SESSION['role'] != 'SGA')) {
    header("Location: ../login.php");
    exit();
}

include '../config/connexion.php';

// Récupérer les filtres depuis l'URL
$filtre_departement = $_GET['departement'] ?? '';
$filtre_enseignant = $_GET['enseignant'] ?? '';

// Récupérer les données pour les charges horaires
$charges_horaires = [];
$statistiques_departements = [];
$departements = [];
$enseignants = [];

try {
    // Récupérer tous les départements (mentions)
    $stmt = $pdo->prepare("SELECT code_mention, sigle FROM mention ORDER BY sigle");
    $stmt->execute();
    $departements = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer tous les enseignants
    $stmt = $pdo->prepare("SELECT matriculeEnseignant, nom, postnom, prenom FROM enseignant ORDER BY nom");
    $stmt->execute();
    $enseignants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer toutes les charges horaires avec les informations associées
    $sql = "
        SELECT ch.*, 
               e.nom as enseignant_nom, 
               e.postnom as enseignant_postnom,
               e.prenom as enseignant_prenom,
               c.nomComplet as cours_nom,
               c.nbreHeure as volume_horaire,
               m.sigle as mention_sigle,
               p.sigle_promotion as promotion_sigle
        FROM chargehoraire ch
        LEFT JOIN enseignant e ON ch.matricule = e.matriculeEnseignant
        LEFT JOIN cours c ON ch.codecours = c.code_cours
        LEFT JOIN promotion p ON ch.codepromotion = p.sigle_promotion
        LEFT JOIN mention m ON c.code_mention = m.code_mention
        WHERE 1=1
    ";
    
    $params = [];
    
    // Appliquer les filtres
    if (!empty($filtre_departement)) {
        $sql .= " AND m.code_mention = ?";
        $params[] = $filtre_departement;
    }
    
    if (!empty($filtre_enseignant)) {
        $sql .= " AND ch.matricule = ?";
        $params[] = $filtre_enseignant;
    }
    
    $sql .= " ORDER BY e.nom";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
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
        $cours_heures = $charge['volume_horaire'] ?? 0;
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

// Type d'affichage des statistiques (camembert ou histogramme)
$type_graphique = $_GET['graphique'] ?? 'camembert';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charges Horaire - Service Académique</title>
    <link rel="stylesheet" href="academic_admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .filter-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }
        
        .filter-modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            border-radius: 5px;
        }
        
        .chart-container {
            position: relative;
            height: 400px;
            margin-top: 20px;
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        
        .filter-form {
            margin: 15px 0;
        }
        
        .filter-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .filter-form select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
        }
    </style>
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

            <!-- Modal de filtre par département -->
            <div id="filtreDepartementModal" class="filter-modal">
                <div class="filter-modal-content">
                    <span class="close" onclick="fermerModal('filtreDepartementModal')">&times;</span>
                    <h3>Filtrer par Département</h3>
                    <form class="filter-form" method="GET">
                        <label for="departement">Sélectionnez un département :</label>
                        <select name="departement" id="departement">
                            <option value="">Tous les départements</option>
                            <?php foreach ($departements as $dept): ?>
                                <option value="<?php echo htmlspecialchars($dept['code_mention']); ?>" 
                                    <?php echo ($filtre_departement == $dept['code_mention']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($dept['sigle']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="enseignant" value="<?php echo htmlspecialchars($filtre_enseignant); ?>">
                        <button type="submit" class="btn btn-primary">Appliquer le filtre</button>
                        <button type="button" class="btn btn-secondary" onclick="fermerModal('filtreDepartementModal')">Annuler</button>
                    </form>
                </div>
            </div>

            <!-- Modal de filtre par enseignant -->
            <div id="filtreEnseignantModal" class="filter-modal">
                <div class="filter-modal-content">
                    <span class="close" onclick="fermerModal('filtreEnseignantModal')">&times;</span>
                    <h3>Filtrer par Enseignant</h3>
                    <form class="filter-form" method="GET">
                        <label for="enseignant">Sélectionnez un enseignant :</label>
                        <select name="enseignant" id="enseignant">
                            <option value="">Tous les enseignants</option>
                            <?php foreach ($enseignants as $ens): ?>
                                <option value="<?php echo htmlspecialchars($ens['matriculeEnseignant']); ?>" 
                                    <?php echo ($filtre_enseignant == $ens['matriculeEnseignant']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ens['nom'] . ' ' . $ens['postnom'] . ' ' . $ens['prenom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="departement" value="<?php echo htmlspecialchars($filtre_departement); ?>">
                        <button type="submit" class="btn btn-primary">Appliquer le filtre</button>
                        <button type="button" class="btn btn-secondary" onclick="fermerModal('filtreEnseignantModal')">Annuler</button>
                    </form>
                </div>
            </div>

            <div class="academic-section">
                <h3 class="section-title"><i class="fas fa-tasks"></i> Vue d'ensemble des Charges Horaire</h3>
                <p>Consultation des charges horaires de tous les départements.</p>
                
                <div class="academic-actions">
                    <button class="btn btn-primary" onclick="ouvrirModal('filtreDepartementModal')"><i class="fas fa-filter"></i> Filtrer par Département</button>
                    <button class="btn btn-success" onclick="ouvrirModal('filtreEnseignantModal')"><i class="fas fa-filter"></i> Filtrer par Enseignant</button>
                    <button class="btn btn-info" onclick="exporterPDF()"><i class="fas fa-download"></i> Exporter en PDF</button>
                    <?php if ($filtre_departement || $filtre_enseignant): ?>
                        <a href="chargehoraire.php" class="btn btn-warning"><i class="fas fa-times"></i> Réinitialiser les filtres</a>
                    <?php endif; ?>
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
                                    <td><?php echo htmlspecialchars($charge['volume_horaire'] ?? 0); ?> heures</td>
                                    <td>Semestre 1</td>
                                    <td><span class="status-badge status-approved"><i class="fas fa-check"></i> Validé</span></td>
                                    <td class="table-actions">
                                        <button class="btn btn-sm btn-info" onclick="afficherDetails('<?php echo htmlspecialchars($charge['id']); ?>')"><i class="fas fa-eye"></i> Détails</button>
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
                    <a href="?graphique=camembert<?php echo (!empty($filtre_departement) ? '&departement=' . urlencode($filtre_departement) : '') . (!empty($filtre_enseignant) ? '&enseignant=' . urlencode($filtre_enseignant) : ''); ?>" 
                       class="btn <?php echo ($type_graphique == 'camembert') ? 'btn-primary' : 'btn-default'; ?>">
                        <i class="fas fa-chart-pie"></i> Vue Camembert
                    </a>
                    <a href="?graphique=histogramme<?php echo (!empty($filtre_departement) ? '&departement=' . urlencode($filtre_departement) : '') . (!empty($filtre_enseignant) ? '&enseignant=' . urlencode($filtre_enseignant) : ''); ?>" 
                       class="btn <?php echo ($type_graphique == 'histogramme') ? 'btn-success' : 'btn-default'; ?>">
                        <i class="fas fa-chart-bar"></i> Vue Histogramme
                    </a>
                </div>
                
                <?php if (!empty($statistiques_departements)): ?>
                    <div class="chart-container">
                        <canvas id="statistiquesChart"></canvas>
                    </div>
                <?php else: ?>
                    <p>Aucune donnée statistique disponible.</p>
                <?php endif; ?>
                
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
                                        <button class="btn btn-sm btn-info" onclick="afficherDetailsDepartement('<?php echo htmlspecialchars($stat['departement']); ?>')"><i class="fas fa-eye"></i> Détails</button>
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

    <script>
        // Fonctions pour gérer les modals
        function ouvrirModal(modalId) {
            document.getElementById(modalId).style.display = "block";
        }
        
        function fermerModal(modalId) {
            document.getElementById(modalId).style.display = "none";
        }
        
        // Fermer le modal quand on clique en dehors
        window.onclick = function(event) {
            if (event.target.classList.contains('filter-modal')) {
                event.target.style.display = "none";
            }
        }
        
        // Fonctions pour les actions des boutons
        function exporterPDF() {
            alert("Fonction d'exportation PDF à implémenter");
            // Ici, vous pouvez intégrer une bibliothèque d'export PDF
        }
        
        function afficherDetails(id) {
            alert("Affichage des détails pour la charge horaire ID: " + id);
            // Ici, vous pouvez afficher une modale avec les détails
        }
        
        function afficherDetailsDepartement(departement) {
            alert("Affichage des détails pour le département: " + departement);
            // Ici, vous pouvez afficher une modale avec les détails
        }
        
        // Générer le graphique
        <?php if (!empty($statistiques_departements)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('statistiquesChart').getContext('2d');
            
            // Préparer les données
            var labels = <?php echo json_encode(array_column($statistiques_departements, 'departement')); ?>;
            var data = <?php echo json_encode(array_map(function($item) {
                return (int)explode(' ', $item['charge_totale'])[0]; 
            }, $statistiques_departements)); ?>;
            
            var config = {
                type: '<?php echo ($type_graphique == 'histogramme') ? 'bar' : 'pie'; ?>',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Charge horaire totale (heures)',
                        data: data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 205, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(54, 162, 235)',
                            'rgb(255, 205, 86)',
                            'rgb(75, 192, 192)',
                            'rgb(153, 102, 255)',
                            'rgb(255, 159, 64)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: <?php echo ($type_graphique == 'histogramme') ? '{
                        y: {
                            beginAtZero: true
                        }
                    }' : '{}'; ?>
                }
            };
            
            var statistiquesChart = new Chart(ctx, config);
        });
        <?php endif; ?>
    </script>
</body>
</html>