<?php
global $pdo;
session_start();
    
    // Vérifier si l'utilisateur est connecté et s'il est enseignant
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Enseignant') {
        header("Location: ../login.php");
        exit();
    }

    include("../script/config.php");
    
    // Récupérer des statistiques pour le dashboard
    try {
        // Nombre de cours assignés
        $sql_cours = "SELECT COUNT(*) as nb FROM cours WHERE enseignant LIKE ?";
        $stmt_cours = $pdo->prepare($sql_cours);
        $stmt_cours->execute(array('%' . $_SESSION['username'] . '%'));
        $nb_cours = $stmt_cours->fetch()['nb'];
        
        // Nombre de fiches de prestation remplies
        $sql_prestation = "SELECT COUNT(*) as nb FROM entetefiche ef WHERE ef.enseignant LIKE ?";
        $stmt_prestation = $pdo->prepare($sql_prestation);
        $stmt_prestation->execute(array('%' . $_SESSION['username'] . '%'));
        $nb_prestations = $stmt_prestation->fetch()['nb'];
        
        // Nombre de plans de cours soumis
        $sql_plans = "SELECT COUNT(*) as nb FROM description_cours dc WHERE dc.enseignant LIKE ?";
        $stmt_plans = $pdo->prepare($sql_plans);
        $stmt_plans->execute(array('%' . $_SESSION['username'] . '%'));
        $nb_plans = $stmt_plans->fetch()['nb'];
        
    } catch (Exception $e) {
        $error = "Erreur lors du chargement des statistiques: " . $e->getMessage();
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface Enseignant</title>
    <link rel="stylesheet" href="teacher_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="teacher-header">
        <h1><i class="fas fa-chalkboard-teacher"></i> Espace Enseignant</h1>
        <div class="header-actions">
            <a href="../index.php" class="btn btn-primary"><i class="fas fa-home"></i> <span>Accueil</span></a>
            <a href="../script/logout.php" class="teacher-logout"><i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span></a>
        </div>
    </header>

    <!-- Container -->
    <div class="teacher-container">
        <!-- Sidebar -->
        <aside class="teacher-sidebar">
            <div class="teacher-sidebar-header">
                <img src="../image/logo.jpg" alt="Logo">
                <h2>Enseignant</h2>
                <p><?php echo $_SESSION['username']; ?></p>
            </div>
            <nav class="teacher-nav-menu">
                <ul>
                    <li><a href="index_ens.php" class="active"><i class="fas fa-home"></i> <span>Tableau de bord</span></a></li>
                    <li><a href="horaire_ens.php"><i class="fas fa-clock"></i> <span>Mon Horaire</span></a></li>
                    <li><a href="charge_horaire_ens.php"><i class="fas fa-hourglass-half"></i> <span>Ma Charge Horaire</span></a></li>
                    <li><a href="prestation_ens.php"><i class="fas fa-file-invoice"></i> <span>Fiche de Prestation</span></a></li>
                    <li><a href="description_ens.php"><i class="fas fa-book-open"></i> <span>Plan de Cours</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="teacher-main">
            <div class="content-header">
                <h2>Tableau de bord enseignant</h2>
                <ul class="breadcrumb">
                    <li><a href="index_ens.php">Accueil</a></li>
                    <li>Tableau de bord</li>
                </ul>
            </div>

            <?php if (isset($error)): ?>
                <div class="teacher-alert teacher-alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-title">Cours Assignés</div>
                    <div class="card-value"><?php echo $nb_cours; ?></div>
                    <div class="card-footer">Cours enregistrés</div>
                </div>
                
                <div class="card charges">
                    <div class="card-title">Fiches de Prestation</div>
                    <div class="card-value"><?php echo $nb_prestations; ?></div>
                    <div class="card-footer">Fiches remplies</div>
                </div>
                
                <div class="card prestations">
                    <div class="card-title">Plans de Cours</div>
                    <div class="card-value"><?php echo $nb_plans; ?></div>
                    <div class="card-footer">Plans soumis</div>
                </div>
            </div>

            <div class="teacher-section">
                <h3 class="section-title">Accès Rapide</h3>
                <div class="teacher-actions">
                    <a href="horaire_ens.php" class="btn btn-primary"><i class="fas fa-clock"></i> Consulter mon Horaire</a>
                    <a href="charge_horaire_ens.php" class="btn btn-success"><i class="fas fa-hourglass-half"></i> Voir ma Charge Horaire</a>
                    <a href="prestation_ens.php" class="btn btn-warning"><i class="fas fa-file-invoice"></i> Fiche de Prestation</a>
                    <a href="description_ens.php" class="btn btn-danger"><i class="fas fa-book-open"></i> Plan de Cours</a>
                </div>
            </div>

            <div class="teacher-section">
                <h3 class="section-title">Responsabilités quotidiennes</h3>
                <ul>
                    <li>✅ Vérifier votre horaire de cours chaque jour</li>
                    <li>✅ Assurer vos cours selon l'horaire établi</li>
                    <li>✅ À la fin de chaque séance, remplir et signer la fiche de prestation</li>
                    <li>✅ Si vous constatez une anomalie dans votre charge horaire, la signaler au chef de section</li>
                    <li>✅ Avant de dispenser un cours, compléter la fiche de plan de cours et la transmettre à l'académique, au chef de section et au chef de promotion</li>
                </ul>
            </div>
            
            <div class="teacher-section">
                <h3 class="section-title">Guides et Ressources</h3>
                <div class="dashboard-cards">
                    <div class="card horaires">
                        <div class="card-title">Procédures de Prestation</div>
                        <div class="card-value"><i class="fas fa-file-alt"></i></div>
                        <div class="card-footer">Suivez les étapes pour remplir correctement vos fiches de prestation quotidiennes.</div>
                    </div>
                    
                    <div class="card">
                        <div class="card-title">Modèles de Plans de Cours</div>
                        <div class="card-value"><i class="fas fa-book"></i></div>
                        <div class="card-footer">Accédez aux modèles standardisés pour la création de vos plans de cours.</div>
                    </div>
                    
                    <div class="card charges">
                        <div class="card-title">Contactez le Support</div>
                        <div class="card-value"><i class="fas fa-headset"></i></div>
                        <div class="card-footer">Besoin d'aide ? Contactez le service informatique ou votre chef de section.</div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <footer class="teacher-footer">
        <p>&copy; 2025 Système de Suivi de Prestation - Tous droits réservés</p>
    </footer>
</body>
</html>