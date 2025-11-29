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
    <title>Annonces - Espace Étudiant</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="student_styles.css">
</head>
<body>
    <!-- En-tête -->
    <header>
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Annonces - Espace Étudiant</h1>
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
                            <a class="nav-link" href="horaire.php">Mon Horaire</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="prestation.php">Avancement des Cours</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="description.php">Plan du Cours</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="annonces.php">Annonces</a>
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
                            <h2>Messages Officiels</h2>
                        </div>
                        <div class="card-body">
                            <p class="mb-4">Retrouvez ici tous les messages officiels de la section et du chef de promotion :</p>
                            
                            <!-- Liste des annonces -->
                            <div class="announcements-list">
                                <div class="announcement-item card mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="mb-0">Convocation pour la réunion des étudiants</h5>
                                            <span>25 Novembre 2025</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p>Tous les étudiants sont convoqués à une réunion générale le vendredi 29 novembre 2025 à 14h00 en salle A1. Ordre du jour : préparation des examens de fin de semestre.</p>
                                        <span class="badge bg-secondary">Posté par: Chef de Promotion</span>
                                    </div>
                                </div>
                                
                                <div class="announcement-item card mb-3">
                                    <div class="card-header bg-success text-white">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="mb-0">Disponibilité des notes du contrôle continu</h5>
                                            <span>22 Novembre 2025</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p>Les notes du contrôle continu du premier trimestre sont disponibles sur l'ENT. Les étudiants ayant des notes inférieures à 8/20 sont invités à participer aux séances de soutien prévues à partir du 1er décembre.</p>
                                        <span class="badge bg-secondary">Posté par: Chef de Section</span>
                                    </div>
                                </div>
                                
                                <div class="announcement-item card mb-3">
                                    <div class="card-header bg-warning text-dark">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="mb-0">Modification des horaires de bibliothèque</h5>
                                            <span>20 Novembre 2025</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p>La bibliothèque sera ouverte exceptionnellement jusqu'à 21h00 du lundi au jeudi cette semaine pour faciliter les révisions. Le week-end, les horaires habituels s'appliquent.</p>
                                        <span class="badge bg-secondary">Posté par: Administration</span>
                                    </div>
                                </div>
                                
                                <div class="announcement-item card mb-3">
                                    <div class="card-header bg-info text-white">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="mb-0">Concours d'entrée à l'agrégation</h5>
                                            <span>18 Novembre 2025</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p>Les inscriptions pour le concours d'entrée à l'agrégation de l'année prochaine ouvriront le 1er décembre. Des sessions d'information auront lieu le 5 et 12 décembre à 16h en salle B2.</p>
                                        <span class="badge bg-secondary">Posté par: Chef de Section</span>
                                    </div>
                                </div>
                                
                                <div class="announcement-item card mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="mb-0">Stage pédagogique - Appel à candidatures</h5>
                                            <span>15 Novembre 2025</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p>Les candidatures pour le stage pédagogique du second semestre sont ouvertes jusqu'au 15 janvier. Déposer votre dossier auprès du secrétariat pédagogique. Plus d'informations sur l'intranet.</p>
                                        <span class="badge bg-secondary">Posté par: Chef de Promotion</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Pagination -->
                            <nav aria-label="Pagination des annonces">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">Précédent</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Suivant</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Information :</strong> Vous recevrez une notification par email pour chaque nouvelle annonce importante.
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