<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'secretaire') {
    header("Location: ../login.php");
    exit();
}

include '../config/connexion.php';

// Déterminer la page active
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiches de Prestation - Secrétaire de Section</title>
    <link rel="stylesheet" href="secretaire_section_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="secretary-header">
        <h1><i class="fas fa-file-invoice"></i> Consultation des Fiches de Prestation</h1>
        <div class="header-actions">
            <button onclick="location.href='index.php'"><i class="fas fa-home"></i> <span>Accueil</span></button>
            <button onclick="location.href='../script/logout.php'"><i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span></button>
        </div>
    </header>

    <!-- Container -->
    <div class="secretary-container">
        <!-- Sidebar -->
        <aside class="secretary-sidebar">
            <div class="secretary-sidebar-header">
                <img src="../image/logo.jpg" alt="Logo">
                <h2>Secrétaire de Section</h2>
                <p><?php echo $_SESSION['username']; ?></p>
            </div>
            <nav class="secretary-nav-menu">
                <ul>
                    <li><a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>"><i class="fas fa-home"></i> <span>Tableau de bord</span></a></li>
                    <li><a href="prestation.php" class="<?php echo ($current_page == 'prestation.php') ? 'active' : ''; ?>"><i class="fas fa-file-invoice"></i> <span>Fiches de Prestation</span></a></li>
                    <li><a href="coursfini.php" class="<?php echo ($current_page == 'coursfini.php') ? 'active' : ''; ?>"><i class="fas fa-clipboard-check"></i> <span>Cours Finis</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="secretary-main">
            <div class="content-header">
                <h2>Fiches de Prestation</h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Accueil</a></li>
                    <li>Fiches de Prestation</li>
                </ul>
            </div>

            <div class="secretary-section">
                <h3 class="section-title"><i class="fas fa-calendar-day"></i> Consultation Quotidienne</h3>
                <p>En tant que secrétaire de section, vous pouvez consulter les fiches de prestation quotidiennement.</p>
                
                <div class="secretary-table mt-20">
                    <button class="btn btn-primary" style="margin-right: 10px;"><i class="fas fa-calendar-day"></i> Voir les fiches du jour</button>
                    <button class="btn btn-success" style="margin-right: 10px;"><i class="fas fa-filter"></i> Filtrer par date</button>
                    <button class="btn btn-warning"><i class="fas fa-search"></i> Rechercher par enseignant</button>
                </div>

                <div class="secretary-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Enseignant</th>
                                <th>Cours</th>
                                <th>Heure Début</th>
                                <th>Heure Fin</th>
                                <th>Salle</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2025-11-28</td>
                                <td>Dupont Jean</td>
                                <td>Mathématiques</td>
                                <td>08:00</td>
                                <td>10:00</td>
                                <td>Salle A1</td>
                                <td><span class="status-badge status-approved"><i class="fas fa-check"></i> Terminé</span></td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-outline"><i class="fas fa-eye"></i> Voir détails</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2025-11-28</td>
                                <td>Martin Marie</td>
                                <td>Physique</td>
                                <td>10:00</td>
                                <td>12:00</td>
                                <td>Labo 1</td>
                                <td><span class="status-badge status-pending"><i class="fas fa-sync"></i> En cours</span></td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-outline"><i class="fas fa-eye"></i> Voir détails</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2025-11-28</td>
                                <td>Bernard Pierre</td>
                                <td>Chimie</td>
                                <td>14:00</td>
                                <td>16:00</td>
                                <td>Labo 2</td>
                                <td><span class="status-badge status-pending"><i class="fas fa-clock"></i> À venir</span></td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-outline"><i class="fas fa-eye"></i> Voir détails</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="secretary-section">
                <h3 class="section-title"><i class="fas fa-check-circle"></i> Validation des Fiches Finalisées</h3>
                <p>Validation des fiches de prestation après finalisation des cours.</p>
                
                <div class="secretary-table mt-20">
                    <button class="btn btn-primary" style="margin-right: 10px;"><i class="fas fa-list"></i> Voir fiches à valider</button>
                    <button class="btn btn-success"><i class="fas fa-filter"></i> Filtrer par période</button>
                </div>
                
                <div class="secretary-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Enseignant</th>
                                <th>Cours</th>
                                <th>Total Heures</th>
                                <th>Observations</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2025-11-27</td>
                                <td>Dupont Jean</td>
                                <td>Mathématiques</td>
                                <td>2 heures</td>
                                <td>Cours terminé avec succès</td>
                                <td><span class="status-badge status-pending"><i class="fas fa-clock"></i> En attente validation</span></td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-success"><i class="fas fa-check"></i> Valider</button>
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i> Rejeter</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2025-11-27</td>
                                <td>Martin Marie</td>
                                <td>Physique</td>
                                <td>2 heures</td>
                                <td>Retard de 15 minutes</td>
                                <td><span class="status-badge status-approved"><i class="fas fa-check"></i> Validé</span></td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-outline"><i class="fas fa-eye"></i> Détails</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2025-11-26</td>
                                <td>Bernard Pierre</td>
                                <td>Chimie</td>
                                <td>2 heures</td>
                                <td>Problème technique avec équipement</td>
                                <td><span class="status-badge status-pending"><i class="fas fa-clock"></i> En attente validation</span></td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-success"><i class="fas fa-check"></i> Valider</button>
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i> Rejeter</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <footer class="secretary-footer">
        <p>&copy; 2025 Système de Suivi de Prestation. Tous droits réservés.</p>
    </footer>
</body>
</html>