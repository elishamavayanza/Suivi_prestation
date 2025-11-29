<?php
session_start();

// Vérifier si l'utilisateur est connecté et s'il est CP
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Chefpromotion') {
    header("Location: ../login.php");
    exit();
}

include("../script/config.php");

// Récupérer les données nécessaires pour le formulaire
try {
    // Récupérer les cours
    $sql_cours = "SELECT * FROM cours ORDER BY nomComplet";
    $stmt_cours = $pdo->prepare($sql_cours);
    $stmt_cours->execute();
    $cours = $stmt_cours->fetchAll();

    // Récupérer les enseignants
    $sql_enseignants = "SELECT * FROM users WHERE role='Enseignant' ORDER BY username";
    $stmt_enseignants = $pdo->prepare($sql_enseignants);
    $stmt_enseignants->execute();
    $enseignants = $stmt_enseignants->fetchAll();

    // Récupérer les sections
    $sql_sections = "SELECT * FROM section ORDER BY nom";
    $stmt_sections = $pdo->prepare($sql_sections);
    $stmt_sections->execute();
    $sections = $stmt_sections->fetchAll();

    // Récupérer les mentions
    $sql_mentions = "SELECT * FROM mention ORDER BY nom";
    $stmt_mentions = $pdo->prepare($sql_mentions);
    $stmt_mentions->execute();
    $mentions = $stmt_mentions->fetchAll();

    // Récupérer les promotions
    $sql_promotions = "SELECT * FROM promotion ORDER BY nom";
    $stmt_promotions = $pdo->prepare($sql_promotions);
    $stmt_promotions->execute();
    $promotions = $stmt_promotions->fetchAll();

} catch (Exception $e) {
    $error_message = "Erreur lors du chargement des données: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Prestation - CP</title>
    <link rel="stylesheet" href="cp_enhanced_user_experience.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="cp-header">
        <h1><i class="fas fa-file-invoice"></i> Formulaire de Prestation</h1>
        <div class="header-actions">
            <a href="index.php" class="btn btn-outline"><i class="fas fa-home"></i> <span>Tableau de bord</span></a>
            <a href="../script/logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span></a>
        </div>
    </header>

    <!-- Container -->
    <div class="cp-container">
        <!-- Sidebar -->
        <aside class="cp-sidebar">
            <div class="cp-sidebar-header">
                <img src="../image/logo.jpg" alt="Logo">
                <h2>Chef de Programme</h2>
                <p><?php echo $_SESSION['username']; ?></p>
            </div>
            <nav class="cp-nav-menu">
                <ul>
                    <li><a href="index.php"><i class="fas fa-home"></i> <span>Tableau de bord</span></a></li>
                    <li><a href="prestation.php" class="active"><i class="fas fa-file-invoice"></i> <span>Gestion Prestations</span></a></li>
                    <li><a href="horaire.php"><i class="fas fa-clock"></i> <span>Consulter Horaires</span></a></li>
                    <li><a href="#"><i class="fas fa-chart-bar"></i> <span>Rapports</span></a></li>
                    <li><a href="../print/ficheprestation.php" target="_blank"><i class="fas fa-print"></i> <span>Imprimer Rapports</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="cp-main">
            <div class="content-header">
                <h2><i class="fas fa-plus-circle"></i> Nouvelle Fiche de Prestation</h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Tableau de bord</a></li>
                    <li><a href="prestation.php">Gestion Prestations</a></li>
                    <li>Nouvelle Prestation</li>
                </ul>
            </div>

            <?php if (!empty($error_message)): ?>
                <div class="cp-alert cp-alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <!-- Formulaire de prestation -->
            <div class="cp-section">
                <h3 class="section-title"><i class="fas fa-file-alt"></i> Informations de la Fiche</h3>
                
                <form method="POST" action="traitement_prestation_cp.php" class="cp-form">
                    <div class="form-row">
                        <div class="form-col">
                            <label for="cours"><i class="fas fa-book"></i> Cours:</label>
                            <select name="cours" id="cours" class="form-control" required>
                                <option value="">Sélectionnez un cours</option>
                                <?php foreach ($cours as $c): ?>
                                    <option value="<?php echo htmlspecialchars($c['code_cours']); ?>">
                                        <?php echo htmlspecialchars($c['nomComplet']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-col">
                            <label for="enseignant"><i class="fas fa-chalkboard-teacher"></i> Enseignant:</label>
                            <select name="enseignant" id="enseignant" class="form-control" required>
                                <option value="">Sélectionnez un enseignant</option>
                                <?php foreach ($enseignants as $ens): ?>
                                    <option value="<?php echo htmlspecialchars($ens['username']); ?>">
                                        <?php echo htmlspecialchars($ens['username']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <label for="section"><i class="fas fa-layer-group"></i> Section:</label>
                            <select name="section" id="section" class="form-control" required>
                                <option value="">Sélectionnez une section</option>
                                <?php foreach ($sections as $sec): ?>
                                    <option value="<?php echo htmlspecialchars($sec['code']); ?>">
                                        <?php echo htmlspecialchars($sec['nom']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-col">
                            <label for="mention"><i class="fas fa-tags"></i> Mention:</label>
                            <select name="mention" id="mention" class="form-control" required>
                                <option value="">Sélectionnez une mention</option>
                                <?php foreach ($mentions as $men): ?>
                                    <option value="<?php echo htmlspecialchars($men['code']); ?>">
                                        <?php echo htmlspecialchars($men['nom']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <label for="promotion"><i class="fas fa-graduation-cap"></i> Promotion:</label>
                            <select name="promotion" id="promotion" class="form-control" required>
                                <option value="">Sélectionnez une promotion</option>
                                <?php foreach ($promotions as $promo): ?>
                                    <option value="<?php echo htmlspecialchars($promo['code']); ?>">
                                        <?php echo htmlspecialchars($promo['nom']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-col">
                            <label for="volume_horaire"><i class="fas fa-clock"></i> Volume horaire prévu:</label>
                            <input type="number" name="volume_horaire" id="volume_horaire" class="form-control" placeholder="Nombre d'heures prévues">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <label for="heures_reelles"><i class="fas fa-user-clock"></i> Heures réellement prestées:</label>
                            <input type="number" name="heures_reelles" id="heures_reelles" class="form-control" placeholder="Nombre d'heures effectuées">
                        </div>
                        
                        <div class="form-col">
                            <label for="description"><i class="fas fa-align-left"></i> Description:</label>
                            <textarea name="description" id="description" class="form-control" placeholder="Description de la prestation"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Créer la Fiche</button>
                        <a href="prestation.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Retour</a>
                    </div>
                </form>
            </div>
            
            <!-- Instructions -->
            <div class="cp-section">
                <h3 class="section-title"><i class="fas fa-info-circle"></i> Instructions</h3>
                <div class="procedure-steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h4>Remplir les informations</h4>
                            <p>Complétez tous les champs du formulaire avec les informations du cours et de l'enseignant.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h4>Créer la fiche</h4>
                            <p>Cliquez sur "Créer la Fiche" pour générer une nouvelle fiche de prestation.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h4>Compléter les détails</h4>
                            <p>Après la création, vous pourrez compléter les détails de la prestation dans la section "Gestion Prestations".</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <footer class="cp-footer">
        <p>&copy; 2025 Système de Suivi de Prestation - Tous droits réservés</p>
    </footer>
</body>
</html>