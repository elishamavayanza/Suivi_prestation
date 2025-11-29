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
    <title>Avancement des Cours - Espace Étudiant</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="student_styles.css">
</head>
<body>
    <div class="container-fluid">
        <!-- En-tête -->
        <header class="row bg-primary text-white p-3 mb-4">
            <div class="col-12">
                <h1 class="text-center">Avancement des Cours - Espace Étudiant</h1>
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
                            <a class="nav-link" href="index.php">Tableau de bord</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="horaire.php">Mon Horaire</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="prestation.php">Avancement des Cours</a>
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
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h2>État d'Avancement des Cours</h2>
                    </div>
                    <div class="card-body">
                        <p class="mb-4">Consultez ci-dessous l'avancement de vos cours basé sur les fiches de prestations validées :</p>
                        
                        <!-- Liste des cours avec leur avancement -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Cours</th>
                                        <th>Enseignant</th>
                                        <th>Progression</th>
                                        <th>Statut</th>
                                        <th>Dernière mise à jour</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Mathématiques</td>
                                        <td>Prof. Dupont</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75%</div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">En cours</span></td>
                                        <td>24 Novembre 2025</td>
                                    </tr>
                                    <tr>
                                        <td>Physique</td>
                                        <td>Prof. Martin</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">50%</div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-warning">En cours</span></td>
                                        <td>22 Novembre 2025</td>
                                    </tr>
                                    <tr>
                                        <td>Chimie</td>
                                        <td>Prof. Leroy</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">90%</div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">En cours</span></td>
                                        <td>25 Novembre 2025</td>
                                    </tr>
                                    <tr>
                                        <td>Français</td>
                                        <td>Prof. Dubois</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-danger" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-warning">En cours</span></td>
                                        <td>20 Novembre 2025</td>
                                    </tr>
                                    <tr>
                                        <td>Anglais</td>
                                        <td>Prof. Smith</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">65%</div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success">En cours</span></td>
                                        <td>23 Novembre 2025</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-info">
                            <strong>Information :</strong> Ces informations sont mises à jour automatiquement 
                            lorsque les fiches de prestations sont validées par les responsables.
                        </div>
                    </div>
                </div>
                
                <!-- Détails des fiches de prestation récentes -->
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h3>Dernières Fiches de Prestation Validées</h3>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="prestationAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                        Mathématiques - Semaine du 18 Novembre 2025
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#prestationAccordion">
                                    <div class="accordion-body">
                                        <p><strong>Contenu couvert :</strong> Intégrales définies et applications</p>
                                        <p><strong>Objectifs atteints :</strong> Calculer des intégrales simples, appliquer aux calculs d'aires</p>
                                        <p><strong>Difficultés rencontrées :</strong> Quelques étudiants ont des difficultés avec les changements de variable</p>
                                        <p><strong>Remarques :</strong> Séance de révision prévue la semaine prochaine</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                        Physique - Semaine du 15 Novembre 2025
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#prestationAccordion">
                                    <div class="accordion-body">
                                        <p><strong>Contenu couvert :</strong> Lois de Newton et applications</p>
                                        <p><strong>Objectifs atteints :</strong> Résoudre des problèmes de dynamique simple</p>
                                        <p><strong>Difficultés rencontrées :</strong> Difficultés pour identifier les forces en présence</p>
                                        <p><strong>Remarques :</strong> Des exercices supplémentaires seront donnés en TD</p>
                                    </div>
                                </div>
                            </div>
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