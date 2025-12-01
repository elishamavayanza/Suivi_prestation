<?php
session_start();
include("../script/config.php");
include("db_connect.php");

// Vérifier si l'utilisateur est connecté et s'il est étudiant
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Etudiant') {
    header("Location: ../login.php");
    exit();
}

// Récupérer les informations de l'étudiant
$matricule = $_SESSION['matricule'];

// Récupérer les descriptions de cours pour les cours de l'étudiant
$stmt = $pdo->prepare("SELECT df.*, c.nomComplet as cours_nom, e.nom as enseignant_nom, e.postnom as enseignant_postnom
                       FROM `descriptionfiche` df
                       JOIN cours c ON df.code_cours = c.id
                       JOIN enseignant e ON df.matricule_enseignant = e.matriculeEnseignant
                       WHERE df.code_promotion = (
                           SELECT codepromotion FROM inscription WHERE matriculeEtudiant = ?
                       )");
$stmt->execute([$matricule]);
$descriptions = $stmt->fetchAll();

// Organiser les descriptions par cours
$descriptionsParCours = [];
foreach ($descriptions as $desc) {
    $coursNom = $desc['cours_nom'];
    if (!isset($descriptionsParCours[$coursNom])) {
        $descriptionsParCours[$coursNom] = $desc;
    }
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
    <!-- En-tête -->
    <header>
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Plan du Cours - Espace Étudiant</h1>
                <div>
                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span> | 
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
        <main>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>Plans de Cours Remplis par les Enseignants</h2>
                        </div>
                        <div class="card-body">
                            <p class="mb-4">Consultez ci-dessous les plans de cours remplis par vos enseignants :</p>
                            
                            <?php if (count($descriptions) > 0): ?>
                            <!-- Onglets pour chaque matière -->
                            <ul class="nav nav-tabs" id="courseTabs" role="tablist">
                                <?php $first = true; ?>
                                <?php foreach ($descriptionsParCours as $coursNom => $desc): ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link <?php echo $first ? 'active' : ''; ?>" id="<?php echo strtolower(str_replace(' ', '-', $coursNom)); ?>-tab" data-bs-toggle="tab" data-bs-target="#<?php echo strtolower(str_replace(' ', '-', $coursNom)); ?>" type="button" role="tab">
                                        <?php echo htmlspecialchars($coursNom); ?>
                                    </button>
                                </li>
                                <?php $first = false; ?>
                                <?php endforeach; ?>
                            </ul>
                            
                            <div class="tab-content" id="courseTabsContent">
                                <?php $first = true; ?>
                                <?php foreach ($descriptionsParCours as $coursNom => $desc): ?>
                                <!-- Contenu <?php echo htmlspecialchars($coursNom); ?> -->
                                <div class="tab-pane fade <?php echo $first ? 'show active' : ''; ?>" id="<?php echo strtolower(str_replace(' ', '-', $coursNom)); ?>" role="tabpanel">
                                    <div class="p-3">
                                        <h3><?php echo htmlspecialchars($coursNom); ?> - <?php echo htmlspecialchars($desc['enseignant_nom'] . " " . $desc['enseignant_postnom']); ?></h3>
                                        <div class="course-details">
                                            <?php if (!empty($desc['objectif'])): ?>
                                            <h4>Objectifs du cours :</h4>
                                            <p><?php echo htmlspecialchars($desc['objectif']); ?></p>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($desc['contenu'])): ?>
                                            <h4>Contenu du cours :</h4>
                                            <p><?php echo nl2br(htmlspecialchars($desc['contenu'])); ?></p>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($desc['methode'])): ?>
                                            <h4>Méthodes d'enseignement :</h4>
                                            <p><?php echo htmlspecialchars($desc['methode']); ?></p>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($desc['ressource'])): ?>
                                            <h4>Ressources pédagogiques :</h4>
                                            <p><?php echo nl2br(htmlspecialchars($desc['ressource'])); ?></p>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($desc['evaluation'])): ?>
                                            <h4>Modalités d'évaluation :</h4>
                                            <p><?php echo htmlspecialchars($desc['evaluation']); ?></p>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($desc['bibliographie'])): ?>
                                            <h4>Bibliographie :</h4>
                                            <p><?php echo nl2br(htmlspecialchars($desc['bibliographie'])); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php $first = false; ?>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <div class="alert alert-info">
                                Aucun plan de cours n'a encore été rempli par vos enseignants.
                            </div>
                            <?php endif; ?>
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