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
        // Récupérer la charge horaire de l'enseignant
        $sql = "SELECT c.code_cours AS code, c.nomComplet AS nom, c.nbreHeure AS heure, 
                       c.ponderation AS max, s.nomComplet AS nomsection, m.nomComplet AS nommention 
                FROM cours c, section s, mention m 
                WHERE c.code_section = s.code_section 
                  AND c.code_mention = m.code_mention 
                  AND c.enseignant LIKE ?
                ORDER BY c.nomComplet";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array('%' . $_SESSION['username'] . '%'));
        $charges = $stmt->fetchAll();
        
        // Calculer le total des heures
        $total_heures = 0;
        foreach ($charges as $charge) {
            $total_heures += $charge['heure'];
        }
        
    } catch (Exception $e) {
        $error_message = "Erreur lors du chargement de la charge horaire: " . $e->getMessage();
    }
    
    // Traitement du formulaire de réclamation
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_claim'])) {
        try {
            $objet = $_POST['objet'];
            $details = $_POST['details'];
            $destinataire = $_POST['destinataire'];
            
            // Ici, vous pouvez ajouter le code pour enregistrer la réclamation dans la base de données
            $success_message = "Votre réclamation a été soumise avec succès.";
            
        } catch (Exception $e) {
            $error_message = "Erreur lors de la soumission de la réclamation: " . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Charge Horaire - Interface Enseignant</title>
    <link rel="stylesheet" href="style_enseignant.css">
    <link rel="stylesheet" href="elegant_tabs.css">
</head>
<body class="teacher-dashboard">
    <header class="teacher-header">
        <h1>Ma Charge Horaire</h1>
        <div class="teacher-user-info">
            <span>Bienvenue, <?php echo $_SESSION['username']; ?></span>
            <a href="../script/logout.php" class="teacher-logout">Déconnexion</a>
        </div>
    </header>

    <nav class="teacher-navigation">
        <ul>
            <li><a href="index.php">Tableau de bord</a></li>
            <li><a href="horaire.php">Mon Horaire</a></li>
            <li><a href="charge_horaire.php" class="active">Ma Charge Horaire</a></li>
            <li><a href="prestation.php">Fiche de Prestation</a></li>
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
        
        <section class="teacher-section teacher-charge-section">
            <div class="teacher-tabs-container">
                <div class="teacher-tabs">
                    <div class="teacher-tab" data-tab="horaire">
                        <span class="teacher-tab-icon">🕒</span> Horaire
                    </div>
                    <div class="teacher-tab active" data-tab="charge">
                        <span class="teacher-tab-icon">📊</span> Charge Horaire
                    </div>
                    <div class="teacher-tab" data-tab="description">
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
                
                <div class="teacher-tab-content active" id="charge-tab">
                    <h2 class="teacher-charge-title">Ma Charge Horaire</h2>
                    <p>Voici la liste des cours qui vous sont assignés avec le nombre d'heures correspondant.</p>
                    
                    <div class="teacher-workload">
                        <?php if (count($charges) > 0): ?>
                            <table class="teacher-charge-table">
                                <thead>
                                    <tr>
                                        <th>Code Cours</th>
                                        <th>Nom du Cours</th>
                                        <th>Nombre d'Heures</th>
                                        <th>Points Max</th>
                                        <th>Section</th>
                                        <th>Mention</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($charges as $row): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['code']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nom']); ?></td>
                                        <td><?php echo htmlspecialchars($row['heure']); ?></td>
                                        <td><?php echo htmlspecialchars($row['max']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nomsection']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nommention']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr style="font-weight: bold; background-color: #e9f7fe;">
                                        <td colspan="2">Total</td>
                                        <td><?php echo $total_heures; ?> heures</td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <div class="teacher-actions" style="margin-top: 20px;">
                                <button class="teacher-btn teacher-btn-primary" onclick="window.print()">Imprimer ma charge horaire</button>
                                
                                <!-- Formulaire de réclamation -->
                                <button class="teacher-btn teacher-btn-warning" onclick="document.getElementById('claimForm').style.display='block'">
                                    Faire une réclamation
                                </button>
                            </div>
                            
                            <!-- Formulaire de réclamation (caché par défaut) -->
                            <div id="claimForm" style="display:none; margin-top: 20px;">
                                <div class="teacher-section">
                                    <h3>Formulaire de Réclamation</h3>
                                    <p>Si vous constatez une anomalie dans votre charge horaire, veuillez la signaler en remplissant ce formulaire. Elle sera transmise à votre chef de section.</p>
                                    <form class="teacher-form" method="POST">
                                        <div class="teacher-form-group">
                                            <label class="teacher-form-label">Objet de la réclamation:</label>
                                            <input type="text" class="teacher-form-input" name="objet" placeholder="Ex: Disparité de charge horaire" required>
                                        </div>
                                        
                                        <div class="teacher-form-group">
                                            <label class="teacher-form-label">Détails:</label>
                                            <textarea class="teacher-form-input" name="details" rows="5" placeholder="Décrivez précisément votre réclamation" required></textarea>
                                        </div>
                                        
                                        <div class="teacher-form-group">
                                            <label class="teacher-form-label">Destinataire:</label>
                                            <select class="teacher-form-select" name="destinataire" required>
                                                <option value="">Sélectionnez un destinataire</option>
                                                <option value="chef_section">Chef de Section</option>
                                                <option value="chef_promotion">Chef de Promotion</option>
                                                <option value="sga">Service de Gestion Académique</option>
                                            </select>
                                        </div>
                                        
                                        <div class="teacher-actions">
                                            <button type="submit" name="submit_claim" class="teacher-btn teacher-btn-danger">Soumettre la réclamation</button>
                                            <button type="button" class="teacher-btn teacher-btn-secondary" onclick="document.getElementById('claimForm').style.display='none'">Annuler</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="teacher-alert teacher-alert-info">
                                <p>Vous n'avez pas encore de charge horaire attribuée.</p>
                            </div>
                        <?php endif; ?>
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
            <h2>Informations importantes</h2>
            <ul>
                <li>La charge horaire totale ne doit pas dépasser 20 heures par semaine</li>
                <li>Toute modification de votre charge horaire doit être signalée immédiatement</li>
                <li>Les réclamations sont traitées dans un délai de 5 jours ouvrables</li>
                <li>En cas de contestation, contactez votre chef de section</li>
            </ul>
        </section>
        
        <div class="teacher-actions">
            <a href="index.php" class="teacher-btn teacher-btn-secondary">Retour au tableau de bord</a>
            <a href="horaire.php" class="teacher-btn teacher-btn-primary">Voir mon horaire</a>
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