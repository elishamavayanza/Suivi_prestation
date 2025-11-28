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
    
    // Traitement du formulaire de plan de cours
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_plan'])) {
        try {
            $cours = $_POST['cours'];
            $objectif = $_POST['objectif'];
            $contenu = $_POST['contenu'];
            $methodes = $_POST['methodes'];
            $moyens = $_POST['moyens'];
            $evaluation = $_POST['evaluation'];
            $references = $_POST['references'];
            
            // Vérifier si un plan existe déjà pour ce cours
            $sql_check = "SELECT id FROM description_cours WHERE code_cours = ? AND enseignant LIKE ?";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute(array($cours, '%' . $_SESSION['username'] . '%'));
            
            if ($stmt_check->rowCount() > 0) {
                // Mettre à jour le plan existant
                $sql_update = "UPDATE description_cours SET objectif = ?, contenu = ?, methodes = ?, moyens = ?, evaluation = ?, references_biblio = ?, date_soumission = NOW(), statut = 'Soumis' WHERE code_cours = ? AND enseignant LIKE ?";
                $stmt_update = $pdo->prepare($sql_update);
                $stmt_update->execute(array($objectif, $contenu, $methodes, $moyens, $evaluation, $references, $cours, '%' . $_SESSION['username'] . '%'));
            } else {
                // Insérer un nouveau plan
                $sql_insert = "INSERT INTO description_cours (code_cours, enseignant, objectif, contenu, methodes, moyens, evaluation, references_biblio, date_soumission, statut) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'Soumis')";
                $stmt_insert = $pdo->prepare($sql_insert);
                $stmt_insert->execute(array($cours, $_SESSION['username'], $objectif, $contenu, $methodes, $moyens, $evaluation, $references));
            }
            
            $success_message = "Plan de cours soumis avec succès.";
            
        } catch (Exception $e) {
            $error_message = "Erreur lors de la soumission du plan de cours: " . $e->getMessage();
        }
    }
    
    try {
        // Récupérer les cours assignés à cet enseignant
        $sql_cours = "SELECT code_cours, nomComplet FROM cours WHERE enseignant LIKE ?";
        $stmt_cours = $pdo->prepare($sql_cours);
        $stmt_cours->execute(array('%' . $_SESSION['username'] . '%'));
        $cours_list = $stmt_cours->fetchAll();
        
        // Récupérer les plans de cours soumis par l'enseignant
        $sql_plans = "SELECT dc.id, c.nomComplet AS cours, dc.date_soumission, dc.statut
                      FROM description_cours dc, cours c
                      WHERE dc.code_cours = c.code_cours
                        AND dc.enseignant LIKE ?
                      ORDER BY dc.date_soumission DESC";
        
        $stmt_plans = $pdo->prepare($sql_plans);
        $stmt_plans->execute(array('%' . $_SESSION['username'] . '%'));
        $plans = $stmt_plans->fetchAll();
        
    } catch (Exception $e) {
        $error_message = "Erreur lors du chargement des données: " . $e->getMessage();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan de Cours - Interface Enseignant</title>
    <link rel="stylesheet" href="style_enseignant.css">
    <link rel="stylesheet" href="elegant_tabs.css">
</head>
<body class="teacher-dashboard">
    <header class="teacher-header">
        <h1>Plan de Cours</h1>
        <div class="teacher-user-info">
            <span>Bienvenue, <?php echo $_SESSION['username']; ?></span>
            <a href="../script/logout.php" class="teacher-logout">Déconnexion</a>
        </div>
    </header>

    <nav class="teacher-navigation">
        <ul>
            <li><a href="index.php">Tableau de bord</a></li>
            <li><a href="horaire.php">Mon Horaire</a></li>
            <li><a href="charge_horaire.php">Ma Charge Horaire</a></li>
            <li><a href="prestation.php">Fiche de Prestation</a></li>
            <li><a href="description.php" class="active">Plan de Cours</a></li>
        </ul>
    </nav>

    <main class="teacher-main">
        <?php if (!empty($success_message)): ?>
            <div class="teacher-alert teacher-alert-success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="teacher-alert teacher-alert-error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <section class="teacher-section teacher-description-section">
            <div class="teacher-tabs-container">
                <div class="teacher-tabs">
                    <div class="teacher-tab" data-tab="horaire">
                        <span class="teacher-tab-icon">🕒</span> Horaire
                    </div>
                    <div class="teacher-tab" data-tab="charge">
                        <span class="teacher-tab-icon">📊</span> Charge Horaire
                    </div>
                    <div class="teacher-tab active" data-tab="description">
                        <span class="teacher-tab-icon">📝</span> Plan de Cours
                    </div>
                </div>
                
                <div class="teacher-tab-content" id="horaire-tab">
                    <h2 class="teacher-horaire-title">Mon Horaire</h2>
                    <p>Consultez votre emploi du temps hebdomadaire.</p>
                    
                    <div class="teacher-actions">
                        <a href="horaire.php" class="teacher-btn teacher-btn-primary">Voir mon Horaire</a>
                        <a href="index.php" class="teacher-btn teacher-btn-secondary">Retour au tableau de bord</a>
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
                
                <div class="teacher-tab-content active" id="description-tab">
                    <h2 class="teacher-description-title">Compléter un Plan de Cours</h2>
                    <p>Avant de dispenser un cours, vous devez soumettre un plan détaillé à l'académique, au chef de section et au chef de promotion.</p>
                    
                    <form class="teacher-form" method="POST">
                        <div class="teacher-form-group">
                            <label class="teacher-form-label">Cours:</label>
                            <select name="cours" class="teacher-form-select" required>
                                <option value="">Sélectionnez un cours</option>
                                <?php foreach ($cours_list as $cours): ?>
                                    <option value="<?php echo htmlspecialchars($cours['code_cours']); ?>">
                                        <?php echo htmlspecialchars($cours['nomComplet']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="teacher-form-group">
                            <label class="teacher-form-label">Objectif du cours:</label>
                            <textarea name="objectif" class="teacher-form-input" rows="3" placeholder="Décrivez l'objectif général du cours" required></textarea>
                        </div>
                        
                        <div class="teacher-form-group">
                            <label class="teacher-form-label">Contenu détaillé (séance par séance):</label>
                            <textarea name="contenu" class="teacher-form-input" rows="6" placeholder="Décrivez le contenu détaillé de chaque séance" required></textarea>
                        </div>
                        
                        <div class="teacher-form-group">
                            <label class="teacher-form-label">Méthodes pédagogiques:</label>
                            <input type="text" name="methodes" class="teacher-form-input" placeholder="Ex: Cours magistral, Travaux dirigés, Étude de cas" required>
                        </div>
                        
                        <div class="teacher-form-group">
                            <label class="teacher-form-label">Moyens pédagogiques:</label>
                            <input type="text" name="moyens" class="teacher-form-input" placeholder="Ex: Tableau, Vidéo projecteur, Documents" required>
                        </div>
                        
                        <div class="teacher-form-group">
                            <label class="teacher-form-label">Modalités d'évaluation:</label>
                            <textarea name="evaluation" class="teacher-form-input" rows="3" placeholder="Décrivez les méthodes d'évaluation prévues" required></textarea>
                        </div>
                        
                        <div class="teacher-form-group">
                            <label class="teacher-form-label">Références bibliographiques:</label>
                            <textarea name="references" class="teacher-form-input" rows="4" placeholder="Listez les ouvrages et documents de référence" required></textarea>
                        </div>
                        
                        <div class="teacher-form-group">
                            <label class="teacher-form-label">Transmission:</label>
                            <div>
                                <p>Ce plan de cours sera transmis automatiquement aux personnes suivantes :</p>
                                <ul>
                                    <li>👉 Service académique</li>
                                    <li>👉 Chef de section</li>
                                    <li>👉 Chef de promotion</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="teacher-actions">
                            <button type="submit" name="submit_plan" class="teacher-btn teacher-btn-primary">Soumettre le plan</button>
                            <button type="reset" class="teacher-btn teacher-btn-secondary">Réinitialiser</button>
                        </div>
                    </form>
                    
                    <section class="teacher-section" style="margin-top: 2rem;">
                        <h2>Mes Plans de Cours Soumis</h2>
                        <p>Historique de vos plans de cours soumis avec leur statut.</p>
                        
                        <div class="teacher-timetable">
                            <?php if (count($plans) > 0): ?>
                                <table class="teacher-description-table">
                                    <thead>
                                        <tr>
                                            <th>Cours</th>
                                            <th>Date de soumission</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($plans as $row): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['cours']); ?></td>
                                            <td><?php echo htmlspecialchars($row['date_soumission']); ?></td>
                                            <td>
                                                <?php if ($row['statut'] == 'Soumis'): ?>
                                                    <span style="color: #3498db; font-weight: bold;">Soumis</span>
                                                <?php elseif ($row['statut'] == 'Approuvé'): ?>
                                                    <span style="color: #27ae60; font-weight: bold;">Approuvé</span>
                                                <?php elseif ($row['statut'] == 'Rejeté'): ?>
                                                    <span style="color: #e74c3c; font-weight: bold;">Rejeté</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button class="teacher-btn teacher-btn-secondary" style="padding: 5px 10px; font-size: 0.9rem;">Voir</button>
                                                <?php if ($row['statut'] == 'Rejeté'): ?>
                                                    <button class="teacher-btn teacher-btn-warning" style="padding: 5px 10px; font-size: 0.9rem;">Modifier</button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="teacher-alert teacher-alert-info">
                                    <p>Vous n'avez pas encore soumis de plan de cours.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                </div>
            </div>
        </section>
        
        <section class="teacher-section">
            <h2>Consignes importantes</h2>
            <ul>
                <li>Le plan de cours doit être soumis au moins 3 jours avant le début du cours</li>
                <li>Tout plan rejeté doit être corrigé et resoumis dans un délai de 48h</li>
                <li>Les modifications substantielles du plan doivent être signalées à l'avance</li>
                <li>Le non-respect de ces consignes peut entraîner le rejet de votre prestation</li>
            </ul>
        </section>
        
        <div class="teacher-actions">
            <a href="index.php" class="teacher-btn teacher-btn-secondary">Retour au tableau de bord</a>
        </div>
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