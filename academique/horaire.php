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
    <title>Gestion des Horaires - Service Académique</title>
    <link rel="stylesheet" href="academic_admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="academic-header">
        <h1><i class="fas fa-clock"></i> Gestion des Horaires - Service Académique</h1>
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
                    <li><a href="horaire.php" class="active"><i class="fas fa-clock"></i> <span>Gestion des Horaires</span></a></li>
                    <li><a href="chargehoraire.php"><i class="fas fa-hourglass-half"></i> <span>Charges Horaire</span></a></li>
                    <li><a href="academique_prestation.php"><i class="fas fa-file-invoice"></i> <span>Fiches de Prestation</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="academic-main">
            <div class="content-header">
                <h2>Gestion des Horaires</h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Accueil</a></li>
                    <li>Gestion des Horaires</li>
                </ul>
            </div>

            <div class="academic-alert academic-alert-info">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>Service Académique:</strong> 
                    En tant que membre du service académique, vous pouvez consulter les emplois du temps et effectuer des validations.
                </div>
            </div>

            <div class="academic-section">
                <h3 class="section-title"><i class="fas fa-calendar-alt"></i> Emploi du Temps Global</h3>
                <p>Consultation des emplois du temps de tous les départements.</p>
                
                <div class="academic-actions">
                    <button class="btn btn-primary"><i class="fas fa-search"></i> Rechercher par Enseignant</button>
                    <button class="btn btn-success"><i class="fas fa-search"></i> Rechercher par Salle</button>
                    <button class="btn btn-info"><i class="fas fa-search"></i> Rechercher par Cours</button>
                </div>

                <div class="academic-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Heures</th>
                                <th>Lundi</th>
                                <th>Mardi</th>
                                <th>Mercredi</th>
                                <th>Jeudi</th>
                                <th>Vendredi</th>
                                <th>Samedi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>08:00 - 09:00</td>
                                <td>Mathématiques<br>(Salle A1)<br><small>Prof. Dupont</small></td>
                                <td>Physique<br>(Salle B2)<br><small>Prof. Martin</small></td>
                                <td>Chimie<br>(Labo 1)<br><small>Prof. Bernard</small></td>
                                <td>Biologie<br>(Labo 2)<br><small>Prof. Robert</small></td>
                                <td>Français<br>(Salle C3)<br><small>Prof. Leroy</small></td>
                                <td>Anglais<br>(Salle D4)<br><small>Prof. Moreau</small></td>
                            </tr>
                            <tr>
                                <td>09:00 - 10:00</td>
                                <td>Histoire<br>(Salle A1)<br><small>Prof. Leroy</small></td>
                                <td>Géographie<br>(Salle B2)<br><small>Prof. Moreau</small></td>
                                <td>Mathématiques<br>(Labo 1)<br><small>Prof. Dupont</small></td>
                                <td>Physique<br>(Labo 2)<br><small>Prof. Martin</small></td>
                                <td>Chimie<br>(Salle C3)<br><small>Prof. Bernard</small></td>
                                <td>Biologie<br>(Salle D4)<br><small>Prof. Robert</small></td>
                            </tr>
                            <tr>
                                <td>10:00 - 11:00</td>
                                <td>Anglais<br>(Salle A1)<br><small>Prof. Moreau</small></td>
                                <td>Français<br>(Salle B2)<br><small>Prof. Leroy</small></td>
                                <td>Histoire<br>(Labo 1)<br><small>Prof. Leroy</small></td>
                                <td>Géographie<br>(Labo 2)<br><small>Prof. Moreau</small></td>
                                <td>Mathématiques<br>(Salle C3)<br><small>Prof. Dupont</small></td>
                                <td>Physique<br>(Salle D4)<br><small>Prof. Martin</small></td>
                            </tr>
                            <tr>
                                <td>11:00 - 12:00</td>
                                <td>Chimie<br>(Salle A1)<br><small>Prof. Bernard</small></td>
                                <td>Biologie<br>(Salle B2)<br><small>Prof. Robert</small></td>
                                <td>Anglais<br>(Labo 1)<br><small>Prof. Moreau</small></td>
                                <td>Français<br>(Labo 2)<br><small>Prof. Leroy</small></td>
                                <td>Histoire<br>(Salle C3)<br><small>Prof. Leroy</small></td>
                                <td>Géographie<br>(Salle D4)<br><small>Prof. Moreau</small></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="academic-section">
                <h3 class="section-title"><i class="fas fa-door-open"></i> Gestion des Salles</h3>
                <p>Attribution des salles aux différents cours.</p>
                
                <div class="academic-actions">
                    <button class="btn btn-primary"><i class="fas fa-plus-circle"></i> Ajouter une salle</button>
                    <button class="btn btn-success"><i class="fas fa-edit"></i> Modifier une salle</button>
                </div>
                
                <div class="academic-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Nom de la Salle</th>
                                <th>Type</th>
                                <th>Capacité</th>
                                <th>Équipements</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Salle A1</td>
                                <td>Cours Théorique</td>
                                <td>30 étudiants</td>
                                <td>Tableau blanc, Vidéoprojecteur</td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-success"><i class="fas fa-edit"></i> Modifier</button>
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Labo 1</td>
                                <td>Laboratoire</td>
                                <td>20 étudiants</td>
                                <td>Matériel scientifique, Évier, Gaz</td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-success"><i class="fas fa-edit"></i> Modifier</button>
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Salle D4</td>
                                <td>Cours Théorique</td>
                                <td>25 étudiants</td>
                                <td>Tableau blanc, Vidéoprojecteur, Connexion Internet</td>
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

    <footer class="academic-footer">
        <p>&copy; 2025 Système de Suivi de Prestation. Tous droits réservés.</p>
    </footer>
</body>
</html>