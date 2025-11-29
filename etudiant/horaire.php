<?php
session_start();
include("../script/config.php");

// Vérifier si l'utilisateur est connecté et s'il est étudiant
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Etudiant') {
    header("Location: ../login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Horaire - Espace Étudiant</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="student_styles.css">
</head>
<body>
    <!-- En-tête -->
    <header>
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Mon Horaire - Espace Étudiant</h1>
                <div>
                    <span><?php echo $_SESSION['username']; ?></span> | 
                    <a href="../script/logout.php" class="text-white">Déconnexion</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <!-- Menu principal -->
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Tableau de bord</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="horaire.php">Mon Horaire</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="prestation.php">Avancement des Cours</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="description.php">Plan du Cours</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="annonces.php">Annonces</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Contenu principal -->
        <main>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>Mon Horaire de Cours</h2>
                        </div>
                        <div class="card-body">
                            <p class="mb-4">Voici votre horaire de cours pour cette semaine :</p>
                            
                            <!-- Exemple d'horaire sous forme de tableau -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
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
                                            <td>08:00 - 09:30</td>
                                            <td>Mathématiques<br><small class="text-muted">Salle A1</small></td>
                                            <td>Physique<br><small class="text-muted">Salle B2</small></td>
                                            <td>-</td>
                                            <td>Chimie<br><small class="text-muted">Labo 1</small></td>
                                            <td>Français<br><small class="text-muted">Salle C3</small></td>
                                            <td>-</td>
                                        </tr>
                                        <tr>
                                            <td>09:45 - 11:15</td>
                                            <td>Anglais<br><small class="text-muted">Salle D4</small></td>
                                            <td>-</td>
                                            <td>Histoire<br><small class="text-muted">Salle A1</small></td>
                                            <td>-</td>
                                            <td>Biologie<br><small class="text-muted">Labo 2</small></td>
                                            <td>-</td>
                                        </tr>
                                        <tr>
                                            <td>11:30 - 13:00</td>
                                            <td>Philosophie<br><small class="text-muted">Salle B2</small></td>
                                            <td>Mathématiques<br><small class="text-muted">Salle A1</small></td>
                                            <td>Français<br><small class="text-muted">Salle C3</small></td>
                                            <td>Anglais<br><small class="text-muted">Salle D4</small></td>
                                            <td>-</td>
                                            <td>-</td>
                                        </tr>
                                        <tr>
                                            <td>14:00 - 15:30</td>
                                            <td>-</td>
                                            <td>EPS<br><small class="text-muted">Gymnase</small></td>
                                            <td>-</td>
                                            <td>Mathématiques<br><small class="text-muted">Salle B2</small></td>
                                            <td>Physique<br><small class="text-muted">Labo 1</small></td>
                                            <td>-</td>
                                        </tr>
                                        <tr>
                                            <td>15:45 - 17:15</td>
                                            <td>Biologie<br><small class="text-muted">Labo 2</small></td>
                                            <td>-</td>
                                            <td>Chimie<br><small class="text-muted">Labo 1</small></td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="alert alert-info">
                                <strong>Note :</strong> Les horaires peuvent être mis à jour périodiquement. 
                                Consultez régulièrement cette page pour les dernières modifications.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Pied de page -->
        <footer>
            <div class="container-fluid">
                <div class="col-12 text-center">
                    <p>&copy; 2025 Institut Supérieur Pédagogique MUHANGI - Tous droits réservés</p>
                </div>
            </div>
        </footer>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>