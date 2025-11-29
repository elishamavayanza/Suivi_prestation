<?php
session_start();
if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'Academique' && $_SESSION['role'] != 'SGA')) {
    header("Location: ../login.php");
    exit();
}

include '../config/connexion.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Académique</title>
    <link rel="stylesheet" href="academic_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="academic-header">
        <h1><i class="fas fa-graduation-cap"></i> Service Académique - Tableau de bord</h1>
        <div class="header-actions">
            <div class="user-info">
                <div class="user-avatar"><?php echo substr($_SESSION['username'], 0, 1); ?></div>
                <span><?php echo $_SESSION['username']; ?> (SGA)</span>
            </div>
            <a href="../index.php"><i class="fas fa-home"></i> <span>Accueil</span></a>
            <a href="../script/logout.php"><i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span></a>
        </div>
    </header>

    <!-- Container -->
    <div class="academic-container">
        <!-- Sidebar -->
        <aside class="academic-sidebar">
            <div class="academic-sidebar-header">
                <img src="../image/logo.jpg" alt="Logo">
                <h2>Service Académique</h2>
                <p><?php echo $_SESSION['username']; ?></p>
            </div>
            <nav class="academic-nav-menu">
                <ul>
                    <li><a href="index.php" class="active"><i class="fas fa-home"></i> <span>Tableau de bord</span></a></li>
                    <li><a href="horaire.php"><i class="fas fa-clock"></i> <span>Gestion des Horaires</span></a></li>
                    <li><a href="chargehoraire.php"><i class="fas fa-hourglass-half"></i> <span>Charges Horaire</span></a></li>
                    <li><a href="academique_prestation.php"><i class="fas fa-file-invoice"></i> <span>Fiches de Prestation</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="academic-main">
          <div class="content-header">
                <h2><i class="fas fa-tachometer-alt"></i> Tableau de bord Service Académique</h2>
                <ul class="breadcrumb">
                    <li><a href="../index.php">Accueil</a></li>
                    <li>Tableau de bord</li>
                </ul>
            </div>

            <div class="academic-alert academic-alert-info">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>Bienvenue sur le tableau de bord du Service Académique:</strong> 
                    Vous pouvez gérer les horaires, les charges horaires et valider les fiches de prestations des enseignants.
                </div>
            </div>

            <!-- Statistiques -->
            <div class="academic-section">
                <h3 class="section-title"><i class="fas fa-chart-bar"></i> Statistiques rapides</h3>
                <div class="academic-actions">
                    <div class="action-card">
                        <i class="fas fa-file-invoice"></i>
                        <h3>Fiches à valider</h3>
                        <p class="badge badge-warning">8 fiches</p>
                    </div>
                    
                    <div class="action-card">
                        <i class="fas fa-check-circle"></i>
                        <h3>Fiches validées</h3>
                        <p class="badge badge-success">24 fiches</p>
                    </div>
                    
                    <div class="action-card">
                        <i class="fas fa-paper-plane"></i>
                        <h3>Fiches envoyées</h3>
                        <p class="badge badge-info">15 fiches</p>
                    </div>
                    
                    <div class="action-card">
                        <i class="fas fa-users"></i>
                        <h3>Enseignants actifs</h3>
                        <p class="badge badge-primary">32 enseignants</p>
                    </div>
                </div>
            </div>

            <!-- Actions principales -->
            <div class="academic-section">
                <h3 class="section-title"><i class="fas fa-tasks"></i> Actions principales</h3>
                <div class="academic-actions">
                    <div class="action-card">
                        <i class="fas fa-clock"></i>
                        <h3>Gestion des Horaires</h3>
                        <p>Consulter et gérer les emplois du temps</p>
                        <a href="horaire.php" class="btn btn-primary mt-20">Accéder</a>
                    </div>
                    
                    <div class="action-card">
                        <i class="fas fa-hourglass-half"></i>
<h3>Charges Horaire</h3>
                        <p>Suivre les charges horaires des enseignants</p>
                        <a href="chargehoraire.php" class="btn btn-primary mt-20">Accéder</a>
                    </div>
                    
                    <div class="action-card">
                        <i class="fas fa-file-invoice"></i>
                        <h3>Fiches de Prestation</h3>
                        <p>Valider les fiches de prestations des enseignants</p>
                        <a href="academique_prestation.php" class="btn btn-primary mt-20">Accéder</a>
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <div class="academic-section">
                <h3 class="section-title"><i class="fas fa-bell"></i> Notifications récentes</h3>
                <div class="academic-alert academic-alert-warning">
                  <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Rappel:</strong> 3 fiches de prestations sont en attente de validation depuis plus de 3 jours.
                    </div>
                </div>
                
                <div class="academic-alert academic-alert-info">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Information:</strong> Nouvelle mise à jour du système disponible. Consultez les notes de version.
                    </div>
                </div>
            </div>
        </main>
    </div>

    <footer class="academic-footer">
        <p>&copy; 2025 Système de Suivi de Prestation. Tous droits réservés.</p>
    </footer>
</body>
</html>