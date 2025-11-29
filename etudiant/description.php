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
    <title>Plan du Cours - Espace Étudiant</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="student_styles.css">
</head>
<body>
    <div class="container-fluid">
        <!-- En-tête -->
        <header class="row bg-primary text-white p-3 mb-4">
            <div class="col-12">
                <h1 class="text-center">Plan du Cours - Espace Étudiant</h1>
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
                            <a class="nav-link" href="prestation.php">Avancement des Cours</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="description.php">Plan du Cours</a>
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
                        <h2>Plans de Cours Remplis par les Enseignants</h2>
                    </div>
                    <div class="card-body">
                        <p class="mb-4">Consultez ci-dessous les plans de cours remplis par vos enseignants :</p>
                        
                        <!-- Onglets pour chaque matière -->
                        <ul class="nav nav-tabs" id="courseTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="math-tab" data-bs-toggle="tab" data-bs-target="#math" type="button" role="tab">
                                    Mathématiques
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="physics-tab" data-bs-toggle="tab" data-bs-target="#physics" type="button" role="tab">
                                    Physique
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="chemistry-tab" data-bs-toggle="tab" data-bs-target="#chemistry" type="button" role="tab">
                                    Chimie
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="french-tab" data-bs-toggle="tab" data-bs-target="#french" type="button" role="tab">
                                    Français
                                </button>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="courseTabsContent">
                            <!-- Contenu Mathématiques -->
                            <div class="tab-pane fade show active" id="math" role="tabpanel" aria-labelledby="math-tab">
                                <div class="p-3">
                                    <h3>Mathématiques - Prof. Dupont</h3>
                                    <div class="course-details">
                                        <h4>Objectifs du cours :</h4>
                                        <ul>
                                            <li>Maîtriser les bases de l'analyse mathématique</li>
                                            <li>Comprendre les concepts fondamentaux de l'algèbre linéaire</li>
                                            <li>Développer des compétences en résolution de problèmes</li>
                                        </ul>
                                        
                                        <h4>Programme prévisionnel :</h4>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Semaine</th>
                                                        <th>Thème</th>
                                                        <th>Contenu</th>
                                                        <th>Modalités</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1-2</td>
                                                        <td>Primitives</td>
                                                        <td>Définition, propriétés, primitives usuelles</td>
                                                        <td>Cours magistral, TD</td>
                                                    </tr>
                                                    <tr>
                                                        <td>3-4</td>
                                                        <td>Intégrales définies</td>
                                                        <td>Théorème fondamental, calculs d'aires</td>
                                                        <td>Cours magistral, TD, Projet</td>
                                                    </tr>
                                                    <tr>
                                                        <td>5-7</td>
                                                        <td>Équations différentielles</td>
                                                        <td>Premier et second ordre, applications</td>
                                                        <td>Cours magistral, TP, TD</td>
                                                    </tr>
                                                    <tr>
                                                        <td>8-10</td>
                                                        <td>Algèbre linéaire</td>
                                                        <td>Matrices, déterminants, systèmes linéaires</td>
                                                        <td>Cours magistral, TD, Contrôle continu</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <h4>Ressources pédagogiques :</h4>
                                        <ul>
                                            <li>Polycopié de cours disponible sur la plateforme numérique</li>
                                            <li>"Analyse Mathématique" - J. Stewart (Dunod)</li>
                                            <li>Exercices en ligne sur la plateforme Moodle</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contenu Physique -->
                            <div class="tab-pane fade" id="physics" role="tabpanel" aria-labelledby="physics-tab">
                                <div class="p-3">
                                    <h3>Physique - Prof. Martin</h3>
                                    <div class="course-details">
                                        <h4>Objectifs du cours :</h4>
                                        <ul>
                                            <li>Comprendre les lois fondamentales de la mécanique</li>
                                            <li>Appliquer les principes physiques à des situations concrètes</li>
                                            <li>Développer une démarche expérimentale rigoureuse</li>
                                        </ul>
                                        
                                        <h4>Programme prévisionnel :</h4>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Semaine</th>
                                                        <th>Thème</th>
                                                        <th>Contenu</th>
                                                        <th>Modalités</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1-3</td>
                                                        <td>Cinématique</td>
                                                        <td>Mouvement, vitesse, accélération</td>
                                                        <td>Cours magistral, TD, TP</td>
                                                    </tr>
                                                    <tr>
                                                        <td>4-6</td>
                                                        <td>Dynamique newtonienne</td>
                                                        <td>Lois de Newton, applications</td>
                                                        <td>Cours magistral, TD, TP</td>
                                                    </tr>
                                                    <tr>
                                                        <td>7-8</td>
                                                        <td>Travail et énergie</td>
                                                        <td>Théorème de l'énergie cinétique, puissance</td>
                                                        <td>Cours magistral, TD</td>
                                                    </tr>
                                                    <tr>
                                                        <td>9-10</td>
                                                        <td>Mouvement dans un champ uniforme</td>
                                                        <td>Champ de pesanteur, champ électrostatique</td>
                                                        <td>Cours magistral, TP, Contrôle</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <h4>Ressources pédagogiques :</h4>
                                        <ul>
                                            <li>Support de cours numérique</li>
                                            <li>"Physique générale" - C. Richard (Ellipses)</li>
                                            <li>Simulations interactives sur le site PhET</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contenu Chimie -->
                            <div class="tab-pane fade" id="chemistry" role="tabpanel" aria-labelledby="chemistry-tab">
                                <div class="p-3">
                                    <h3>Chimie - Prof. Leroy</h3>
                                    <div class="course-details">
                                        <h4>Objectifs du cours :</h4>
                                        <ul>
                                            <li>Maîtriser les bases de la chimie générale</li>
                                            <li>Comprendre les transformations chimiques</li>
                                            <li>Acquérir des compétences expérimentales</li>
                                        </ul>
                                        
                                        <h4>Programme prévisionnel :</h4>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Semaine</th>
                                                        <th>Thème</th>
                                                        <th>Contenu</th>
                                                        <th>Modalités</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1-2</td>
                                                        <td>Structure de la matière</td>
                                                        <td>Atomes, molécules, liaisons chimiques</td>
                                                        <td>Cours magistral, TD</td>
                                                    </tr>
                                                    <tr>
                                                        <td>3-5</td>
                                                        <td>Stœchiométrie</td>
                                                        <td>Réactions chimiques, bilan de matière</td>
                                                        <td>Cours magistral, TD, TP</td>
                                                    </tr>
                                                    <tr>
                                                        <td>6-8</td>
                                                        <td>Thermochimie</td>
                                                        <td>Énergie des réactions, enthalpie</td>
                                                        <td>Cours magistral, TD, TP</td>
                                                    </tr>
                                                    <tr>
                                                        <td>9-10</td>
                                                        <td>Équilibres chimiques</td>
                                                        <td>Constante d'équilibre, pH</td>
                                                        <td>Cours magistral, TD, Contrôle</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <h4>Ressources pédagogiques :</h4>
                                        <ul>
                                            <li>Polycopié de cours</li>
                                            <li>"Chimie générale" - P. Depovere (De Boeck)</li>
                                            <li>Plateforme Chimie-Linux pour simulations</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contenu Français -->
                            <div class="tab-pane fade" id="french" role="tabpanel" aria-labelledby="french-tab">
                                <div class="p-3">
                                    <h3>Français - Prof. Dubois</h3>
                                    <div class="course-details">
                                        <h4>Objectifs du cours :</h4>
                                        <ul>
                                            <li>Renforcer les compétences en expression écrite et orale</li>
                                            <li>Approfondir la connaissance des œuvres littéraires</li>
                                            <li>Développer l'esprit critique et analytique</li>
                                        </ul>
                                        
                                        <h4>Programme prévisionnel :</h4>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Semaine</th>
                                                        <th>Thème</th>
                                                        <th>Contenu</th>
                                                        <th>Modalités</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1-3</td>
                                                        <td>Roman du XXe siècle</td>
                                                        <td>"À la recherche du temps perdu" - Proust (extraits)</td>
                                                        <td>Cours magistral, analyse, exposé</td>
                                                    </tr>
                                                    <tr>
                                                        <td>4-6</td>
                                                        <td>Poésie contemporaine</td>
                                                        <td>Paul Éluard, Louis Aragon</td>
                                                        <td>Cours magistral, TD, Création poétique</td>
                                                    </tr>
                                                    <tr>
                                                        <td>7-8</td>
                                                        <td>Théâtre classique</td>
                                                        <td>"Le Cid" - Corneille</td>
                                                        <td>Cours magistral, Lecture, Jeu théâtral</td>
                                                    </tr>
                                                    <tr>
                                                        <td>9-10</td>
                                                        <td>Expression écrite</td>
                                                        <td>Techniques de dissertation et d'expression</td>
                                                        <td>TD, Atelier d'écriture, Contrôle</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <h4>Ressources pédagogiques :</h4>
                                        <ul>
                                            <li>Anthologie de textes fournie en début de cours</li>
                                            <li>"Histoire de la littérature française" - P. Brunel (PUF)</li>
                                            <li>Revues littéraires disponibles au CDI</li>
                                        </ul>
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