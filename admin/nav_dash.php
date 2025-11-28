<?php 
    session_start();
    include("../script/config.php");
    
    // Déterminer la page active
    $current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Administrateur</title>
    <link rel="stylesheet" href="admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="admin-header">
        <h1><i class="fas fa-tachometer-alt"></i> Tableau de bord administrateur</h1>
        <div class="header-actions">
            <button onclick="location.href='../index.php'"><i class="fas fa-home"></i> <span>Accueil</span></button>
            <button onclick="location.href='../script/logout.php'"><i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span></button>
        </div>
    </header>

    <!-- Container -->
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../image/logo.jpg" alt="Logo">
                <h2>Administration</h2>
                <p>Gestion du système</p>
            </div>
            <nav class="nav-menu">
                <ul>
                    <li><a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>"><i class="fas fa-home"></i> <span>Tableau de bord</span></a></li>
                    <li><a href="annee.php" class="<?php echo ($current_page == 'annee.php') ? 'active' : ''; ?>"><i class="fas fa-calendar-alt"></i> <span>Années académiques</span></a></li>
                    <li><a href="etudiant.php" class="<?php echo ($current_page == 'etudiant.php') ? 'active' : ''; ?>"><i class="fas fa-user-graduate"></i> <span>Étudiants</span></a></li>
                    <li><a href="enseignant.php" class="<?php echo ($current_page == 'enseignant.php') ? 'active' : ''; ?>"><i class="fas fa-chalkboard-teacher"></i> <span>Enseignants</span></a></li>
                    <li><a href="isp.php" class="<?php echo ($current_page == 'isp.php') ? 'active' : ''; ?>"><i class="fas fa-building"></i> <span>ISP-Muhanga</span></a></li>
                    <li><a href="section.php" class="<?php echo ($current_page == 'section.php') ? 'active' : ''; ?>"><i class="fas fa-layer-group"></i> <span>Sections</span></a></li>
                    <li><a href="mention.php" class="<?php echo ($current_page == 'mention.php') ? 'active' : ''; ?>"><i class="fas fa-bookmark"></i> <span>Mentions</span></a></li>
                    <li><a href="promotion.php" class="<?php echo ($current_page == 'promotion.php') ? 'active' : ''; ?>"><i class="fas fa-users"></i> <span>Promotions</span></a></li>
                    <li><a href="inscription.php" class="<?php echo ($current_page == 'inscription.php') ? 'active' : ''; ?>"><i class="fas fa-edit"></i> <span>Inscriptions</span></a></li>
                    <li><a href="cours.php" class="<?php echo ($current_page == 'cours.php') ? 'active' : ''; ?>"><i class="fas fa-book"></i> <span>Cours</span></a></li>
                    <li><a href="horaire.php" class="<?php echo ($current_page == 'horaire.php') ? 'active' : ''; ?>"><i class="fas fa-clock"></i> <span>Horaires</span></a></li>
                    <li><a href="analyse.php" class="<?php echo ($current_page == 'analyse.php') ? 'active' : ''; ?>"><i class="fas fa-chart-bar"></i> <span>Analyse</span></a></li>
                </ul>
            </nav>
        </aside>