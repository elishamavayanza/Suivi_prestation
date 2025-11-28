<?php
global $pdo;
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
    
    // Récupérer les informations de l'enseignant
    try {
        // Récupérer les cours assignés à cet enseignant
        $sql_cours = "SELECT code_cours, nomComplet FROM cours WHERE enseignant LIKE ?";
        $stmt_cours = $pdo->prepare($sql_cours);
        $stmt_cours->execute(array('%' . $_SESSION['username'] . '%'));
        $cours_list = $stmt_cours->fetchAll();
        
        // Récupérer les fiches de prestation soumises
        $sql_fiches = "SELECT ef.*, c.nomComplet as cours_nom 
                      FROM entetefiche ef, cours c 
                      WHERE ef.code_cours = c.code_cours 
                        AND ef.enseignant LIKE ? 
                      ORDER BY ef.datecreation DESC 
                      LIMIT 10";
        $stmt_fiches = $pdo->prepare($sql_fiches);
        $stmt_fiches->execute(array('%' . $_SESSION['username'] . '%'));
        $fiches = $stmt_fiches->fetchAll();
        
    } catch (Exception $e) {
        $error_message = "Erreur lors du chargement des données: " . $e->getMessage();
    }
    
    // Traitement du formulaire de fiche de prestation
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_prestation'])) {
        try {
            $code_cours = $_POST['cours'];
            $date_creation = $_POST['date_creation'];
            $heure_debut = $_POST['heure_debut'];
            $heure_fin = $_POST['heure_fin'];
            $objectif = $_POST['objectif'];
            $competence = $_POST['competence'];
            $methodes = $_POST['methodes'];
            $moyens = $_POST['moyens'];
            $evaluation = $_POST['evaluation'];
            $contenu = $_POST['contenu'];
            $difficultes = $_POST['difficultes'];
            $mesures = $_POST['mesures'];
            $signature = $_POST['signature'];
            
            // Insérer la nouvelle fiche de prestation
            $sql_insert = "INSERT INTO entetefiche (
                code_cours, enseignant, datecreation, heure_debut, heure_fin,
                objectif, competence_visee, methodes_utilisees, moyens_utilises,
                evaluation_effectuee, contenu_donne, difficultes_rencontrees,
                mesures_prises, signature
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute(array(
                $code_cours, $_SESSION['username'], $date_creation, $heure_debut, $heure_fin,
                $objectif, $competence, $methodes, $moyens, $evaluation, $contenu,
                $difficultes, $mesures, $signature
            ));
            
            $success_message = "Fiche de prestation soumise avec succès.";
            
        } catch (Exception $e) {
            $error_message = "Erreur lors de la soumission de la fiche de prestation: " . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche de Prestation - Interface Enseignant</title>
    <link rel="stylesheet" href="style_enseignant.css">
    <link rel="stylesheet" href="elegant_tabs.css">
</head>
<body class="teacher-dashboard">
    <header class="teacher-header">
        <h1>Fiche de Prestation</h1>
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
            <li><a href="prestation.php" class="active">Fiche de Prestation</a></li>
            <li><a href="description.php">Plan de Cours</a></li>
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
        
        <section class="teacher-section">
            <div class="teacher-tabs-container">
                <div class="teacher-tabs">
                    <div class="teacher-tab" data-tab="horaire">
                        <span class="teacher-tab-icon">🕒</span> Horaire
                    </div>
                    <div class="teacher-tab" data-tab="charge">
                        <span class="teacher-tab-icon">📊</span> Charge Horaire
                    </div>
                    <div class="teacher-tab active" data-tab="prestation">
                        <span class="teacher-tab-icon">📋</span> Fiche de Prestation
                    </div>
                    <div class="teacher-tab" data-tab="description">
                        <span class="teacher-tab-icon">📝</span> Plan de Cours
                    </div>
                </div>
                
                <div class="teacher-tab-content" id="horaire-tab">
                    <h2>Mon Horaire</h2>
                    <p>Consultez votre emploi du temps hebdomadaire.</p>
                    
                    <div class="teacher-actions">
                        <a href="horaire.php" class="teacher-btn teacher-btn-primary">Voir mon Horaire</a>
                        <a href="index.php" class="teacher-btn teacher-btn-secondary">Retour au tableau de bord</a>
                    </div>
                </div>
                
                <div class="teacher-tab-content" id="charge-tab">
                    <h2>Ma Charge Horaire</h2>
                    <p>Visualisez votre charge de travail en heures.</p>
                    
                    <div class="teacher-actions">
                        <a href="charge_horaire.php" class="teacher-btn teacher-btn-primary">Voir ma Charge Horaire</a>
                        <a href="index.php" class="teacher-btn teacher-btn-secondary">Retour au tableau de bord</a>
                    </div>
                </div>
                
                <div class="teacher-tab-content active" id="prestation-tab">
                    <h2>Remplir une Fiche de Prestation</h2>
                    <p>À la fin de chaque séance de cours, remplissez une fiche de prestation détaillée.</p>
                    
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
                        
                        <div class="teacher-form-row">
                            <div class="teacher-form-group" style="flex: 1; margin-right: 1rem;">
                                <label class="teacher-form-label">Date:</label>
                                <input type="date" name="date_creation" class="teacher-form-input" required>
                            </div>
                            
                            <div class="teacher-form-group" style="flex: 1;">
                                <label class="teacher-form-label">Heure de début:</label>
                                <input type="time" name="heure_debut" class="teacher-form-input" required>
                            </div>
                            
                            <div class="teacher-form-group" style="flex: 1; margin-left: 1rem;">
                                <label class="teacher-form-label">Heure de fin:</label>
                                <input type="time" name="heure_fin" class="teacher-form-input" required>
                            </div>
                        </div>
                        
                        <div class="teacher-form-group">
                            <label class="teacher-form-label">Objectif de la séance:</label>
                            <textarea name="objectif" class="teacher-form-input" rows="2" placeholder="Quel était l'objectif de cette séance ?" required></textarea>
                        </div>
                        
                        <div class="teacher-form-group">
                            <label class="teacher-form-label">Compétence visée:</label>
                            <textarea name="competence" class="teacher-form-input" rows="2" placeholder="Quelle compétence devait être acquise ?" required></textarea>
                        </div>
                        
                        <div class="teacher-form-group">
                            <label class="teacher-form-label">Méthodes utilisées:</label>
                            <input type="text" name="methodes" class="teacher-form-input" placeholder="Ex: Cours magistral, Travaux dirigés, Étude de cas" required>
                        </div>
                        
                        <div class="teacher-form-group">
                            <label class="teacher-form-label">Moyens utilisés:</label>
                            <input type="text" name="moyens" class="teacher-form-input" placeholder="Ex: Tableau, Vidéo projecteur, Documents" required>
                        </div>
                        
                        <div class="teacher-form-group">
                            <label class="teacher-form-label">Évaluation effectuée:</label>
                            <textarea name="evaluation" class="teacher-form-input" rows="2" placeholder="Décrivez l'évaluation réalisée durant la séance" required></textarea>
                        </div>
                        
                        <div class="teacher-form-group">
                            <label class="teacher-form-label">Contenu donné:</label>
                            <textarea name="contenu" class="teacher-form-input" rows="3" placeholder="Décrivez en détail le contenu enseigné durant cette séance" required></textarea>
                        </div>
                        
                        <div class="teacher-form-group">
                            <label class="teacher-form-label">Difficultés rencontrées:</label>
                            <textarea name="difficultes" class="teacher-form-input" rows="2" placeholder="Décrivez les difficultés rencontrées pendant la séance"></textarea>
                        </div>
                        
                        <div class="teacher-form-group">
                            <label class="teacher-form-label">Mesures prises:</label>
                            <textarea name="mesures" class="teacher-form-input" rows="2" placeholder="Décrivez les mesures prises pour pallier aux difficultés"></textarea>
                        </div>
                        
                        <div class="teacher-form-group">
                            <label class="teacher-form-label">Signature (Nom complet):</label>
                            <input type="text" name="signature" class="teacher-form-input" placeholder="Entrez votre nom complet" required>
                        </div>
                        
                        <div class="teacher-actions">
                            <button type="submit" name="submit_prestation" class="teacher-btn teacher-btn-primary">Soumettre la fiche</button>
                            <button type="reset" class="teacher-btn teacher-btn-secondary">Réinitialiser</button>
                        </div>
                    </form>
                    
                    <section class="teacher-section" style="margin-top: 2rem;">
                        <h2>Mes Dernières Fiches de Prestation</h2>
                        <p>Historique de vos dernières fiches de prestation soumises.</p>
                        
                        <div class="teacher-timetable">
                            <?php if (count($fiches) > 0): ?>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Cours</th>
                                            <th>Date</th>
                                            <th>Heures</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($fiches as $fiche): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($fiche['cours_nom']); ?></td>
                                            <td><?php echo htmlspecialchars($fiche['datecreation']); ?></td>
                                            <td><?php echo htmlspecialchars($fiche['heure_debut'] . ' - ' . $fiche['heure_fin']); ?></td>
                                            <td>
                                                <span style="color: #27ae60; font-weight: bold;">Soumis</span>
                                            </td>
                                            <td>
                                                <button class="teacher-btn teacher-btn-secondary" style="padding: 5px 10px; font-size: 0.9rem;">Voir</button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="teacher-alert teacher-alert-info">
                                    <p>Vous n'avez pas encore soumis de fiche de prestation.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                </div>
                
                <div class="teacher-tab-content" id="description-tab">
                    <h2>Plan de Cours</h2>
                    <p>Gérez vos plans de cours à soumettre.</p>
                    
                    <div class="teacher-actions">
                        <a href="description.php" class="teacher-btn teacher-btn-danger">Gérer mes Plans</a>
                        <a href="index.php" class="teacher-btn teacher-btn-secondary">Retour au tableau de bord</a>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="teacher-section">
            <h2>Instructions importantes</h2>
            <ul>
                <li>Chaque fiche de prestation doit être remplie immédiatement après la séance de cours</li>
                <li>La signature est obligatoire pour valider la fiche</li>
                <li>Les fiches incomplètes ne seront pas acceptées</li>
                <li>En cas de problème technique, contactez immédiatement le service informatique</li>
            </ul>
        </section>
        
        <div class="teacher-actions">
            <a href="index.php" class="teacher-btn teacher-btn-secondary">Retour au tableau de bord</a>
            <button class="teacher-btn teacher-btn-primary" onclick="window.print()">Imprimer cette page</button>
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