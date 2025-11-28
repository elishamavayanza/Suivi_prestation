<?php 
    session_start();
    
    // Vérifier si l'utilisateur est connecté et s'il est enseignant
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Enseignant') {
        header("Location: ../login.php");
        exit();
    }

    include("../script/config.php");
    
    // Messages d'alerte
    $success_message = "";
    $error_message = "";
    
    try {
        // Récupérer l'horaire de l'enseignant connecté
        $sql = "SELECT h.jourheure, h.codepromotion AS pro, h.codemention AS dep, 
                       c.nomComplet AS cours, h.enseignant, h.site, h.observation, h.datejour 
                FROM horaire h, cours c 
                WHERE h.idcours = c.code_cours 
                  AND h.enseignant LIKE ? 
                ORDER BY h.datejour DESC, h.jourheure ASC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array('%' . $_SESSION['username'] . '%'));
        $horaires = $stmt->fetchAll();
        
    } catch (Exception $e) {
        $error_message = "Erreur lors du chargement de l'horaire: " . $e->getMessage();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Horaire - Interface Enseignant</title>
    <link rel="stylesheet" href="style_enseignant.css">
    <link rel="stylesheet" href="elegant_tabs.css">
</head>
<body class="teacher-dashboard">
    <header class="teacher-header">
        <h1>Mon Horaire</h1>
        <div class="teacher-user-info">
            <span>Bienvenue, <?php echo $_SESSION['username']; ?></span>
            <a href="../script/logout.php" class="teacher-logout">Déconnexion</a>
        </div>
    </header>

    <nav class="teacher-navigation">
        <ul>
            <li><a href="index.php">Tableau de bord</a></li>
            <li><a href="horaire.php" class="active">Mon Horaire</a></li>
            <li><a href="charge_horaire.php">Ma Charge Horaire</a></li>
            <li><a href="prestation.php">Fiche de Prestation</a></li>
            <li><a href="description.php">Plan de Cours</a></li>
        </ul>
    </nav>

    <main class="teacher-main">
        <?php if(!empty($success_message)): ?>
            <div class="teacher-alert teacher-alert-success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="teacher-alert teacher-alert-error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <section class="teacher-section teacher-horaire-section">
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
                    <h2 class="teacher-horaire-title">Mon Horaire de Cours</h2>
                    <p>Consultez votre emploi du temps hebdomadaire ci-dessous.</p>
                    
                    <div class="teacher-timetable">
                        <?php if (count($horaires) > 0): ?>
                            <table class="teacher-horaire-table">
                                <thead>
                                    <tr>
                                        <th>Jour & Heure</th>
                                        <th>Promotion/Mention</th>
                                        <th>Cours</th>
                                        <th>Site</th>
                                        <th>Observation</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($horaires as $row): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['jourheure']); ?></td>
                                        <td><?php echo htmlspecialchars($row['pro'] . '/' . $row['dep']); ?></td>
                                        <td><?php echo htmlspecialchars($row['cours']); ?></td>
                                        <td><?php echo htmlspecialchars($row['site']); ?></td>
                                        <td><?php echo htmlspecialchars($row['observation']); ?></td>
                                        <td><?php echo htmlspecialchars($row['datejour']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="teacher-alert teacher-alert-info">
                                <p>Vous n'avez aucun horaire programmé pour le moment.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="teacher-actions">
                        <a href="index.php" class="teacher-btn teacher-btn-secondary">Retour au tableau de bord</a>
                        <a href="charge_horaire.php" class="teacher-btn teacher-btn-primary">Voir ma charge horaire</a>
                        <a href="prestation.php" class="teacher-btn teacher-btn-warning">Remplir une fiche de prestation</a>
                        <button class="teacher-btn teacher-btn-danger" onclick="window.print()">Imprimer mon horaire</button>
                    </div>
                </div>
                
                <div class="teacher-tab-content" id="charge-tab">
                    <h2 class="teacher-charge-title">Ma Charge Horaire</h2>
                    <p>Visualisez votre charge de travail en heures.</p>
                    
                    <div class="teacher-actions">
                        <a href="charge_horaire.php" class="teacher-btn teacher-btn-primary">Voir ma Charge Horaire</a>
                        <a href="index.php" class="teacher-btn teacher-btn-secondary">Retour au tableau de bord</a>
                    </div>
                </div>
                
                <div class="teacher-tab-content" id="description-tab">
                    <h2 class="teacher-description-title">Plan de Cours</h2>
                    <p>Gérez vos plans de cours à soumettre.</p>
                    
                    <div class="teacher-actions">
                        <a href="description.php" class="teacher-btn teacher-btn-danger">Gérer mes Plans</a>
                        <a href="index.php" class="teacher-btn teacher-btn-secondary">Retour au tableau de bord</a>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="teacher-section">
            <h2>Aide et Support</h2>
            <p>Si vous constatez une erreur dans votre horaire, veuillez contacter votre chef de section ou le service de gestion académique.</p>
            <div class="teacher-actions">
                <a href="#" class="teacher-btn teacher-btn-secondary">Contacter le chef de section</a>
                <a href="#" class="teacher-btn teacher-btn-primary">Contacter le SGA</a>
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