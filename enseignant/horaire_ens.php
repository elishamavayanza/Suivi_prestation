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
    <link rel="stylesheet" href="teacher_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="teacher-header">
        <h1><i class="fas fa-clock"></i> Mon Horaire</h1>
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
                    <li><a href="horaire_ens.php" class="active"><i class="fas fa-clock"></i> <span>Mon Horaire</span></a></li>
                    <li><a href="charge_horaire_ens.php"><i class="fas fa-hourglass-half"></i> <span>Ma Charge Horaire</span></a></li>
                    <li><a href="prestation_ens.php"><i class="fas fa-file-invoice"></i> <span>Fiche de Prestation</span></a></li>
                    <li><a href="description_ens.php"><i class="fas fa-book-open"></i> <span>Plan de Cours</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="teacher-main">
            <div class="content-header">
                <h2>Mon Horaire de Cours</h2>
                <ul class="breadcrumb">
                    <li><a href="index_ens.php">Tableau de bord</a></li>
                    <li>Mon Horaire</li>
                </ul>
            </div>

            <?php if(!empty($success_message)): ?>
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
                            <th>Jour & Heure</th>
                            <th>Promotion/Mention</th>
                            <th>Cours</th>
                            <th>Site</th>
                            <th>Observation</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($horaires) > 0): ?>
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
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Vous n'avez aucun horaire programmé pour le moment.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="teacher-actions">
                <a href="index_ens.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour au tableau de bord</a>
                <a href="charge_horaire_ens.php" class="btn btn-primary"><i class="fas fa-hourglass-half"></i> Voir ma charge horaire</a>
                <a href="prestation_ens.php" class="btn btn-warning"><i class="fas fa-file-invoice"></i> Remplir une fiche de prestation</a>
                <button class="btn btn-danger" onclick="window.print()"><i class="fas fa-print"></i> Imprimer mon horaire</button>
            </div>
            
            <div class="teacher-section">
                <h3 class="section-title">Aide et Support</h3>
                <p>Si vous constatez une erreur dans votre horaire, veuillez contacter votre chef de section ou le service de gestion académique.</p>
                <div class="teacher-actions">
                    <a href="#" class="btn btn-secondary"><i class="fas fa-envelope"></i> Contacter le chef de section</a>
                    <a href="#" class="btn btn-primary"><i class="fas fa-headset"></i> Contacter le SGA</a>
                </div>
            </div>
        </main>
    </div>

    <footer class="teacher-footer">
        <p>&copy; 2025 Système de Suivi de Prestation - Tous droits réservés</p>
    </footer>
</body>
</html>