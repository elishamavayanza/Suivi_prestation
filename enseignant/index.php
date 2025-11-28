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
    <link rel="stylesheet" href="style_enseignant.css">
    <link rel="stylesheet" href="elegant_tabs.css">
</head>
<body class="teacher-dashboard">
    <header class="teacher-header">
        <h1>Espace Enseignant - Suivi de Prestation</h1>
        <div class="teacher-user-info">
            <span>Bienvenue, <?php echo $_SESSION['username']; ?></span>
            <a href="../script/logout.php" class="teacher-logout">Déconnexion</a>
        </div>
    </header>

    <nav class="teacher-navigation">
        <ul>
            <li><a href="index.php" class="active">Tableau de bord</a></li>
            <li><a href="horaire.php">Mon Horaire</a></li>
            <li><a href="charge_horaire.php">Ma Charge Horaire</a></li>
            <li><a href="prestation.php">Fiche de Prestation</a></li>
            <li><a href="description.php">Plan de Cours</a></li>
        </ul>
    </nav>

    <main class="teacher-main">
        <?php if (isset($error)): ?>
            <div class="teacher-alert teacher-alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <section class="teacher-section">
            <h2>Vue d'ensemble</h2>
            <p>Consultez rapidement vos informations clés.</p>
            
            <div class="teacher-stats-grid">
                <div class="teacher-stat-card">
                    <div class="teacher-stat-value"><?php echo $nb_cours; ?></div>
                    <div class="teacher-stat-label">Cours Assignés</div>
                </div>
                
                <div class="teacher-stat-card">
                    <div class="teacher-stat-value"><?php echo $nb_prestations; ?></div>
                    <div class="teacher-stat-label">Fiches de Prestation</div>
                </div>
                
                <div class="teacher-stat-card">
                    <div class="teacher-stat-value"><?php echo $nb_plans; ?></div>
                    <div class="teacher-stat-label">Plans de Cours</div>
                </div>
            </div>
        </section>
        
        <section class="teacher-section">
            <h2>Accès Rapide</h2>
            <p>Naviguez facilement entre les différentes sections.</p>
            
            <div class="teacher-tabs-container">
                <div class="teacher-tabs">
                    <div class="teacher-tab active" data-tab="horaire">
                        <span class="teacher-tab-icon">🕒</span> Horaire
                    </div>
                    <div class="teacher-tab" data-tab="charge">
                        <span class="teacher-tab-icon">📊</span> Charge Horaire
                    </div>
                    <div class="teacher-tab" data-tab="description">
                        <span class="teacher-tab-icon">📝</span> Plan de Cours
                    </div>
                </div>
                
                <div class="teacher-tab-content active" id="horaire-tab">
                    <div class="teacher-tab-card tab-horaire">
                        <div class="teacher-tab-card-header">
                            <h3 class="teacher-tab-card-title">Mon Horaire</h3>
                            <span class="teacher-tab-card-badge">Consultation</span>
                        </div>
                        <div class="teacher-tab-card-body">
                            <p>Consultez votre emploi du temps hebdomadaire. Vérifiez les jours et heures de vos cours, les salles attribuées ainsi que toutes les observations importantes.</p>
                            <div class="teacher-actions">
                                <a href="horaire.php" class="teacher-btn teacher-btn-primary">Voir mon Horaire</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="teacher-tab-content" id="charge-tab">
                    <div class="teacher-tab-card tab-charge">
                        <div class="teacher-tab-card-header">
                            <h3 class="teacher-tab-card-title">Ma Charge Horaire</h3>
                            <span class="teacher-tab-card-badge">Suivi</span>
                        </div>
                        <div class="teacher-tab-card-body">
                            <p>Visualisez la liste de vos cours avec le nombre d'heures attribuées. Vous pouvez également signaler toute anomalie dans votre charge horaire.</p>
                            <div class="teacher-actions">
                                <a href="charge_horaire.php" class="teacher-btn teacher-btn-secondary">Voir ma Charge Horaire</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="teacher-tab-content" id="description-tab">
                    <div class="teacher-tab-card tab-description">
                        <div class="teacher-tab-card-header">
                            <h3 class="teacher-tab-card-title">Plan de Cours</h3>
                            <span class="teacher-tab-card-badge">Gestion</span>
                        </div>
                        <div class="teacher-tab-card-body">
                            <p>Créez, modifiez et soumettez vos plans de cours. Avant chaque début de cours, vous devez soumettre un plan détaillé à l'académique, au chef de section et au chef de promotion.</p>
                            <div class="teacher-actions">
                                <a href="description.php" class="teacher-btn teacher-btn-danger">Gérer mes Plans</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="teacher-section">
            <h2>Fonctionnalités principales</h2>
            <div class="teacher-actions">
                <a href="horaire.php" class="teacher-btn teacher-btn-primary">Consulter mon Horaire</a>
                <a href="charge_horaire.php" class="teacher-btn teacher-btn-secondary">Voir ma Charge Horaire</a>
                <a href="prestation.php" class="teacher-btn teacher-btn-warning">Fiche de Prestation</a>
                <a href="description.php" class="teacher-btn teacher-btn-danger">Plan de Cours</a>
            </div>
        </section>

        <section class="teacher-section">
            <h2>Responsabilités quotidiennes</h2>
            <ul>
                <li>✅ Vérifier votre horaire de cours chaque jour</li>
                <li>✅ Assurer vos cours selon l'horaire établi</li>
                <li>✅ À la fin de chaque séance, remplir et signer la fiche de prestation</li>
                <li>✅ Si vous constatez une anomalie dans votre charge horaire, la signaler au chef de section</li>
                <li>✅ Avant de dispenser un cours, compléter la fiche de plan de cours et la transmettre à l'académique, au chef de section et au chef de promotion</li>
            </ul>
        </section>
        
        <section class="teacher-section">
            <h2>Guides et Ressources</h2>
            <div class="teacher-card-container">
                <div class="teacher-card">
                    <h3>Procédures de Prestation</h3>
                    <p>Suivez les étapes pour remplir correctement vos fiches de prestation quotidiennes.</p>
                </div>
                
                <div class="teacher-card">
                    <h3>Modèles de Plans de Cours</h3>
                    <p>Accédez aux modèles standardisés pour la création de vos plans de cours.</p>
                </div>
                
                <div class="teacher-card">
                    <h3>Contactez le Support</h3>
                    <p>Besoin d'aide ? Contactez le service informatique ou votre chef de section.</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="teacher-footer">
        <p>&copy; 2025 Système de Suivi de Prestation - Tous droits réservés</p>
    </footer>
    
    <script>
        // Gestion des onglets
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.teacher-tab');
            const tabContents = document.querySelectorAll('.teacher-tab-content');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Retirer la classe active de tous les onglets
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));
                    
                    // Ajouter la classe active à l'onglet cliqué
                    tab.classList.add('active');
                    
                    // Afficher le contenu correspondant
                    const tabId = tab.getAttribute('data-tab') + '-tab';
                    const targetTab = document.getElementById(tabId);
                    if (targetTab) {
                        targetTab.classList.add('active');
                    }
                });
            });
        });
    </script>
</body>
</html>