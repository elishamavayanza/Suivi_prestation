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
    <title>Espace Étudiant - ISP Muhangi</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="student_styles.css">
</head>
<body>
    <div class="container-fluid">
        <!-- En-tête -->
        <header class="row bg-primary text-white p-3 mb-4">
            <div class="col-12">
                <h1 class="text-center">Espace Étudiant - Institut Supérieur Pédagogique MUHANGI</h1>
                <p class="text-end mb-0">
                    Connecté en tant que: <?php echo $_SESSION['username']; ?> |
                    <a href="../script/logout.php" class="text-white">Déconnexion</a>
                </p>
            </div>
        </header>

        <!-- Menu principal -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-secondary mb-4">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">Tableau de bord</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="horaire.php">Mon Horaire</a>
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
        <main class="row">
            <div class="col-md-12">
                <div class="welcome-card card mb-4">
                    <div class="card-header bg-info text-white">
                        <h2>Bienvenue, <?php echo $_SESSION['username']; ?>!</h2>
                    </div>
                    <div class="card-body">
                        <p>Dans votre espace étudiant, vous pouvez :</p>
                        <ul>
                            <li>Consulter votre horaire</li>
                            <li>Suivre l'avancement des cours via les fiches de prestations validées</li>
                            <li>Lire le plan des cours rempli par les enseignants</li>
                            <li>Recevoir les messages officiels de la section ou du chef de promotion</li>
                        </ul>
                    </div>
                </div>
                
                <!-- Section des annonces rapides -->
                <div class="announcements-section">
                    <h3>Annonces récentes</h3>
                    <div class="announcement-card card mb-3">
                        <div class="card-header bg-warning text-dark">
                            Annonce importante - 25 Novembre 2025
                        </div>
                        <div class="card-body">
                            <p class="card-text">Les examens du premier semestre commenceront le 15 décembre 2025. Consultez les horaires sur l'onglet "Mon Horaire".</p>
                        </div>
                    </div>
                    
                    <div class="announcement-card card mb-3">
                        <div class="card-header bg-success text-white">
                            Information - 20 Novembre 2025
                        </div>
                        <div class="card-body">
                            <p class="card-text">Les fiches de prestations du mois de novembre ont été mises à jour. Vous pouvez suivre l'avancement dans l'onglet correspondant.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Pied de page -->
        <footer class="row bg-dark text-white mt-4 p-3">
            <div class="col-12 text-center">
                <p>&copy; 2025 Institut Supérieur Pédagogique MUHANGI - Tous droits réservés</p>
            </div>
        </footer>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>