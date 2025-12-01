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

// Récupérer toutes les annonces
$stmt = $pdo->prepare("SELECT cf.*, c.nomComplet as cours_nom, e.nom as enseignant_nom, e.postnom as enseignant_postnom
                       FROM contenufiche cf
                       JOIN entetefiche ef ON cf.identetefiche = ef.id
                       JOIN cours c ON ef.code_cours = c.code_cours
                       JOIN enseignant e ON ef.matricule_enseignant = e.matriculeEnseignant
                       WHERE ef.code_promotion = (
                           SELECT codepromotion FROM inscription WHERE matriculeEtudiant = ?
                       )
                       ORDER BY cf.datejoure DESC");
$stmt->execute([$matricule]);
$annonces = $stmt->fetchAll();
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
                                <?php if (count($annonces) > 0): ?>
                                    <?php foreach ($annonces as $annonce): ?>
                                    <div class="announcement-item card mb-3">
                                        <div class="card-header <?php 
                                            $heure = date('H', strtotime($annonce['heureEntree']));
                                            if ($heure < 10) echo 'bg-primary text-white';
                                            elseif ($heure < 14) echo 'bg-success text-white';
                                            elseif ($heure < 18) echo 'bg-warning text-dark';
                                            else echo 'bg-info text-white';
                                        ?>">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="mb-0"><?php echo htmlspecialchars($annonce['cours_nom']); ?></h5>
                                                <span><?php echo date('d F Y', strtotime($annonce['datejoure'])); ?></span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p><?php echo htmlspecialchars($annonce['contenu']); ?></p>
                                            <p><strong>Heures:</strong> <?php echo substr($annonce['heureEntree'], 0, 5); ?> - <?php echo substr($annonce['heureSortie'], 0, 5); ?> (<?php echo $annonce['nbreH']; ?> heures)</p>
                                            <span class="badge bg-secondary">Posté par: <?php echo htmlspecialchars($annonce['enseignant_nom'] . " " . $annonce['enseignant_postnom']); ?></span>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="alert alert-info">
                                        Aucune annonce pour le moment.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (count($annonces) > 0): ?>
                    <div class="alert alert-info">
                        <strong>Information :</strong> Vous recevrez une notification par email pour chaque nouvelle annonce importante.
                    </div>
                    <?php endif; ?>
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