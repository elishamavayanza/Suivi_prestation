<?php
session_start();
if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'Academique' && $_SESSION['role'] != 'SGA')) {
    header("Location: ../login.php");
    exit();
}

include '../config/connexion.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charges Horaire - Service Académique</title>
    <link rel="stylesheet" href="academic_styles.css">
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
                            <tr>
                                <td>Dupont Jean</td>
                                <td>Mathématiques</td>
                                <td>Mathématiques Avancées</td>
                                <td>60 heures</td>
                                <td>Semestre 1</td>
                                <td><span class="status-badge status-approved"><i class="fas fa-check"></i> Validé</span></td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Détails</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Martin Marie</td>
                                <td>Physique</td>
                                <td>Physique Quantique</td>
                                <td>45 heures</td>
                                <td>Semestre 1</td>
                                <td><span class="status-badge status-pending"><i class="fas fa-clock"></i> En attente</span></td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-success"><i class="fas fa-check"></i> Valider</button>
                                    <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Détails</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Bernard Pierre</td>
                                <td>Chimie</td>
                                <td>Chimie Organique</td>
                                <td>50 heures</td>
                                <td>Annuel</td>
                                <td><span class="status-badge status-approved"><i class="fas fa-check"></i> Validé</span></td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Détails</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Robert Claire</td>
                                <td>Biologie</td>
                                <td>Biologie Moléculaire</td>
                                <td>40 heures</td>
                                <td>Semestre 1</td>
                                <td><span class="status-badge status-approved"><i class="fas fa-check"></i> Validé</span></td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Détails</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Leroy Sophie</td>
                                <td>Lettres</td>
                                <td>Français</td>
                                <td>55 heures</td>
                                <td>Annuel</td>
                                <td><span class="status-badge status-pending"><i class="fas fa-clock"></i> En attente</span></td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-success"><i class="fas fa-check"></i> Valider</button>
                                    <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Détails</button>
                                </td>
                            </tr>
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
                            <tr>
                                <td>Mathématiques</td>
                                <td>8 enseignants</td>
                                <td>320 heures</td>
                                <td>40 heures</td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Détails</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Physique</td>
                                <td>6 enseignants</td>
                                <td>240 heures</td>
                                <td>40 heures</td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Détails</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Chimie</td>
                                <td>5 enseignants</td>
                                <td>200 heures</td>
                                <td>40 heures</td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Détails</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Biologie</td>
                                <td>4 enseignants</td>
                                <td>160 heures</td>
                                <td>40 heures</td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Détails</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Lettres</td>
                                <td>7 enseignants</td>
                                <td>280 heures</td>
                                <td>40 heures</td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Détails</button>
                                </td>
                            </tr>
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