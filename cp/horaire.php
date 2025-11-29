<?php
session_start();

// Vérifier si l'utilisateur est connecté et s'il est CP
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Chefpromotion') {
    header("Location: ../login.php");
    exit();
}

include("../script/config.php");

// Messages d'alerte$success_message = "";
$error_message = "";

// Traitement des filtres
$date_debut = isset($_GET['date_debut']) ? $_GET['date_debut'] : '';
$date_fin = isset($_GET['date_fin']) ? $_GET['date_fin'] : '';
$promotion_filter = isset($_GET['promotion']) ? $_GET['promotion'] : '';
$enseignant_filter = isset($_GET['enseignant']) ? $_GET['enseignant'] : '';

// Récupérer tous les horaires avec filtres
try {
    $sql_horaires = "SELECT h.*, c.nomComplet as cours_nom, p.nomComplet as promotion_nom, m.nomComplet as mention_nom
                    FROM horaire h
                    JOIN cours c ON h.idcours = c.code_cours
                    JOIN promotion p ON h.codepromotion = p.sigle_promotion
                    JOIN mention m ON h.codemention = m.code_mention";
                    
    $params = [];
    
// Ajouter les conditions de filtre
    if (!empty($date_debut) || !empty($date_fin) || !empty($promotion_filter) || !empty($enseignant_filter)) {
        $conditions = [];
        
        if (!empty($date_debut)) {
            $conditions[] = "h.datejour >= ?";
            $params[] = $date_debut;
        }
        
        if (!empty($date_fin)) {
            $conditions[] = "h.datejour <= ?";
            $params[] = $date_fin;
        }
        
        if (!empty($promotion_filter)) {
            $conditions[] = "h.codepromotion = ?";
            $params[] = $promotion_filter;
        }
        
        if (!empty($enseignant_filter)) {
            $conditions[] = "h.enseignant = ?";
            $params[] = $enseignant_filter;
        }
        
        if (!empty($conditions)) {
            $sql_horaires .=" WHERE " . implode(" AND ", $conditions);
        }
    }
    
    $sql_horaires .= " ORDER BY h.datejour DESC, h.jourheure ASC";
    
    $stmt_horaires = $pdo->prepare($sql_horaires);
    $stmt_horaires->execute($params);
    $horaires = $stmt_horaires->fetchAll();
    
} catch (Exception $e) {
    $error_message = "Erreur lors du chargement des horaires: " . $e->getMessage();
}

// Récupérer les promotions pour le filtre
try {
    $sql_promotions = "SELECT * FROM promotion ORDER BY nomComplet";
    $stmt_promotions = $pdo->prepare($sql_promotions);
    $stmt_promotions->execute();
    $promotions = $stmt_promotions->fetchAll();
} catch (Exception $e) {
    $promotions = [];
}

