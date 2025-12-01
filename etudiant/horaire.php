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

// Récupérer l'horaire de l'étudiant
$stmt = $pdo->prepare("SELECT h.*, c.nomComplet as cours_nom, e.nom as enseignant_nom, e.postnom as enseignant_postnom 
                      FROM horaire h
                      JOIN cours c ON h.idcours = c.code_cours
                      JOIN enseignant e ON h.enseignant = e.matriculeEnseignant
                      WHERE h.codepromotion = (
                          SELECT codepromotion FROM inscription WHERE matriculeEtudiant = ?
                      )");
$stmt->execute([$matricule]);
$horaires = $stmt->fetchAll();

// Organiser les horaires par jour
$horaireParJour = [
    'Lundi' => [],
    'Mardi' => [],
    'Mercredi' => [],
    'Jeudi' => [],
    'Vendredi' => [],
    'Samedi' => []
];

// Fonction pour obtenir le jour à partir de la chaîne jourheure
foreach ($horaires as $horaire) {
    // Extraire le jour de la chaîne jourheure (exemple: "Lundi - Samedi")
    $jourheure = $horaire['jourheure'];
    if (strpos($jourheure, 'Lundi') !== false) {
        $horaireParJour['Lundi'][] = $horaire;
    }
    if (strpos($jourheure, 'Mardi') !== false) {
        $horaireParJour['Mardi'][] = $horaire;
    }
    if (strpos($jourheure, 'Mercredi') !== false) {
        $horaireParJour['Mercredi'][] = $horaire;
    }
    if (strpos($jourheure, 'Jeudi') !== false) {
        $horaireParJour['Jeudi'][] = $horaire;
    }
    if (strpos($jourheure, 'Vendredi') !== false) {
        $horaireParJour['Vendredi'][] = $horaire;
    }
    if (strpos($jourheure, 'Samedi') !== false) {
        $horaireParJour['Samedi'][] = $horaire;
    }
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
                                            <td>
                                                <?php 
                                                foreach ($horaireParJour['Lundi'] as $horaire) {
                                                    if (strpos($horaire['jourheure'], '08:00') !== false) {
                                                        echo htmlspecialchars($horaire['cours_nom']) . "<br>";
                                                        echo "<small class='text-muted'>" . htmlspecialchars($horaire['enseignant_nom'] . " " . $horaire['enseignant_postnom']) . "</small>";
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                foreach ($horaireParJour['Mardi'] as $horaire) {
                                                    if (strpos($horaire['jourheure'], '08:00') !== false) {
                                                        echo htmlspecialchars($horaire['cours_nom']) . "<br>";
                                                        echo "<small class='text-muted'>" . htmlspecialchars($horaire['enseignant_nom'] . " " . $horaire['enseignant_postnom']) . "</small>";
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                foreach ($horaireParJour['Mercredi'] as $horaire) {
                                                    if (strpos($horaire['jourheure'], '08:00') !== false) {
                                                        echo htmlspecialchars($horaire['cours_nom']) . "<br>";
                                                        echo "<small class='text-muted'>" . htmlspecialchars($horaire['enseignant_nom'] . " " . $horaire['enseignant_postnom']) . "</small>";
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                foreach ($horaireParJour['Jeudi'] as $horaire) {
                                                    if (strpos($horaire['jourheure'], '08:00') !== false) {
                                                        echo htmlspecialchars($horaire['cours_nom']) . "<br>";
                                                        echo "<small class='text-muted'>" . htmlspecialchars($horaire['enseignant_nom'] . " " . $horaire['enseignant_postnom']) . "</small>";
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                foreach ($horaireParJour['Vendredi'] as $horaire) {
                                                    if (strpos($horaire['jourheure'], '08:00') !== false) {
                                                        echo htmlspecialchars($horaire['cours_nom']) . "<br>";
                                                        echo "<small class='text-muted'>" . htmlspecialchars($horaire['enseignant_nom'] . " " . $horaire['enseignant_postnom']) . "</small>";
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                foreach ($horaireParJour['Samedi'] as $horaire) {
                                                    if (strpos($horaire['jourheure'], '08:00') !== false) {
                                                        echo htmlspecialchars($horaire['cours_nom']) . "<br>";
                                                        echo "<small class='text-muted'>" . htmlspecialchars($horaire['enseignant_nom'] . " " . $horaire['enseignant_postnom']) . "</small>";
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>09:45 - 11:15</td>
                                            <td>
                                                <?php 
                                                foreach ($horaireParJour['Lundi'] as $horaire) {
                                                    if (strpos($horaire['jourheure'], '09:45') !== false) {
                                                        echo htmlspecialchars($horaire['cours_nom']) . "<br>";
                                                        echo "<small class='text-muted'>" . htmlspecialchars($horaire['enseignant_nom'] . " " . $horaire['enseignant_postnom']) . "</small>";
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                foreach ($horaireParJour['Mardi'] as $horaire) {
                                                    if (strpos($horaire['jourheure'], '09:45') !== false) {
                                                        echo htmlspecialchars($horaire['cours_nom']) . "<br>";
                                                        echo "<small class='text-muted'>" . htmlspecialchars($horaire['enseignant_nom'] . " " . $horaire['enseignant_postnom']) . "</small>";
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                foreach ($horaireParJour['Mercredi'] as $horaire) {
                                                    if (strpos($horaire['jourheure'], '09:45') !== false) {
                                                        echo htmlspecialchars($horaire['cours_nom']) . "<br>";
                                                        echo "<small class='text-muted'>" . htmlspecialchars($horaire['enseignant_nom'] . " " . $horaire['enseignant_postnom']) . "</small>";
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                foreach ($horaireParJour['Jeudi'] as $horaire) {
                                                    if (strpos($horaire['jourheure'], '09:45') !== false) {
                                                        echo htmlspecialchars($horaire['cours_nom']) . "<br>";
                                                        echo "<small class='text-muted'>" . htmlspecialchars($horaire['enseignant_nom'] . " " . $horaire['enseignant_postnom']) . "</small>";
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                foreach ($horaireParJour['Vendredi'] as $horaire) {
                                                    if (strpos($horaire['jourheure'], '09:45') !== false) {
                                                        echo htmlspecialchars($horaire['cours_nom']) . "<br>";
                                                        echo "<small class='text-muted'>" . htmlspecialchars($horaire['enseignant_nom'] . " " . $horaire['enseignant_postnom']) . "</small>";
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                foreach ($horaireParJour['Samedi'] as $horaire) {
                                                    if (strpos($horaire['jourheure'], '09:45') !== false) {
                                                        echo htmlspecialchars($horaire['cours_nom']) . "<br>";
                                                        echo "<small class='text-muted'>" . htmlspecialchars($horaire['enseignant_nom'] . " " . $horaire['enseignant_postnom']) . "</small>";
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>11:30 - 13:00</td>
                                            <td>
                                                <?php 
                                                foreach ($horaireParJour['Lundi'] as $horaire) {
                                                    if (strpos($horaire['jourheure'], '11:30') !== false) {
                                                        echo htmlspecialchars($horaire['cours_nom']) . "<br>";
                                                        echo "<small class='text-muted'>" . htmlspecialchars($horaire['enseignant_nom'] . " " . $horaire['enseignant_postnom']) . "</small>";
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                foreach ($horaireParJour['Mardi'] as $horaire) {
                                                    if (strpos($horaire['jourheure'], '11:30') !== false) {
                                                        echo htmlspecialchars($horaire['cours_nom']) . "<br>";
                                                        echo "<small class='text-muted'>" . htmlspecialchars($horaire['enseignant_nom'] . " " . $horaire['enseignant_postnom']) . "</small>";
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                foreach ($horaireParJour['Mercredi'] as $horaire) {
                                                    if (strpos($horaire['jourheure'], '11:30') !== false) {
                                                        echo htmlspecialchars($horaire['cours_nom']) . "<br>";
                                                        echo "<small class='text-muted'>" . htmlspecialchars($horaire['enseignant_nom'] . " " . $horaire['enseignant_postnom']) . "</small>";
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                foreach ($horaireParJour['Jeudi'] as $horaire) {
                                                    if (strpos($horaire['jourheure'], '11:30') !== false) {
                                                        echo htmlspecialchars($horaire['cours_nom']) . "<br>";
                                                        echo "<small class='text-muted'>" . htmlspecialchars($horaire['enseignant_nom'] . " " . $horaire['enseignant_postnom']) . "</small>";
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                foreach ($horaireParJour['Vendredi'] as $horaire) {
                                                    if (strpos($horaire['jourheure'], '11:30') !== false) {
                                                        echo htmlspecialchars($horaire['cours_nom']) . "<br>";
                                                        echo "<small class='text-muted'>" . htmlspecialchars($horaire['enseignant_nom'] . " " . $horaire['enseignant_postnom']) . "</small>";
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                foreach ($horaireParJour['Samedi'] as $horaire) {
                                                    if (strpos($horaire['jourheure'], '11:30') !== false) {
                                                        echo htmlspecialchars($horaire['cours_nom']) . "<br>";
                                                        echo "<small class='text-muted'>" . htmlspecialchars($horaire['enseignant_nom'] . " " . $horaire['enseignant_postnom']) . "</small>";
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <?php if (count($horaires) == 0): ?>
                            <div class="alert alert-info">
                                <strong>Information :</strong> Aucun horaire n'a encore été défini pour votre promotion.
                            </div>
                            <?php else: ?>
                            <div class="alert alert-info">
                                <strong>Note :</strong> Les horaires peuvent être mis à jour périodiquement. 
                                Consultez régulièrement cette page pour les dernières modifications.
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