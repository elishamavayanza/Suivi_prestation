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
$stmt = $pdo->prepare("SELECT * FROM etudiant WHERE matriculeEtudiant = ?");
$stmt->execute([$matricule]);
$etudiant = $stmt->fetch();

// Récupérer les 5 dernières annonces
$stmt = $pdo->prepare("SELECT cf.*, c.nomComplet as cours_nom FROM contenufiche cf 
                      JOIN entetefiche ef ON cf.identetefiche = ef.id 
                      JOIN cours c ON ef.code_cours = c.code_cours
                      WHERE ef.code_promotion = (
                          SELECT codepromotion FROM inscription WHERE matriculeEtudiant = ?
                      )
                      ORDER BY cf.datejoure DESC LIMIT 5");
$stmt->execute([$matricule]);
$annonces = $stmt->fetchAll();
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
    <!-- En-tête -->
    <header>
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Espace Étudiant - Institut Supérieur Pédagogique MUHANGI</h1>
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
        <main>
            <div class="row">
                <div class="col-md-12">
                    <div class="card welcome-card">
                        <div class="card-header">
                            <h2>Bienvenue, <?php echo htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']); ?>!</h2>
                        </div>
                        <div class="card-body">
                            <p>Matricule: <?php echo htmlspecialchars($etudiant['matriculeEtudiant']); ?></p>
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
                        <?php if (count($annonces) > 0): ?>
                            <?php foreach ($annonces as $annonce): ?>
                            <div class="announcement-card card">
                                <div class="card-header bg-warning text-dark">
                                    Annonce - <?php echo date('d F Y', strtotime($annonce['datejoure'])); ?>
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><strong><?php echo htmlspecialchars($annonce['cours_nom']); ?>:</strong> <?php echo htmlspecialchars(substr($annonce['contenu'], 0, 100)) . '...'; ?></p>
                                    <a href="annonces.php" class="btn btn-primary btn-sm">Voir détails</a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-info">
                                Aucune annonce récente pour le moment.
                            </div>
                        <?php endif; ?>
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