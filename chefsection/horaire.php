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
    <title>Gestion des Horaires - Chef de Section</title>
    <link rel="stylesheet" href="chef_section_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="section-chief-header">
        <h1><i class="fas fa-clock"></i> Gestion des Horaires</h1>
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
                    <li><a href="horaire.php" class="active"><i class="fas fa-clock"></i> <span>Gestion des Horaires</span></a></li>
                    <li><a href="chargehoraire.php"><i class="fas fa-hourglass-half"></i> <span>Charges Horaire</span></a></li>
                    <li><a href="prestation.php"><i class="fas fa-file-invoice"></i> <span>Fiches de Prestation</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="section-chief-main">
            <div class="content-header">
                <h2>Gestion des Horaires</h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Accueil</a></li>
                    <li>Gestion des Horaires</li>
                </ul>
            </div>

            <div class="section-chief-section">
                <h3 class="section-title"><i class="fas fa-calendar-alt"></i> Emploi du Temps</h3>
                <p>En tant que chef de section, vous pouvez consulter et gérer les emplois du temps des enseignants.</p>
                
                <div class="section-chief-actions">
                    <button class="btn btn-primary"><i class="fas fa-plus-circle"></i> Créer un nouvel horaire</button>
                    <button class="btn btn-success"><i class="fas fa-edit"></i> Modifier un horaire</button>
                    <button class="btn btn-danger"><i class="fas fa-trash"></i> Supprimer un horaire</button>
                </div>

                <div class="section-chief-table">
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
                                <td>Mathématiques<br>(Salle A1)</td>
                                <td>Physique<br>(Salle B2)</td>
                                <td>Chimie<br>(Labo 1)</td>
                                <td>Biologie<br>(Labo 2)</td>
                                <td>Français<br>(Salle C3)</td>
                                <td>Anglais<br>(Salle D4)</td>
                            </tr>
                            <tr>
                                <td>09:00 - 10:00</td>
                                <td>Histoire<br>(Salle A1)</td>
                                <td>Géographie<br>(Salle B2)</td>
                                <td>Mathématiques<br>(Labo 1)</td>
                                <td>Physique<br>(Labo 2)</td>
                                <td>Chimie<br>(Salle C3)</td>
                                <td>Biologie<br>(Salle D4)</td>
                            </tr>
                            <tr>
                                <td>10:00 - 11:00</td>
                                <td>Anglais<br>(Salle A1)</td>
                                <td>Français<br>(Salle B2)</td>
                                <td>Histoire<br>(Labo 1)</td>
                                <td>Géographie<br>(Labo 2)</td>
                                <td>Mathématiques<br>(Salle C3)</td>
                                <td>Physique<br>(Salle D4)</td>
                            </tr>
                            <tr>
                                <td>11:00 - 12:00</td>
                                <td>Chimie<br>(Salle A1)</td>
                                <td>Biologie<br>(Salle B2)</td>
                                <td>Anglais<br>(Labo 1)</td>
                                <td>Français<br>(Labo 2)</td>
                                <td>Histoire<br>(Salle C3)</td>
                                <td>Géographie<br>(Salle D4)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="section-chief-section">
                <h3 class="section-title"><i class="fas fa-door-open"></i> Gestion des Salles</h3>
                <p>Attribution des salles aux différents cours.</p>
                
                <div class="section-chief-actions">
                    <button class="btn btn-primary"><i class="fas fa-plus-circle"></i> Ajouter une salle</button>
                    <button class="btn btn-success"><i class="fas fa-edit"></i> Modifier une salle</button>
                </div>
                
                <div class="section-chief-table">
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

    <footer class="section-chief-footer">
        <p>&copy; 2025 Système de Suivi de Prestation. Tous droits réservés.</p>
    </footer>
</body>
</html>