// Récupérer les enseignants pour le filtre
try {
    $sql_enseignants = "SELECT DISTINCT enseignant FROM horaire WHERE enseignant IS NOT NULL AND enseignant != '' ORDER BY enseignant";
    $stmt_enseignants = $pdo->prepare($sql_enseignants);
    $stmt_enseignants->execute();
    $enseignants = $stmt_enseignants->fetchAll();
} catch (Exception $e) {
    $enseignants = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation des Horaires - CP</title>
    <link rel="stylesheet" href="cp_enhanced_user_experience.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="cp-header">
        <h1><i class="fas fa-clock"></i> Consultation des Horaires</h1>
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
                    <li><a href="prestation.php"><i class="fas fa-file-invoice"></i> <span>Gestion Prestations</span></a></li>
                    <li><a href="horaire.php" class="active"><i class="fas fa-clock"></i> <span>Consulter Horaires</span></a></li>
                    <li><a href="rapports.php"><i class="fas fa-chart-bar"></i> <span>Rapports</span></a></li>
                    <li><a href="../print/ficheprestation.php" target="_blank"><i class="fas fa-print"></i> <span>Imprimer Rapports</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="cp-main">
            <div class="content-header">
                <h2><i class="fas fa-calendar-alt"></i> Horaires des Cours</h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Tableau de bord</a></li>
                    <li>Consultation Horaires</li>
                </ul>
            </div>

            <?php if (!empty($success_message)): ?>
                <div class="cp-alert cp-alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="cp-alert cp-alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <!-- Filtres -->
            <div class="cp-section">
                <h3 class="section-title"><i class="fas fa-filter"></i> Filtrer les horaires</h3>
                <form method="GET" class="cp-form">
                    <div class="form-row">
                        <div class="form-col">
                            <label for="date_debut"><i class="fas fa-calendar-start"></i> Date de début:</label>
                            <input type="date" id="date_debut" name="date_debut" class="form-control" value="<?php echo htmlspecialchars($date_debut); ?>">
                        </div>
                        <div class="form-col">
                            <label for="date_fin"><i class="fas fa-calendar-end"></i> Date de fin:</label>
                            <input type="date" id="date_fin" name="date_fin" class="form-control" value="<?php echo htmlspecialchars($date_fin); ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <label for="promotion"><i class="fas fa-graduation-cap"></i> Promotion:</label>
                            <select id="promotion" name="promotion" class="form-control">
                                <option value="">Toutes les promotions</option>
                                <?php foreach ($promotions as $promo): ?>
                                    <option value="<?php echo htmlspecialchars($promo['sigle_promotion']); ?>" <?php echo ($promotion_filter == $promo['sigle_promotion']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($promo['nomComplet']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
</div>
                        <div class="form-col">
                            <label for="enseignant"><i class="fas fa-chalkboard-teacher"></i> Enseignant:</label>
                            <select id="enseignant" name="enseignant" class="form-control">
                                <option value="">Tous les enseignants</option>
                                <?php foreach ($enseignants as $ens): ?>
                                    <option value="<?php echo htmlspecialchars($ens['enseignant']); ?>" <?php echo ($enseignant_filter == $ens['enseignant']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($ens['enseignant']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrer</button>
                        <a href="horaire.php" class="btn btn-outline"><i class="fas fa-undo"></i> Réinitialiser</a>
                    </div>
                </form>
            </div>
            
            <!-- Liste des horaires -->
            <div class="cp-section">
                <h3 class="section-title"><i class="fas fa-list"></i> Planning des Cours</h3>
                
                <div class="cp-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Heure</th>
                                <th>Cours</th>
                                <th>Enseignant</th>
                                <th>Promotion</th>
                                <th>Mention</th>
                                <th>Site</th>
                                <th>Observations</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($horaires) > 0): ?>
                                <?php foreach ($horaires as $horaire): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($horaire['datejour'])); ?></td>
                                    <td><?php echo htmlspecialchars($horaire['jourheure']); ?></td>
                                    <td><?php echo htmlspecialchars($horaire['cours_nom']); ?></td>
                                    <td><?php echo htmlspecialchars($horaire['enseignant']); ?></td>
                                    <td><?php echo htmlspecialchars($horaire['promotion_nom']); ?></td>
                                    <td><?php echo htmlspecialchars($horaire['mention_nom']); ?></td>
                                    <td><?php echo htmlspecialchars($horaire['site']); ?></td>
                                    <td><?php echo htmlspecialchars($horaire['observation']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">Aucun horaire trouvé.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (count($horaires) > 0): ?>
                <div class="cp-actions">
                    <p><strong><?php echo count($horaires); ?></strong> créneau(x) trouvé(s)</p>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="cp-actions">
                <a href="index.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Retour au tableau de bord</a>
                <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Imprimer les horaires</button>
                <a href="../print/horaire.php" target="_blank" class="btn btn-success"><i class="fas fa-file-pdf"></i> Exporter en PDF</a>
            </div>
        </main>
    </div>

    <footer class="cp-footer">
        <p>&copy; 2025 Système de Suivi de Prestation - Tous droits réservés</p>
    </footer>
</body>
</html>