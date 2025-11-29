<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Chefdesection') {
    header("Location: ../login.php");
    exit();
}

include '../config/connexion.php';

// Récupérer les données depuis la base de données
$fichesQuotidiennes = [];
$fichesAValider = [];

try {
    // Récupérer les fiches de prestation quotidiennes avec les détails
    $stmt = $pdo->prepare("
        SELECT cf.id, cf.datejoure, cf.contenu, cf.heureEntree, cf.heureSortie, cf.nbreH,
               ef.code_cours, ef.matricule_enseignant,
               e.nom AS enseignant_nom, e.postnom AS enseignant_postnom, e.prenom AS enseignant_prenom,
               c.nomComplet AS cours_nom
        FROM contenufiche cf
        JOIN entetefiche ef ON cf.identetefiche = ef.id
        JOIN enseignant e ON ef.matricule_enseignant = e.matriculeEnseignant
        JOIN cours c ON ef.code_cours = c.code_cours
        ORDER BY cf.datejoure DESC, cf.heureEntree ASC
        LIMIT 20
    ");
    $stmt->execute();
    $fichesQuotidiennes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les fiches à valider
    $stmt = $pdo->prepare("
        SELECT ef.id, 
               e.nom AS enseignant_nom, e.postnom AS enseignant_postnom, e.prenom AS enseignant_prenom,
               c.nomComplet AS cours_nom,
               COUNT(cf.id) AS total_jours,
               SUM(cf.nbreH) AS total_heures
        FROM entetefiche ef
        JOIN enseignant e ON ef.matricule_enseignant = e.matriculeEnseignant
        JOIN cours c ON ef.code_cours = c.code_cours
        LEFT JOIN contenufiche cf ON ef.id = cf.identetefiche
        WHERE ef.id NOT IN (SELECT DISTINCT identetefiche FROM contenufiche WHERE signatureCP IS NULL OR signatureCP = '')
        GROUP BY ef.id, e.nom, e.postnom, e.prenom, c.nomComplet
        ORDER BY e.nom
    ");
    $stmt->execute();
    $fichesAValider = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Erreur lors de la récupération des données : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiches de Prestation - Chef de Section</title>
    <link rel="stylesheet" href="chef_section_cp_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="section-chief-header">
        <h1><i class="fas fa-file-invoice"></i> Consultation des Fiches de Prestation</h1>
        <div class="header-actions">
            <a href="../index.php"><i class="fas fa-home"></i> <span>Accueil</span></a>
            <a href="../script/logout.php"><i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span></a>
        </div>
    </header>

    <!-- Container -->
    <div class="section-chief-container">
        <!-- Sidebar -->
        <aside class="section-chief-sidebar">
            <div class="section-chief-sidebar-header">
                <img src="../image/logo.jpg" alt="Logo">
                <h2>Chef de Section</h2>
                <p><?php echo $_SESSION['username']; ?></p>
            </div>
            <nav class="section-chief-nav-menu">
                <ul>
                    <li><a href="index.php"><i class="fas fa-home"></i> <span>Tableau de bord</span></a></li>
                    <li><a href="horaire.php"><i class="fas fa-clock"></i> <span>Gestion des Horaires</span></a></li>
                    <li><a href="chargehoraire.php"><i class="fas fa-hourglass-half"></i> <span>Charges Horaire</span></a></li>
                    <li><a href="prestation.php" class="active"><i class="fas fa-file-invoice"></i> <span>Fiches de Prestation</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="section-chief-main">
            <div class="content-header">
                <h2>Fiches de Prestation</h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Accueil</a></li>
                    <li>Fiches de Prestation</li>
                </ul>
            </div>

            <div class="section-chief-section">
                <h3 class="section-title"><i class="fas fa-calendar-day"></i> Consultation Quotidienne</h3>
                <p>En tant que chef de section, vous pouvez consulter les fiches de prestation quotidiennement.</p>
                
                <div class="section-chief-actions">
                    <button class="btn btn-primary" onclick="filterToday()"><i class="fas fa-calendar-day"></i> Voir les fiches du jour</button>
                    <button class="btn btn-success" onclick="showFilterForm('date')"><i class="fas fa-filter"></i> Filtrer par date</button>
                    <button class="btn btn-warning" onclick="showFilterForm('enseignant')"><i class="fas fa-search"></i> Rechercher par enseignant</button>
                </div>

                <div class="section-chief-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Enseignant</th>
                                <th>Cours</th>
                                <th>Heure Début</th>
                                <th>Heure Fin</th>
                                <th>Nombre Heures</th>
                                <th>Contenu</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($fichesQuotidiennes)): ?>
                                <tr>
                                    <td colspan="9" class="text-center">Aucune fiche de prestation trouvée</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($fichesQuotidiennes as $fiche): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($fiche['datejoure']); ?></td>
                                        <td><?php echo htmlspecialchars($fiche['enseignant_nom'] . ' ' . $fiche['enseignant_postnom'] . ' ' . $fiche['enseignant_prenom']); ?></td>
                                        <td><?php echo htmlspecialchars($fiche['cours_nom']); ?></td>
                                        <td><?php echo htmlspecialchars($fiche['heureEntree']); ?></td>
                                        <td><?php echo htmlspecialchars($fiche['heureSortie']); ?></td>
                                        <td><?php echo htmlspecialchars($fiche['nbreH']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($fiche['contenu'], 0, 50)) . (strlen($fiche['contenu']) > 50 ? '...' : ''); ?></td>
                                        <td>
                                            <?php if (!empty($fiche['signatureCP'])): ?>
                                                <span class="status-badge status-approved"><i class="fas fa-check"></i> Validé</span>
                                            <?php else: ?>
                                                <span class="status-badge status-pending"><i class="fas fa-clock"></i> En attente</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="table-actions">
                                            <button class="btn btn-sm btn-outline" onclick="viewDetails(<?php echo $fiche['id']; ?>)">
                                                <i class="fas fa-eye"></i> Voir détails
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="section-chief-section">
                <h3 class="section-title"><i class="fas fa-check-circle"></i> Validation des Fiches Finalisées</h3>
                <p>Validation des fiches de prestation après finalisation des cours.</p>
                
                <div class="section-chief-actions">
                    <button class="btn btn-primary" onclick="showPending()"><i class="fas fa-list"></i> Voir fiches à valider</button>
                    <button class="btn btn-success" onclick="showFilterForm('periode')"><i class="fas fa-filter"></i> Filtrer par période</button>
                </div>
                
                <div class="section-chief-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Enseignant</th>
                                <th>Cours</th>
                                <th>Jours Effectués</th>
                                <th>Total Heures</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($fichesAValider)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Aucune fiche à valider</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($fichesAValider as $fiche): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($fiche['enseignant_nom'] . ' ' . $fiche['enseignant_postnom'] . ' ' . $fiche['enseignant_prenom']); ?></td>
                                        <td><?php echo htmlspecialchars($fiche['cours_nom']); ?></td>
                                        <td><?php echo htmlspecialchars($fiche['total_jours']); ?> jours</td>
                                        <td><?php echo htmlspecialchars($fiche['total_heures']); ?> heures</td>
                                        <td><span class="status-badge status-pending"><i class="fas fa-clock"></i> En attente validation</span></td>
                                        <td class="table-actions">
                                            <button class="btn btn-sm btn-success" onclick="validateFiche(<?php echo $fiche['id']; ?>)">
                                                <i class="fas fa-check"></i> Valider
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="rejectFiche(<?php echo $fiche['id']; ?>)">
                                                <i class="fas fa-times"></i> Rejeter
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <footer class="section-chief-footer">
        <p>&copy; 2025 Système de Suivi de Prestation. Tous droits réservés.</p>
    </footer>

    <script>
        function filterToday() {
            alert("Affichage des fiches du jour");
            // Cette fonction serait implémentée pour filtrer les résultats par date du jour
        }

        function showFilterForm(type) {
            alert("Filtrer par " + type);
            // Cette fonction serait implémentée pour afficher un formulaire de filtrage
        }

        function viewDetails(ficheId) {
            alert("Voir les détails de la fiche #" + ficheId);
            // Cette fonction serait implémentée pour afficher les détails d'une fiche
        }

        function showPending() {
            alert("Affichage des fiches en attente de validation");
            // Cette fonction serait implémentée pour afficher uniquement les fiches en attente
        }

        function validateFiche(ficheId) {
            if (confirm("Êtes-vous sûr de vouloir valider cette fiche de prestation ?")) {
                // Créer un formulaire dynamiquement pour la validation
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'scripts/validate_prestation.php';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ficheId';
                input.value = ficheId;
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function rejectFiche(ficheId) {
            if (confirm("Êtes-vous sûr de vouloir rejeter cette fiche de prestation ?")) {
                // Créer un formulaire dynamiquement pour le rejet
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'scripts/reject_prestation.php';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ficheId';
                input.value = ficheId;
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>