<?php
session_start();
if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'Academique' && $_SESSION['role'] != 'SGA')) {
    header("Location: ../login.php");
    exit();
}

include '../config/connexion.php';

// Messages d'alerte
$success_message = "";
$error_message = "";

// Validation d'une fiche de prestation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['validate_prestation'])) {
    try {
        $ficheId = $_POST['fiche_id'];
        
        // Commencer une transaction
        $pdo->beginTransaction();
        
        // Mettre à jour toutes les entrées de la fiche de prestation comme validées
        $sql = "UPDATE contenufiche SET signatureCP = 'OK' WHERE identetefiche = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ficheId]);
        
        // Mettre à jour le statut de l'en-tête de la fiche
        $sql2 = "UPDATE entetefiche SET statut = 'valide', date_approbation = NOW() WHERE id = ?";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute([$ficheId]);
        
        // Valider la transaction
        $pdo->commit();
        
        $success_message = "Fiche de prestation validée avec succès.";
    } catch (PDOException $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollback();
        $error_message = "Erreur lors de la validation : " . $e->getMessage();
    }
}

// Rejet d'une fiche de prestation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reject_prestation'])) {
    try {
        $ficheId = $_POST['fiche_id'];
        $motif = $_POST['motif'];
        
        // Commencer une transaction
        $pdo->beginTransaction();
        
        // Mettre à jour toutes les entrées de la fiche de prestation comme rejetées
        $sql = "UPDATE contenufiche SET signatureCP = 'REJETEE' WHERE identetefiche = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ficheId]);
        
        // Mettre à jour le statut de l'en-tête de la fiche
        $sql2 = "UPDATE entetefiche SET statut = 'rejete', date_approbation = NOW(), description = ? WHERE id = ?";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute([$motif, $ficheId]);
        
        // Valider la transaction
        $pdo->commit();
        
        $success_message = "Fiche de prestation rejetée avec succès.";
    } catch (PDOException $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollback();
        $error_message = "Erreur lors du rejet : " . $e->getMessage();
    }
}

// Récupérer les fiches de prestation à valider (en_attente)
try {
    $sql_pending = "SELECT ef.*, 
                           c.nomComplet as cours_nom,
                           e.nom as enseignant_nom, 
                           e.postnom as enseignant_postnom, 
                           e.prenom as enseignant_prenom
                  FROM entetefiche ef 
                  JOIN cours c ON ef.code_cours = c.code_cours 
                  JOIN enseignant e ON ef.matricule_enseignant = e.matriculeEnseignant
                  WHERE ef.statut = 'en_attente'
                  ORDER BY ef.datecreation DESC";
    $stmt_pending = $pdo->prepare($sql_pending);
    $stmt_pending->execute();
    $pending_fiches = $stmt_pending->fetchAll();
} catch (PDOException $e) {
    $error_message = "Erreur lors du chargement des fiches en attente : " . $e->getMessage();
}

// Récupérer l'historique des validations
try {
    $sql_history = "SELECT ef.*, 
                           c.nomComplet as cours_nom,
                           e.nom as enseignant_nom, 
                           e.postnom as enseignant_postnom, 
                           e.prenom as enseignant_prenom
                  FROM entetefiche ef 
                  JOIN cours c ON ef.code_cours = c.code_cours 
                  JOIN enseignant e ON ef.matricule_enseignant = e.matriculeEnseignant
                  WHERE ef.statut IN ('valide', 'rejete', 'envoye')
                  ORDER BY ef.date_approbation DESC";
    $stmt_history = $pdo->prepare($sql_history);
    $stmt_history->execute();
    $history_fiches = $stmt_history->fetchAll();
} catch (PDOException $e) {
    $error_message = "Erreur lors du chargement de l'historique : " . $e->getMessage();
}

