<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Chefdesection') {
    header("Location: ../login.php");
    exit();
}

include '../config/connexion.php';

// Récupérer les informations du chef de section
$userId = $_SESSION['username'];

// Compter le nombre d'enseignants sous la responsabilité du chef de section
$teachersCountQuery = "SELECT COUNT(*) as total FROM utilisateur WHERE role = 'Enseignant'";
$teachersStmt = $pdo->prepare($teachersCountQuery);
$teachersStmt->execute();
$teachersCount =$teachersStmt->fetch()['total'];

// Compter le nombre de cours programmés
$coursesCountQuery = "SELECT COUNT(*) as total FROM cours";
$coursesStmt = $pdo->prepare($coursesCountQuery);
$coursesStmt->execute();
$coursesCount = $coursesStmt->fetch()['total'];

// Compter le nombre de prestations
$prestationsCountQuery = "SELECT COUNT(*) as total FROM entetefiche";
$prestationsStmt = $pdo->prepare($prestationsCountQuery);
$prestationsStmt->execute();
$prestationsCount = $prestationsStmt->fetch()['total'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Chef de Section</title>
    <link rel="stylesheet" href="chef_section_cp_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="section-chief-header">
        <h1><i class="fas fa-user-tie"></i> Interface Chef de Section</h1>
        <div class="header-actions">
            <a href="../index.php"><i class="fas fa-home"></i> <span>Accueil</span></a>
            <a href="../script/logout.php"><i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span></a>
        </div>
    </header>

    <!-- Container -->
    <div class="section-chief-container">
        <!-- Sidebar -->
        <aside class="section-chief-sidebar">
            <div class="section-chief-sidebar-header">
                <img src="../image/logo.jpg" alt="Logo">
                <h2>Chef de Section</h2>
                <p><?php echo $_SESSION['username']; ?></p>
            </div>
            <nav class="section-chief-nav-menu">
                <ul>
                    <li><a href="index.php" class="active"><i class="fas fa-home"></i> <span>Tableau de bord</span></a></li>
                    <li><a href="horaire.php"><i class="fas fa-clock"></i> <span>Gestion des Horaires</span></a></li>
                    <li><a href="chargehoraire.php"><i class="fas fa-hourglass-half"></i> <span>Charges Horaire</span></a></li>
                    <li><a href="prestation.php"><iclass="fas fa-file-invoice"></i> <span>Fiches de Prestation</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="section-chief-main">
            <div class="content-header">
                <h2>Tableau de bord</h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Accueil</a></li>
                    <li>Tableau de bord</li>
                </ul>
            </div>

            <div class="stats-summary">
                <div class="stat-card">
                    <div class="stat-value"><?php echo $teachersCount; ?></div>
                    <div class="stat-label">Enseignants</div>
                </div>

                <div class="stat-card horaires">
                    <div class="stat-value"><?php echo $coursesCount; ?></div>
                   <div class="stat-label">Cours Programmés</div>
                </div>

                <div class="stat-card prestations">
<div class="stat-value"><?php echo $prestationsCount; ?></div>
                    <div class="stat-label">Fiches de Prestation</div>
                </div>
</div>

            <div class="section-chief-section">
                <h3 class="section-title">Responsabilités duChef de Section</h3>
                <p>En tant que chef de section, vous êtes responsable de :</p>
                <ul>
                    <li>Élaborer la chargehoraire des enseignants</li>
                    <li>Programmer les cours (établir les horaires)</li>
<li>Consulter les fiches de prestation quotidiennement</li>
                    <li>Valider les fiches de prestation après finalisation des cours</li>
               </ul>
            </div>

            <div class="section-chief-section">
                <h3 class="section-title">Accès Rapide</h3>
                <div class="section-chief-actions">
                    <a href="horaire.php" class="btn btn-primary"><i class="fas fa-clock"></i> Gérer les Horaires</a>
                    <a href="chargehoraire.php" class="btn btn-success"><i class="fas fa-hourglass-half"></i> Charges Horaire</a>
                    <a href="prestation.php" class="btn btn-warning"><i class="fas fa-file-invoice"></i> Fiches de Prestation</a>
                </div>
            </div>
        </main>
   </div>

    <footer class="section-chief-footer">
        <p>&copy; 2025 Système de Suivi de Prestation. Tous droits réservés.</p>
    </footer>
</body>
</html>