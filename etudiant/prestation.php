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

// Récupérer les cours de l'étudiant avec leur progression
$stmt = $pdo->prepare("SELECT DISTINCT c.nomComplet as cours_nom, e.nom as enseignant_nom, e.postnom as enseignant_postnom,
                       ef.volume_horaire_prevu, ef.heures_reelles_prestees, ef.statut
                       FROM participeraucours pc
                       JOIN cours c ON pc.code_cours = c.code_cours
                       JOIN enseignant e ON pc.matriculeEnseignant = e.matriculeEnseignant
                       JOIN entetefiche ef ON pc.code_cours = ef.code_cours
                       WHERE pc.matriculeEtudiant = ?");
$stmt->execute([$matricule]);
$cours_progression = $stmt->fetchAll();

// Récupérer les dernières fiches de prestation validées
$stmt = $pdo->prepare("SELECT cf.*, c.nomComplet as cours_nom, e.nom as enseignant_nom, e.postnom as enseignant_postnom
                       FROM contenufiche cf
                       JOIN entetefiche ef ON cf.identetefiche = ef.id
                       JOIN cours c ON ef.code_cours = c.code_cours
                       JOIN enseignant e ON ef.matricule_enseignant = e.matriculeEnseignant
                       WHERE ef.code_cours IN (
                           SELECT code_cours FROM participeraucours WHERE matriculeEtudiant = ?
                       )
                       AND ef.statut = 'approved'
                       ORDER BY cf.datejoure DESC
                       LIMIT 5");
$stmt->execute([$matricule]);
$fiches_prestation = $stmt->fetchAll();
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
    <!-- En-tête -->
    <header>
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Avancement des Cours - Espace Étudiant</h1>
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
        <main>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>État d'Avancement des Cours</h2>
                        </div>
                        <div class="card-body">
                            <p class="mb-4">Consultez ci-dessous l'avancement de vos cours basé sur les fiches de prestations validées :</p>
                            
                            <!-- Liste des cours avec leur avancement -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Cours</th>
                                            <th>Enseignant</th>
                                            <th>Progression</th>
                                            <th>Statut</th>
                                            <th>Heures prestées / Prévue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($cours_progression) > 0): ?>
                                            <?php foreach ($cours_progression as $cours): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($cours['cours_nom']); ?></td>
                                                <td><?php echo htmlspecialchars($cours['enseignant_nom'] . " " . $cours['enseignant_postnom']); ?></td>
                                                <td>
                                                    <?php 
                                                    $pourcentage = 0;
                                                    if ($cours['volume_horaire_prevu'] > 0) {
                                                        $pourcentage = ($cours['heures_reelles_prestees'] / $cours['volume_horaire_prevu']) * 100;
                                                    }
                                                    ?>
                                                    <div class="progress">
                                                        <div class="progress-bar <?php 
                                                        if ($pourcentage >= 75) echo 'bg-success';
                                                        elseif ($pourcentage >= 50) echo 'bg-warning';
                                                        else echo 'bg-danger';
                                                        ?>" role="progressbar" style="width: <?php echo $pourcentage; ?>%" aria-valuenow="<?php echo $pourcentage; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo round($pourcentage); ?>%</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if ($cours['statut'] == 'approved'): ?>
                                                        <span class="badge bg-success">Validé</span>
                                                    <?php elseif ($cours['statut'] == 'pending'): ?>
                                                        <span class="badge bg-warning">En attente</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-info"><?php echo htmlspecialchars($cours['statut']); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $cours['heures_reelles_prestees'] . " / " . $cours['volume_horaire_prevu']; ?> heures</td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Aucun cours trouvé pour votre promotion</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <?php if (count($cours_progression) > 0): ?>
                            <div class="alert alert-info">
                                <strong>Information :</strong> Ces informations sont mises à jour automatiquement 
                                lorsque les fiches de prestations sont validées par les responsables.
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Détails des fiches de prestation récentes -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Dernières Fiches de Prestation Validées</h3>
                        </div>
                        <div class="card-body">
                            <?php if (count($fiches_prestation) > 0): ?>
                            <div class="accordion" id="prestationAccordion">
                                <?php foreach ($fiches_prestation as $index => $fiche): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                                        <button class="accordion-button <?php echo $index > 0 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>">
                                            <?php echo htmlspecialchars($fiche['cours_nom']); ?> - <?php echo date('d F Y', strtotime($fiche['datejoure'])); ?>
                                        </button>
                                    </h2>
                                    <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse <?php echo $index == 0 ? 'show' : ''; ?>" data-bs-parent="#prestationAccordion">
                                        <div class="accordion-body">
                                            <p><strong>Contenu couvert :</strong> <?php echo htmlspecialchars($fiche['contenu']); ?></p>
                                            <p><strong>Heures prestées :</strong> <?php echo $fiche['nbreH']; ?> heures</p>
                                            <p><strong>Enseignant :</strong> <?php echo htmlspecialchars($fiche['enseignant_nom'] . " " . $fiche['enseignant_postnom']); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <div class="alert alert-info">
                                Aucune fiche de prestation validée pour le moment.
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