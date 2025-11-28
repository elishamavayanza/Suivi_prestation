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
    <link rel="stylesheet" href="teacher_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="teacher-header">
        <h1><i class="fas fa-book-open"></i> Plan de Cours</h1>
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
                    <li><a href="charge_horaire_ens.php"><i class="fas fa-hourglass-half"></i> <span>Ma Charge Horaire</span></a></li>
                    <li><a href="prestation_ens.php"><i class="fas fa-file-invoice"></i> <span>Fiche de Prestation</span></a></li>
                    <li><a href="description_ens.php" class="active"><i class="fas fa-book-open"></i> <span>Plan de Cours</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="teacher-main">
            <div class="content-header">
                <h2>Compléter un Plan de Cours</h2>
                <ul class="breadcrumb">
                    <li><a href="index_ens.php">Tableau de bord</a></li>
                    <li>Plan de Cours</li>
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
            
            <div class="teacher-form">
                <h3 class="form-title">Nouveau Plan de Cours</h3>
                <p>Avant de dispenser un cours, vous devez soumettre un plan détaillé à l'académique, au chef de section et au chef de promotion.</p>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Cours:</label>
                        <select name="cours" class="form-control" required>
                            <option value="">Sélectionnez un cours</option>
                            <?php foreach ($cours_list as $cours): ?>
                                <option value="<?php echo htmlspecialchars($cours['code_cours']); ?>">
                                    <?php echo htmlspecialchars($cours['nomComplet']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Objectif du cours:</label>
                        <textarea name="objectif" class="form-control" rows="3" placeholder="Décrivez l'objectif général du cours" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Contenu détaillé (séance par séance):</label>
                        <textarea name="contenu" class="form-control" rows="6" placeholder="Décrivez le contenu détaillé de chaque séance" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Méthodes pédagogiques:</label>
                        <input type="text" name="methodes" class="form-control" placeholder="Ex: Cours magistral, Travaux dirigés, Étude de cas" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Moyens pédagogiques:</label>
                        <input type="text" name="moyens" class="form-control" placeholder="Ex: Tableau, Vidéo projecteur, Documents" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Modalités d'évaluation:</label>
                        <textarea name="evaluation" class="form-control" rows="3" placeholder="Décrivez les méthodes d'évaluation prévues" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Références bibliographiques:</label>
                        <textarea name="references" class="form-control" rows="4" placeholder="Listez les ouvrages et documents de référence" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Transmission:</label>
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
                        <button type="submit" name="submit_plan" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Soumettre le plan</button>
                        <button type="reset" class="btn btn-secondary"><i class="fas fa-undo"></i> Réinitialiser</button>
                    </div>
                </form>
            </div>
            
            <div class="teacher-table">
                <h3 class="form-title">Mes Plans de Cours Soumis</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Cours</th>
                            <th>Date de soumission</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($plans) > 0): ?>
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
                                    <button class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.9rem;"><i class="fas fa-eye"></i> Voir</button>
                                    <?php if ($row['statut'] == 'Rejeté'): ?>
                                        <button class="btn btn-warning" style="padding: 5px 10px; font-size: 0.9rem;"><i class="fas fa-edit"></i> Modifier</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Vous n'avez pas encore soumis de plan de cours.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="teacher-section">
                <h3 class="section-title">Consignes importantes</h3>
                <ul>
                    <li>✅ Le plan de cours doit être soumis au moins 3 jours avant le début du cours</li>
                    <li>✅ Tout plan rejeté doit être corrigé et resoumis dans un délai de 48h</li>
                    <li>✅ Les modifications substantielles du plan doivent être signalées à l'avance</li>
                    <li>✅ Le non-respect de ces consignes peut entraîner le rejet de votre prestation</li>
                </ul>
            </div>
            
            <div class="teacher-actions">
                <a href="index_ens.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour au tableau de bord</a>
            </div>
        </main>
    </div>

    <footer class="teacher-footer">
        <p>&copy; 2025 Système de Suivi de Prestation - Tous droits réservés</p>
    </footer>
</body>
</html>