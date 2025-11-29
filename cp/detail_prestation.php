<?php
session_start();

// Vérifier si l'utilisateur est connecté et s'il est CP
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Chefpromotion') {
    header("Location: ../login.php");
    exit();
}

include("../script/config.php");

// Vérifier si un ID de fiche est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: prestation.php");
    exit();
}

$fiche_id = $_GET['id'];

// Récupérer les détails de la fiche de prestation
try {
    // Informations principales de la fiche
    $sql_fiche = "SELECT ef.*, 
                         c.nomComplet as cours_nom,
                         c.nbreHeure as cours_heures,
                         e.nom as enseignant_nom, 
                         e.postnom as enseignant_postnom, 
                         e.prenom as enseignant_prenom,
                         e.grade as enseignant_grade,
                         s.nomComplet as section_nom,
                         m.nomComplet as mention_nom,
                         p.nomComplet as promotion_nom
                  FROM entetefiche ef 
                  JOIN cours c ON ef.code_cours = c.code_cours 
                  JOIN enseignant e ON ef.matricule_enseignant = e.matriculeEnseignant
                  JOIN section s ON ef.code_section = s.code_section
                  JOIN mention m ON ef.code_mention = m.code_mention
                  JOIN promotion p ON ef.code_promotion = p.sigle_promotion
                  WHERE ef.id = ?";
    $stmt_fiche = $pdo->prepare($sql_fiche);
    $stmt_fiche->execute([$fiche_id]);
    $fiche = $stmt_fiche->fetch();
    
    if (!$fiche) {
        header("Location: prestation.php?error=Fiche non trouvée");
        exit();
    }
    
    // Détails de la prestation (contenufiche)
    $sql_details = "SELECT * FROM contenufiche WHERE identetefiche = ? ORDER BY datejoure ASC";
    $stmt_details = $pdo->prepare($sql_details);
    $stmt_details->execute([$fiche_id]);
    $details = $stmt_details->fetchAll();
    
} catch (Exception $e) {
    $error_message = "Erreur lors du chargement de la fiche de prestation: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Fiche de Prestation - CP</title>
    <link rel="stylesheet" href="cp_enhanced_user_experience.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .detail-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 25px;
        }
        
        .detail-header {
            border-bottom: 2px solid #3498db;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .detail-header h3 {
            color: #2c3e50;
            margin: 0;
        }
        
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .detail-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }
        
        .detail-label {
            font-weight: bold;
            color: #7f8c8d;
            font-size: 0.9em;
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-size: 1.1em;
            color: #2c3e50;
        }
        
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .detail-table th {
            background: #3498db;
            color: white;
            padding: 12px;
            text-align: left;
        }
        
        .detail-table td {
            padding: 12px;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .detail-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .detail-table tr:hover {
            background: #e8f4fc;
        }
        
        .stats-card {
            display: flex;
            justify-content: space-around;
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #3498db, #2c3e50);
            color: white;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .stat-item h4 {
            margin: 0 0 10px 0;
            font-size: 1.2em;
        }
        
        .stat-item .value {
            font-size: 2em;
            font-weight: bold;
        }
        
        @media print {
            .no-print {
                display: none;
            }
            
            body {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="cp-header">
        <h1><i class="fas fa-file-invoice"></i> Détails de la Fiche de Prestation</h1>
        <div class="header-actions">
            <a href="prestation.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> <span>Retour</span></a>
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
                    <li><a href="rapports.php"><i class="fas fa-chart-bar"></i> <span>Rapports</span></a></li>
                    <li><a href="../print/ficheprestation.php" target="_blank"><i class="fas fa-print"></i> <span>Imprimer Rapports</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="cp-main">
            <div class="content-header">
                <h2><i class="fas fa-info-circle"></i> Détails de la Fiche #<?php echo htmlspecialchars($fiche['id']); ?></h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Tableau de bord</a></li>
                    <li><a href="prestation.php">Gestion Prestations</a></li>
                    <li>Détails Fiche #<?php echo htmlspecialchars($fiche['id']); ?></li>
                </ul>
            </div>

            <?php if (!empty($error_message)): ?>
                <div class="cp-alert cp-alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <!-- Informations générales -->
            <div class="detail-card">
                <div class="detail-header">
                    <h3><i class="fas fa-file-alt"></i> Informations Générales</h3>
                </div>
                
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Cours</div>
                        <div class="detail-value"><?php echo htmlspecialchars($fiche['cours_nom']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Enseignant</div>
                        <div class="detail-value"><?php echo htmlspecialchars($fiche['enseignant_grade'] . ' ' . $fiche['enseignant_nom'] . ' ' . $fiche['enseignant_postnom'] . ' ' . $fiche['enseignant_prenom']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Section</div>
                        <div class="detail-value"><?php echo htmlspecialchars($fiche['section_nom']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Mention</div>
                        <div class="detail-value"><?php echo htmlspecialchars($fiche['mention_nom']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Promotion</div>
                        <div class="detail-value"><?php echo htmlspecialchars($fiche['promotion_nom']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Date de création</div>
                        <div class="detail-value"><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($fiche['datecreation']))); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Statut</div>
                        <div class="detail-value">
                            <?php if ($fiche['statut'] == 'approuvé'): ?>
                                <span class="status-badge status-approved"><i class="fas fa-check"></i> Approuvé</span>
                            <?php elseif ($fiche['statut'] == 'envoyé'): ?>
                                <span class="status-badge status-sent"><i class="fas fa-paper-plane"></i> Envoyé</span>
                            <?php else: ?>
                                <span class="status-badge status-pending"><i class="fas fa-clock"></i> En attente</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($fiche['volume_horaire_prevu'])): ?>
                    <div class="detail-item">
                        <div class="detail-label">Volume horaire prévu</div>
                        <div class="detail-value"><?php echo htmlspecialchars($fiche['volume_horaire_prevu']); ?> heures</div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($fiche['heures_reelles_prestees'])): ?>
                    <div class="detail-item">
                        <div class="detail-label">Heures réellement prestées</div>
                        <div class="detail-value"><?php echo htmlspecialchars($fiche['heures_reelles_prestees']); ?> heures</div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($fiche['description'])): ?>
                <div class="detail-item">
                    <div class="detail-label">Description</div>
                    <div class="detail-value"><?php echo htmlspecialchars($fiche['description']); ?></div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Statistiques -->
            <div class="stats-card">
                <div class="stat-item">
                    <h4>Total Heures</h4>
                    <div class="value">
                        <?php 
                        $total_heures = array_sum(array_column($details, 'nbreH'));
                        echo $total_heures;
                        ?>
                    </div>
                    <div>heures</div>
                </div>
                
                <div class="stat-item">
                    <h4>Nombre de Séances</h4>
                    <div class="value"><?php echo count($details); ?></div>
                    <div>séances</div>
                </div>
                
                <div class="stat-item">
                    <h4>Volume Cours</h4>
                    <div class="value"><?php echo htmlspecialchars($fiche['cours_heures']); ?></div>
                    <div>heures prévues</div>
                </div>
                
                <div class="stat-item">
                    <h4>% Réalisation</h4>
                    <div class="value">
                        <?php 
                        $total_heures = array_sum(array_column($details, 'nbreH'));
                        $pourcentage = ($fiche['cours_heures'] > 0) ? round(($total_heures / $fiche['cours_heures']) * 100, 1) : 0;
                        echo $pourcentage;
                        ?>%
                    </div>
                    <div>du programme</div>
                </div>
            </div>
            
            <!-- Détails des prestations -->
            <div class="detail-card">
                <div class="detail-header">
                    <h3><i class="fas fa-list"></i> Détails des Prestations</h3>
                </div>
                
                <?php if (count($details) > 0): ?>
                    <table class="detail-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Contenu du cours</th>
                                <th>Heure Entrée</th>
                                <th>Heure Sortie</th>
                                <th>Nbre H</th>
                                <th>Signature CP</th>
                                <th>Signature Enseignant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_heures_cours = 0;
                            foreach ($details as $detail): 
                                $total_heures_cours += $detail['nbreH'];
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($detail['datejoure']))); ?></td>
                                <td><?php echo htmlspecialchars($detail['contenu']); ?></td>
                                <td><?php echo htmlspecialchars($detail['heureEntree']); ?></td>
                                <td><?php echo htmlspecialchars($detail['heureSortie']); ?></td>
                                <td><?php echo htmlspecialchars($detail['nbreH']); ?></td>
                                <td><?php echo htmlspecialchars($detail['signatureCP']); ?></td>
                                <td><?php echo htmlspecialchars($detail['signatureEnseignant']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <tr style="background-color: #e9f7fe; font-weight: bold;">
                                <td colspan="4" style="text-align: right;">Total heures :</td>
                                <td><?php echo $total_heures_cours; ?></td>
                                <td colspan="2"></td>
                            </tr>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="cp-alert cp-alert-info">
                        <i class="fas fa-info-circle"></i> Aucun détail de prestation enregistré pour cette fiche.
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Actions -->
            <div class="cp-actions no-print">
                <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Imprimer</button>
                <a href="prestation.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
            </div>
        </main>
    </div>

    <footer class="cp-footer">
        <p>&copy; 2025 Système de Suivi de Prestation - Tous droits réservés</p>
    </footer>
</body>
</html>