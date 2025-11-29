<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'secretaire') {
    header("Location: ../login.php");
    exit();
}

include '../config/connexion.php';

// Initialize counts
$teachersCount = 0;
$coursesCount = 0;
$prestationsCount = 0;
$finishedCoursesCount = 0;

// Check if $pdo is properly initialized
if ($pdo) {
    try {
        // Compter le nombre d'enseignants
        $teachersCountQuery = "SELECT COUNT(*) as total FROM utilisateur WHERE role = 'Enseignant'";
        $teachersStmt = $pdo->prepare($teachersCountQuery);
        $teachersStmt->execute();
        $teachersCount = $teachersStmt->fetch()['total'];

        // Compter le nombre de cours programmés
        $coursesCountQuery = "SELECT COUNT(*) as total FROM cours";
        $coursesStmt = $pdo->prepare($coursesCountQuery);
        $coursesStmt->execute();
        $coursesCount = $coursesStmt->fetch()['total'];

        // Compter le nombre de fiches de prestation
        $prestationsCountQuery = "SELECT COUNT(*) as total FROM entetefiche";
        $prestationsStmt = $pdo->prepare($prestationsCountQuery);
        $prestationsStmt->execute();
        $prestationsCount = $prestationsStmt->fetch()['total'];

        // Compter le nombre de cours finis
        $finishedCoursesCountQuery = "SELECT COUNT(*) as total FROM cours WHERE statut = 'fini'";
        $finishedCoursesStmt = $pdo->prepare($finishedCoursesCountQuery);
        $finishedCoursesStmt->execute();
        $finishedCoursesCount = $finishedCoursesStmt->fetch()['total'];
    } catch (Exception $e) {
        // Log error but continue execution
        error_log("Database error in secretairesection/index.php: " . $e->getMessage());
    }
}
?>

<?php 
    // Déterminer la page active
    $current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Secrétaire de Section</title>
    <link rel="stylesheet" href="secretaire_section_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="secretary-header">
        <h1><i class="fas fa-user-tie"></i> Interface Secrétaire de Section</h1>
        <div class="header-actions">
            <button onclick="location.href='../index.php'"><i class="fas fa-home"></i> <span>Accueil</span></button>
            <button onclick="location.href='../script/logout.php'"><i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span></button>
        </div>
    </header>

    <!-- Container -->
    <div class="secretary-container">
        <!-- Sidebar-->
        <aside class="secretary-sidebar">
            <div class="secretary-sidebar-header">
                <img src="../image/logo.jpg" alt="Logo">
                <h2>Secrétaire de Section</h2>
                <p><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Utilisateur'; ?></p>
            </div>
            <nav class="secretary-nav-menu">
                <ul>
                    <li><a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>"><i class="fas fa-home"></i> <span>Tableau de bord</span></a></li>
                    <li><a href="prestation.php" class="<?php echo ($current_page == 'prestation.php') ? 'active' : ''; ?>"><i class="fas fa-file-invoice"></i> <span>Fiches de Prestation</span></a></li>
                    <li><a href="coursfini.php" class="<?php echo ($current_page == 'coursfini.php') ? 'active' : ''; ?>"><i class="fas fa-clipboard-check"></i> <span>Cours Finis</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="secretary-main">
            <div class="content-header">
                <h2>Tableau de bord</h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Accueil</a></li>
                    <li>Tableau de bord</li>
                </ul>
            </div>

            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-title">Enseignants</div>
                    <div class="card-value"><?php echo $teachersCount; ?></div>
                    <div class="card-footer">Enseignants enregistrés</div>
                </div>

                <div class="card horaires">
                    <div class="card-title">Cours Programmés</div>
                    <div class="card-value"><?php echo $coursesCount; ?></div>
                    <div class="card-footer">Cours définis</div>
                </div>

                <div class="card prestations">
                    <div class="card-title">Fiches de Prestation</div>
                    <div class="card-value"><?php echo $prestationsCount; ?></div>
                    <div class="card-footer">Fiches créées</div>
                </div>

                <div class="card charges">
                    <div class="card-title">Cours Finis</div>
                    <div class="card-value"><?php echo $finishedCoursesCount; ?></div>
                    <div class="card-footer">Cours terminés</div>
                </div>
            </div>

            <div class="secretary-section">
                <h3 class="section-title">Responsabilités du Secrétaire de Section</h3>
                <p>En tant que secrétaire de section, vous êtes responsable de :</p>
                <ul style="margin-left: 20px;margin-top: 15px;">
                    <li>Consulter les fiches de prestation quotidiennement</li>
                    <li>Compléter les fiches des matières finies</li>
                    <li>Mettre à jour les statuts des cours</li>
                    <li>Assister le chef de section dans ses tâches administratives</li>
                </ul>
            </div>

            <div class="secretary-section">
                <h3 class="section-title">Accès Rapide</h3>
                <div class="secretary-actions">
                    <a href="prestation.php" class="btn btn-primary"><i class="fas fa-file-invoice"></i> Consulter les Fiches de Prestation</a>
                    <a href="coursfini.php" class="btn btn-success"><i class="fas fa-clipboard-check"></i> Gérer les Cours Finis</a>
                </div>
            </div>
        </main>
    </div>

    <footer class="secretary-footer">
        <p>&copy; 2025 Système de Suivi de Prestation. Tous droits réservés.</p>
    </footer>
</body>
</html>