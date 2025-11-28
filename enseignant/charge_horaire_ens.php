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
    <link rel="stylesheet" href="teacher_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="teacher-header">
        <h1><i class="fas fa-hourglass-half"></i> Ma Charge Horaire</h1>
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
                    <li><a href="index_ens.php"><i class="fas fa-home"></i> <span>Tableau de bord</span></a></li>
                    <li><a href="horaire_ens.php"><i class="fas fa-clock"></i> <span>Mon Horaire</span></a></li>
                    <li><a href="charge_horaire_ens.php" class="active"><i class="fas fa-hourglass-half"></i> <span>Ma Charge Horaire</span></a></li>
                    <li><a href="prestation_ens.php"><i class="fas fa-file-invoice"></i> <span>Fiche de Prestation</span></a></li>
                    <li><a href="description_ens.php"><i class="fas fa-book-open"></i> <span>Plan de Cours</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="teacher-main">
            <div class="content-header">
                <h2>Ma Charge Horaire</h2>
                <ul class="breadcrumb">
                    <li><a href="index_ens.php">Tableau de bord</a></li>
                    <li>Ma Charge Horaire</li>
                </ul>
            </div>

            <?php if (!empty($success_message)): ?>
                <div class="teacher-alert teacher-alert-success">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="teacher-alert teacher-alert-danger">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <div class="teacher-table">
                <table>
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
                        <?php if (count($charges) > 0): ?>
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
                            <tr class="table-total">
                                <td colspan="2"><strong>Total</strong></td>
                                <td><strong><?php echo $total_heures; ?> heures</strong></td>
                                <td colspan="3"></td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Vous n'avez pas encore de charge horaire attribuée.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="teacher-actions">
                <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Imprimer ma charge horaire</button>
                
                <!-- Formulaire de réclamation -->
                <button class="btn btn-warning" onclick="document.getElementById('claimForm').style.display='block'">
                    <i class="fas fa-exclamation-circle"></i> Faire une réclamation
                </button>
            </div>
            
            <!-- Formulaire de réclamation (caché par défaut) -->
            <div id="claimForm" style="display:none; margin-top: 20px;">
                <div class="teacher-section">
                    <h3 class="section-title">Formulaire de Réclamation</h3>
                    <p>Si vous constatez une anomalie dans votre charge horaire, veuillez la signaler en remplissant ce formulaire. Elle sera transmise à votre chef de section.</p>
                    <form class="teacher-form" method="POST">
                        <div class="form-group">
                            <label>Objet de la réclamation:</label>
                            <input type="text" class="form-control" name="objet" placeholder="Ex: Disparité de charge horaire" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Détails:</label>
                            <textarea class="form-control" name="details" rows="5" placeholder="Décrivez précisément votre réclamation" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Destinataire:</label>
                            <select class="form-control" name="destinataire" required>
                                <option value="">Sélectionnez un destinataire</option>
                                <option value="chef_section">Chef de Section</option>
                                <option value="chef_promotion">Chef de Promotion</option>
                                <option value="sga">Service de Gestion Académique</option>
                            </select>
                        </div>
                        
                        <div class="teacher-actions">
                            <button type="submit" name="submit_claim" class="btn btn-danger"><i class="fas fa-paper-plane"></i> Soumettre la réclamation</button>
                            <button type="button" class="btn btn-secondary" onclick="document.getElementById('claimForm').style.display='none'"><i class="fas fa-times"></i> Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="teacher-section">
                <h3 class="section-title">Informations importantes</h3>
                <ul>
                    <li>✅ La charge horaire totale ne doit pas dépasser 20 heures par semaine</li>
                    <li>✅ Toute modification de votre charge horaire doit être signalée immédiatement</li>
                    <li>✅ Les réclamations sont traitées dans un délai de 5 jours ouvrables</li>
                    <li>✅ En cas de contestation, contactez votre chef de section</li>
                </ul>
            </div>
            
            <div class="teacher-actions">
                <a href="index_ens.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour au tableau de bord</a>
                <a href="horaire_ens.php" class="btn btn-primary"><i class="fas fa-clock"></i> Voir mon horaire</a>
            </div>
        </main>
    </div>

    <footer class="teacher-footer">
        <p>&copy; 2025 Système de Suivi de Prestation - Tous droits réservés</p>
    </footer>
</body>
</html>