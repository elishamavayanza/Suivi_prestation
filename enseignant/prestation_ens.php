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
    <link rel="stylesheet" href="teacher_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="teacher-header">
        <h1><i class="fas fa-file-invoice"></i> Fiche de Prestation</h1>
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
                    <li><a href="prestation_ens.php" class="active"><i class="fas fa-file-invoice"></i> <span>Fiche de Prestation</span></a></li>
                    <li><a href="description_ens.php"><i class="fas fa-book-open"></i> <span>Plan de Cours</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="teacher-main">
            <div class="content-header">
                <h2>Remplir une Fiche de Prestation</h2>
                <ul class="breadcrumb">
                    <li><a href="index_ens.php">Tableau de bord</a></li>
                    <li>Fiche de Prestation</li>
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
                <h3 class="form-title">Nouvelle Fiche de Prestation</h3>
                <p>À la fin de chaque séance de cours, remplissez une fiche de prestation détaillée.</p>
                
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
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Date:</label>
                            <input type="date" name="date_creation" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Heure de début:</label>
                            <input type="time" name="heure_debut" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Heure de fin:</label>
                            <input type="time" name="heure_fin" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Objectif de la séance:</label>
                        <textarea name="objectif" class="form-control" rows="2" placeholder="Quel était l'objectif de cette séance ?" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Compétence visée:</label>
                        <textarea name="competence" class="form-control" rows="2" placeholder="Quelle compétence devait être acquise ?" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Méthodes utilisées:</label>
                        <input type="text" name="methodes" class="form-control" placeholder="Ex: Cours magistral, Travaux dirigés, Étude de cas" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Moyens utilisés:</label>
                        <input type="text" name="moyens" class="form-control" placeholder="Ex: Tableau, Vidéo projecteur, Documents" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Évaluation effectuée:</label>
                        <textarea name="evaluation" class="form-control" rows="2" placeholder="Décrivez l'évaluation réalisée durant la séance" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Contenu donné:</label>
                        <textarea name="contenu" class="form-control" rows="3" placeholder="Décrivez en détail le contenu enseigné durant cette séance" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Difficultés rencontrées:</label>
                        <textarea name="difficultes" class="form-control" rows="2" placeholder="Décrivez les difficultés rencontrées pendant la séance"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Mesures prises:</label>
                        <textarea name="mesures" class="form-control" rows="2" placeholder="Décrivez les mesures prises pour pallier aux difficultés"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Signature (Nom complet):</label>
                        <input type="text" name="signature" class="form-control" placeholder="Entrez votre nom complet" required>
                    </div>
                    
                    <div class="teacher-actions">
                        <button type="submit" name="submit_prestation" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Soumettre la fiche</button>
                        <button type="reset" class="btn btn-secondary"><i class="fas fa-undo"></i> Réinitialiser</button>
                    </div>
                </form>
            </div>
            
            <div class="teacher-table">
                <h3 class="form-title">Mes Dernières Fiches de Prestation</h3>
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
                        <?php if (count($fiches) > 0): ?>
                            <?php foreach ($fiches as $fiche): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fiche['cours_nom']); ?></td>
                                <td><?php echo htmlspecialchars($fiche['datecreation']); ?></td>
                                <td><?php echo htmlspecialchars($fiche['heure_debut'] . ' - ' . $fiche['heure_fin']); ?></td>
                                <td>
                                    <span style="color: #27ae60; font-weight: bold;">Soumis</span>
                                </td>
                                <td>
                                    <button class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.9rem;"><i class="fas fa-eye"></i> Voir</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Vous n'avez pas encore soumis de fiche de prestation.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="teacher-section">
                <h3 class="section-title">Instructions importantes</h3>
                <ul>
                    <li>✅ Chaque fiche de prestation doit être remplie immédiatement après la séance de cours</li>
                    <li>✅ La signature est obligatoire pour valider la fiche</li>
                    <li>✅ Les fiches incomplètes ne seront pas acceptées</li>
                    <li>✅ En cas de problème technique, contactez immédiatement le service informatique</li>
                </ul>
            </div>
            
            <div class="teacher-actions">
                <a href="index_ens.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour au tableau de bord</a>
                <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Imprimer cette page</button>
            </div>
        </main>
    </div>

    <footer class="teacher-footer">
        <p>&copy; 2025 Système de Suivi de Prestation - Tous droits réservés</p>
    </footer>
</body>
</html>