// Récupérer les détails d'une fiche spécifique si demandé
$fiche_details = null;
if (isset($_GET['view_details']) && !empty($_GET['view_details'])) {
    try {
        $fiche_id = $_GET['view_details'];
        
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
        $fiche_details = $stmt_fiche->fetch();
        
        if ($fiche_details) {
            // Détails de la prestation (contenufiche)
            $sql_details = "SELECT * FROM contenufiche WHERE identetefiche = ? ORDER BY datejoure ASC";
            $stmt_details = $pdo->prepare($sql_details);
            $stmt_details->execute([$fiche_id]);
            $fiche_details['contenu'] = $stmt_details->fetchAll();
        }
    } catch (PDOException $e) {
        $error_message = "Erreur lors du chargement des détails de la fiche : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiches de Prestation - Académique</title>
    <link rel="stylesheet" href="academic_admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 800px;
            border-radius: 10px;
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="academic-header">
        <h1><i class="fas fa-graduation-cap"></i> Service Académique - Validation des Prestations</h1>
        <div class="header-actions">
            <div class="user-info">
                <div class="user-avatar"><?php echo substr($_SESSION['username'], 0, 1); ?></div>
                <span><?php echo $_SESSION['username']; ?> (SGA)</span>
            </div>
            <a href="../index.php"><i class="fas fa-home"></i> <span>Accueil</span></a>
            <a href="../script/logout.php"><i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span></a>
        </div>
    </header>

    <!-- Container -->
    <div class="academic-container">
        <!-- Sidebar -->
        <aside class="academic-sidebar">
            <div class="academic-sidebar-header">
                <img src="../image/logo.jpg" alt="Logo">
                <h2>Service Académique</h2>
                <p><?php echo $_SESSION['username']; ?></p>
            </div>
            <nav class="academic-nav-menu">
                <ul>
                    <li><a href="index.php"><i class="fas fa-home"></i> <span>Tableau de bord</span></a></li>
                    <li><a href="horaire.php"><i class="fas fa-clock"></i> <span>Gestion des Horaires</span></a></li>
                    <li><a href="chargehoraire.php"><i class="fas fa-hourglass-half"></i> <span>Charges Horaire</span></a></li>
                    <li><a href="academique_prestation.php" class="active"><i class="fas fa-file-invoice"></i> <span>Fiches de Prestation</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="academic-main">
            <div class="content-header">
                <h2><i class="fas fa-file-invoice"></i> Validation des Fiches de Prestation</h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Accueil</a></li>
                    <li>Fiches de Prestation</li>
                </ul>
            </div>

            <?php if (!empty($success_message)): ?>
                <div class="academic-alert academic-alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div><?php echo htmlspecialchars($success_message); ?></div>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="academic-alert academic-alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <div><?php echo htmlspecialchars($error_message); ?></div>
                </div>
            <?php endif; ?>

            <div class="academic-alert academic-alert-info">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>Processus de validation:</strong> 
                    Le SGA consulte les fiches de prestations complètement remplies, les valide puis les transmet à l'AB (Agent Budgétaire).
                </div>
            </div>

            <!-- Process Steps -->
            <div class="academic-section">
                <h3 class="section-title"><i class="fas fa-project-diagram"></i> Processus de Validation</h3>
                <div class="process-steps">
                    <div class="step completed">
                        <div class="step-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                        <div class="step-label">Remplissage par Enseignant</div>
                    </div>
                    <div class="step active">
                        <div class="step-icon"><i class="fas fa-user-check"></i></div>
                        <div class="step-label">Validation SGA</div>
                    </div>
                    <div class="step">
                        <div class="step-icon"><i class="fas fa-user-tie"></i></div>
                        <div class="step-label">Transmission AB</div>
                    </div>
                    <div class="step">
                        <div class="step-icon"><i class="fas fa-euro-sign"></i></div>
                        <div class="step-label">Traitement Budgétaire</div>
                    </div>
                </div>
            </div>

            <div class="academic-section">
                <h3 class="section-title"><i class="fas fa-tasks"></i> Fiches de Prestation à Valider</h3>
                <p>Voici la liste des fiches de prestation qui ont été complétées par les enseignants et sont en attente de votre validation.</p>
                
                <div class="academic-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Fiche</th>
                                <th>Date</th>
                                <th>Enseignant</th>
                                <th>Cours</th>
                                <th>Total Heures</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($pending_fiches) > 0): ?>
                                <?php foreach ($pending_fiches as $fiche): ?>
                                    <tr>
                                        <td>#<?php echo htmlspecialchars($fiche['id']); ?></td>
                                        <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($fiche['datecreation']))); ?></td>
                                        <td><?php echo htmlspecialchars($fiche['enseignant_nom'] . ' ' . $fiche['enseignant_postnom'] . ' ' . $fiche['enseignant_prenom']); ?></td>
                                        <td><?php echo htmlspecialchars($fiche['cours_nom']); ?></td>
                                        <td>
                                            <?php 
                                            // Calculer le total des heures
                                            try {
                                                $sql_total = "SELECT SUM(nbreH) as total FROM contenufiche WHERE identetefiche = ?";
                                                $stmt_total = $pdo->prepare($sql_total);
                                                $stmt_total->execute([$fiche['id']]);
                                                $total_heures = $stmt_total->fetch()['total'];
                                                echo $total_heures ? $total_heures . ' heures' : '0 heures';
                                            } catch (PDOException $e) {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </td>
                                        <td><span class="status-badge status-pending"><i class="fas fa-clock"></i> À valider</span></td>
                                        <td class="table-actions">
                                            <a href="?view_details=<?php echo $fiche['id']; ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Détails</a>
                                            <button class="btn btn-sm btn-success" onclick="validatePrestation(<?php echo $fiche['id']; ?>)"><i class="fas fa-check"></i> Valider</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">Aucune fiche de prestation en attente de validation.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if ($fiche_details): ?>
            <div class="academic-section">
                <h3 class="section-title"><i class="fas fa-check-double"></i> Détails de la Fiche de Prestation</h3>
                <div class="validation-card">
                    <div class="validation-card-header">
                        <h4 class="validation-card-title"><i class="fas fa-file-alt"></i> Fiche de Prestation #<?php echo htmlspecialchars($fiche_details['id']); ?></h4>
                        <span class="status-badge status-pending"><i class="fas fa-clock"></i> En attente validation</span>
                    </div>
                    
                    <div class="validation-details">
                        <div class="detail-item">
                            <div class="detail-label">Date de création</div>
                            <div class="detail-value"><?php echo htmlspecialchars(date('d F Y', strtotime($fiche_details['datecreation']))); ?></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Enseignant</div>
                            <div class="detail-value"><?php echo htmlspecialchars($fiche_details['enseignant_grade'] . ' ' . $fiche_details['enseignant_nom'] . ' ' . $fiche_details['enseignant_postnom'] . ' ' . $fiche_details['enseignant_prenom']); ?></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Cours</div>
                            <div class="detail-value"><?php echo htmlspecialchars($fiche_details['cours_nom']); ?></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Durée totale</div>
                            <div class="detail-value">
                                <?php 
                                $total_heures = array_sum(array_column($fiche_details['contenu'], 'nbreH'));
                                echo $total_heures . ' heures';
                                ?>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Section</div>
                            <div class="detail-value"><?php echo htmlspecialchars($fiche_details['section_nom']); ?></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Mention</div>
                            <div class="detail-value"><?php echo htmlspecialchars($fiche_details['mention_nom']); ?></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Promotion</div>
                            <div class="detail-value"><?php echo htmlspecialchars($fiche_details['promotion_nom']); ?></div>
                        </div>
                    </div>
                    
                    <div class="academic-section">
                        <h4><i class="fas fa-list"></i> Détail des activités</h4>
                        <div class="academic-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Contenu</th>
                                        <th>Heure début</th>
                                        <th>Heure fin</th>
                                        <th>Nb heures</th>
                                        <th>Signature CP</th>
                                        <th>Signature Ens.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($fiche_details['contenu']) > 0): ?>
                                        <?php foreach ($fiche_details['contenu'] as $detail): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($detail['datejoure']))); ?></td>
                                                <td><?php echo htmlspecialchars($detail['contenu']); ?></td>
                                                <td><?php echo htmlspecialchars($detail['heureEntree']); ?></td>
                                                <td><?php echo htmlspecialchars($detail['heureSortie']); ?></td>
                                                <td><?php echo htmlspecialchars($detail['nbreH']); ?></td>
                                                <td>
                                                    <?php if ($detail['signatureCP'] == 'OK'): ?>
                                                        <span class="status-badge status-approved"><i class="fas fa-check"></i> OK</span>
                                                    <?php elseif ($detail['signatureCP'] == 'REJETEE'): ?>
                                                        <span class="status-badge status-rejected"><i class="fas fa-times"></i> Rejetée</span>
                                                    <?php else: ?>
                                                        <span class="status-badge status-pending"><i class="fas fa-clock"></i> En attente</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($detail['signatureEnseignant'] == 'OK'): ?>
                                                        <span class="status-badge status-approved"><i class="fas fa-check"></i> OK</span>
                                                    <?php else: ?>
                                                        <span class="status-badge status-pending"><i class="fas fa-clock"></i> En attente</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr style="background-color: #e9f7fe; font-weight: bold;">
                                            <td colspan="4" style="text-align: right;">Total heures :</td>
                                            <td><?php echo $total_heures; ?></td>
                                            <td colspan="2"></td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Aucun détail de prestation enregistré pour cette fiche.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="validation-actions">
                        <button class="btn btn-danger btn-lg" onclick="rejectPrestation(<?php echo $fiche_details['id']; ?>)"><i class="fas fa-times"></i> Rejeter avec motif</button>
                        <button class="btn btn-success btn-lg" onclick="validatePrestation(<?php echo $fiche_details['id']; ?>)"><i class="fas fa-check"></i> Valider et Envoyer à l'AB</button>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="academic-section">
                <h3 class="section-title"><i class="fas fa-history"></i> Historique des validations</h3>
                <div class="academic-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Fiche</th>
                                <th>Date</th>
                                <th>Enseignant</th>
                                <th>Cours</th>
                                <th>Status</th>
                                <th>Date de validation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($history_fiches) > 0): ?>
                                <?php foreach ($history_fiches as $fiche): ?>
                                    <tr>
                                        <td>#<?php echo htmlspecialchars($fiche['id']); ?></td>
                                        <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($fiche['datecreation']))); ?></td>
                                        <td><?php echo htmlspecialchars($fiche['enseignant_nom'] . ' ' . $fiche['enseignant_postnom'] . ' ' . $fiche['enseignant_prenom']); ?></td>
                                        <td><?php echo htmlspecialchars($fiche['cours_nom']); ?></td>
                                        <td>
                                            <?php if ($fiche['statut'] == 'valide'): ?>
                                                <span class="status-badge status-valid"><i class="fas fa-check"></i> Validé</span>
                                            <?php elseif ($fiche['statut'] == 'rejete'): ?>
                                                <span class="status-badge status-rejected"><i class="fas fa-times"></i> Rejeté</span>
                                            <?php elseif ($fiche['statut'] == 'envoye'): ?>
                                                <span class="status-badge status-sent"><i class="fas fa-paper-plane"></i> Envoyé à l'AB</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($fiche['date_approbation'] ? date('d/m/Y', strtotime($fiche['date_approbation'])) : 'N/A'); ?></td>
                                        <td class="table-actions">
                                            <a href="?view_details=<?php echo $fiche['id']; ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Voir</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">Aucun historique de validation.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal pour la validation -->
    <div id="validateModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Confirmer la validation</h3>
            <p>Êtes-vous sûr de vouloir valider cette fiche de prestation et la transmettre à l'Agent Budgétaire ?</p>
            <form method="POST">
                <input type="hidden" name="fiche_id" id="validateFicheId">
                <button type="submit" name="validate_prestation" class="btn btn-success">Valider</button>
                <button type="button" class="btn btn-outline" id="cancelValidate">Annuler</button>
            </form>
        </div>
    </div>

    <!-- Modal pour le rejet -->
    <div id="rejectModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Rejeter la fiche de prestation</h3>
            <p>Veuillez indiquer le motif du rejet:</p>
            <form method="POST">
                <input type="hidden" name="fiche_id" id="rejectFicheId">
                <textarea name="motif" class="form-control" rows="4" placeholder="Motif du rejet..." required></textarea>
                <br>
                <button type="submit" name="reject_prestation" class="btn btn-danger">Rejeter</button>
                <button type="button" class="btn btn-outline" id="cancelReject">Annuler</button>
            </form>
        </div>
    </div>

    <footer class="academic-footer">
        <p>&copy; 2025 Système de Suivi de Prestation. Tous droits réservés.</p>
    </footer>

    <script>
        // Variables pour les modals
        var validateModal = document.getElementById("validateModal");
        var rejectModal = document.getElementById("rejectModal");
        
        // Fonction pour ouvrir le modal de validation
        function validatePrestation(id) {
            document.getElementById("validateFicheId").value = id;
            validateModal.style.display = "block";
        }
        
        // Fonction pour ouvrir le modal de rejet
        function rejectPrestation(id) {
            document.getElementById("rejectFicheId").value = id;
            rejectModal.style.display = "block";
        }
        
        // Fermer les modals
        var closeButtons = document.getElementsByClassName("close");
        for (var i = 0; i < closeButtons.length; i++) {
            closeButtons[i].onclick = function() {
                validateModal.style.display = "none";
                rejectModal.style.display = "none";
            }
        }
        
        // Boutons annuler
        document.getElementById("cancelValidate").onclick = function() {
            validateModal.style.display = "none";
        };
        
        document.getElementById("cancelReject").onclick = function() {
            rejectModal.style.display = "none";
        };
        
        // Fermer les modals en cliquant en dehors
        window.onclick = function(event) {
            if (event.target == validateModal) {
                validateModal.style.display = "none";
            }
            if (event.target == rejectModal) {
                rejectModal.style.display = "none";
            }
        }
    </script>
</body>
</html>