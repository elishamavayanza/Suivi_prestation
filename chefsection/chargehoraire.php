<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Chefdesection') {
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
    <title>Charges Horaire - Chef de Section</title>
    <link rel="stylesheet" href="chef_section_cp_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="section-chief-header">
        <h1><i class="fas fa-hourglass-half"></i> Charges Horaire des Enseignants</h1>
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
                    <li><a href="chargehoraire.php" class="active"><i class="fas fa-hourglass-half"></i> <span>Charges Horaire</span></a></li>
                    <li><a href="prestation.php"><i class="fas fa-file-invoice"></i> <span>Fiches de Prestation</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="section-chief-main">
            <div class="content-header">
                <h2>Charges Horaire</h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Accueil</a></li>
                    <li>Charges Horaire</li>
                </ul>
            </div>

            <div class="section-chief-section">
                <h3 class="section-title"><i class="fas fa-plus-circle"></i> Elaboration des Charges Horaire</h3>
                <p>En tant que chef de section, vous pouvez élaborer et gérer les charges horaire des enseignants.</p>
                
                <div class="section-chief-actions">
                    <button class="btn btn-primary"><i class="fas fa-plus-circle"></i> Nouvelle Charge Horaire</button>
                    <button class="btn btn-success"><i class="fas fa-user-plus"></i> Affecter un Enseignant</button>
                    <button class="btn btn-warning"><i class="fas fa-sync-alt"></i> Réviser les Charges</button>
                </div>

                <div class="section-chief-form">
                    <h3 class="form-title">Formulaire de Charge Horaire</h3>
                    <form>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="enseignant" class="form-label">Enseignant</label>
                                    <select id="enseignant" class="form-control">
                                        <option value="">Sélectionnez un enseignant</option>
                                        <option value="1">Dupont Jean</option>
                                        <option value="2">Martin Marie</option>
                                        <option value="3">Bernard Pierre</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="cours" class="form-label">Cours</label>
                                    <select id="cours" class="form-control">
                                        <option value="">Sélectionnez un cours</option>
                                        <option value="math">Mathématiques</option>
                                        <option value="phys">Physique</option>
                                        <option value="chim">Chimie</option>
                                        <option value="bio">Biologie</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="volume" class="form-label">Volume Horaire (heures)</label>
                                    <input type="number" id="volume" class="form-control" min="0" placeholder="Entrez le volume horaire">
                                </div>
                            </div>
                            
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="periode" class="form-label">Période</label>
                                    <select id="periode" class="form-control">
                                        <option value="">Sélectionnez une période</option>
                                        <option value="semestre1">Semestre 1</option>
                                        <option value="semestre2">Semestre 2</option>
                                        <option value="annuel">Annuel</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer la Charge</button>
                    </form>
                </div>
            </div>

            <div class="section-chief-section">
                <h3 class="section-title"><i class="fas fa-list"></i> Charges Horaire Actuelles</h3>
                <p>Liste des charges horaire attribuées aux enseignants.</p>
                
                <div class="section-chief-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Enseignant</th>
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
                                <td>60 heures</td>
                                <td>Semestre 1</td>
                                <td><span class="status-badge status-approved"><i class="fas fa-check"></i> Validé</span></td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-success"><i class="fas fa-edit"></i> Modifier</button>
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Martin Marie</td>
                                <td>Physique</td>
                                <td>45 heures</td>
                                <td>Semestre 1</td>
                                <td><span class="status-badge status-pending"><i class="fas fa-clock"></i> En attente</span></td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-success"><i class="fas fa-edit"></i> Modifier</button>
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Bernard Pierre</td>
                                <td>Chimie</td>
                                <td>50 heures</td>
                                <td>Annuel</td>
                                <td><span class="status-badge status-approved"><i class="fas fa-check"></i> Validé</span></td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-success"><i class="fas fa-edit"></i> Modifier</button>
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <footer class="section-chief-footer">
        <p>&copy; 2025 Système de Suivi de Prestation. Tous droits réservés.</p>
    </footer>
</body>
</html>