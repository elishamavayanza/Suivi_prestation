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
    <title>Cours Finis - Secrétaire de Section</title>
    <link rel="stylesheet" href="secretaire_section_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="secretary-header">
        <h1><i class="fas fa-clipboard-check"></i> Gestion des Cours Finis</h1>
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
                <h2>Cours Finis</h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Accueil</a></li>
                    <li>Cours Finis</li>
                </ul>
            </div>

            <div class="secretary-section">
                <h3 class="section-title"><i class="fas fa-list"></i> Liste des Cours Finis</h3>
                <p>En tant que secrétaire de section, vous pouvez consulter et compléter les fiches des matières finies.</p>
                
                <div class="secretary-table mt-20">
                    <button class="btn btn-primary" style="margin-right: 10px;"><i class="fas fa-sync"></i> Actualiser</button>
                    <button class="btn btn-success" style="margin-right: 10px;"><i class="fas fa-filter"></i> Filtrer par période</button>
                    <button class="btn btn-warning"><i class="fas fa-search"></i> Rechercher par enseignant</button>
                </div>

                <div class="secretary-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Cours</th>
                                <th>Enseignant</th>
                                <th>Date Début</th>
                                <th>Date Fin</th>
                                <th>Volume Horaire</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Mathématiques Avancées</td>
                                <td>Dupont Jean</td>
                                <td>2025-09-01</td>
                                <td>2025-11-30</td>
                                <td>60 heures</td>
                                <td><span class="status-badge status-finished"><i class="fas fa-check-circle"></i> Terminé</span></td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-outline"><i class="fas fa-edit"></i> Compléter fiche</button>
                                    <button class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Imprimer</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Physique Quantique</td>
                                <td>Martin Marie</td>
                                <td>2025-09-05</td>
                                <td>2025-11-25</td>
                                <td>50 heures</td>
                                <td><span class="status-badge status-finished"><i class="fas fa-check-circle"></i> Terminé</span></td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-outline"><i class="fas fa-edit"></i> Compléter fiche</button>
                                    <button class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Imprimer</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Chimie Organique</td>
                                <td>Bernard Pierre</td>
                                <td>2025-09-10</td>
                                <td>2025-11-20</td>
                                <td>45 heures</td>
                                <td><span class="status-badge status-finished"><i class="fas fa-check-circle"></i> Terminé</span></td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-outline"><i class="fas fa-edit"></i> Compléter fiche</button>
                                    <button class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Imprimer</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="secretary-section">
                <h3 class="section-title"><i class="fas fa-plus-circle"></i> Compléter une Fiche de Cours Fini</h3>
                <p>Complétez les informations pour un cours qui vient de se terminer.</p>
                
                <form class="secretary-form">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="course">Cours *</label>
                                <select id="course" class="form-control" required>
                                    <option value="">Sélectionner un cours</option>
                                    <option value="math">Mathématiques Avancées</option>
                                    <option value="phys">Physique Quantique</option>
                                    <option value="chim">Chimie Organique</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label for="teacher">Enseignant *</label>
                                <select id="teacher" class="form-control" required>
                                    <option value="">Sélectionner un enseignant</option>
                                    <option value="dupont">Dupont Jean</option>
                                    <option value="martin">Martin Marie</option>
                                    <option value="bernard">Bernard Pierre</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="startDate">Date de Début *</label>
                                <input type="date" id="startDate" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label for="endDate">Date de Fin *</label>
                                <input type="date" id="endDate" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="hours">Volume Horaire *</label>
                                <input type="number" id="hours" class="form-control" min="1" required>
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="form-group">
                                <label for="observations">Observations</label>
                                <textarea id="observations" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Enregistrer la Fiche</button>
                        <button type="reset" class="btn btn-outline"><i class="fas fa-times"></i> Annuler</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <footer class="secretary-footer">
        <p>&copy; 2025 Système de Suivi de Prestation. Tous droits réservés.</p>
    </footer>
</body>
</html>