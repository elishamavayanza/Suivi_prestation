<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Chefdesection') {
    header("Location: ../login.php");
    exit();
}

include '../config/connexion.php';

// Récupérer les données depuis la base de données
$enseignants = [];
$cours = [];
$promotions = [];
$charges = [];

try {
    // Récupérer les enseignants
    $stmt = $pdo->prepare("SELECT matriculeEnseignant, nom, postnom, prenom FROM enseignant ORDER BY nom");
    $stmt->execute();
    $enseignants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les cours
    $stmt = $pdo->prepare("SELECT id, code_cours, nomComplet FROM cours ORDER BY nomComplet");
    $stmt->execute();
    $cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les promotions
    $stmt = $pdo->prepare("SELECT id, sigle_promotion, nomComplet FROM promotion ORDER BY nomComplet");
    $stmt->execute();
    $promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les charges horaires existantes avec les détails
    $stmt = $pdo->prepare("
        SELECT ch.id, ch.observation, 
               e.nom AS enseignant_nom, e.postnom AS enseignant_postnom, e.prenom AS enseignant_prenom,
               c.nomComplet AS cours_nom,
               p.nomComplet AS promotion_nom
        FROM chargehoraire ch
        JOIN enseignant e ON ch.matricule = e.matriculeEnseignant
        JOIN cours c ON ch.codecours = c.code_cours
        JOIN promotion p ON ch.codepromotion = p.sigle_promotion
        ORDER BY e.nom
    ");
    $stmt->execute();
    $charges = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Erreur lors de la récupération des données : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charges Horaire - Chef de Section</title>
    <link rel="stylesheet" href="chef_section_cp_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="section-chief-header">
        <h1><i class="fas fa-hourglass-half"></i> Charges Horaire des Enseignants</h1>
        <div class="header-actions">
            <a href="../index.php"><i class="fas fa-home"></i> <span>Accueil</span></a>
            <a href="../script/logout.php"><i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span></a>
        </div>
    </header>

    <!-- Container -->
    <div class="section-chief-container">
        <!-- Sidebar -->
        <aside class="section-chief-sidebar">
            <div class="section-chief-sidebar-header">
                <img src="../image/logo.jpg" alt="Logo">
                <h2>Chef de Section</h2>
                <p><?php echo $_SESSION['username']; ?></p>
            </div>
            <nav class="section-chief-nav-menu">
                <ul>
                    <li><a href="index.php"><i class="fas fa-home"></i> <span>Tableau de bord</span></a></li>
                    <li><a href="horaire.php"><i class="fas fa-clock"></i> <span>Gestion des Horaires</span></a></li>
                    <li><a href="chargehoraire.php" class="active"><i class="fas fa-hourglass-half"></i> <span>Charges Horaire</span></a></li>
                    <li><a href="prestation.php"><i class="fas fa-file-invoice"></i> <span>Fiches de Prestation</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="section-chief-main">
            <div class="content-header">
                <h2>Charges Horaire</h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Accueil</a></li>
                    <li>Charges Horaire</li>
                </ul>
            </div>

            <div class="section-chief-section">
                <h3 class="section-title"><i class="fas fa-plus-circle"></i> Elaboration des Charges Horaire</h3>
                <p>En tant que chef de section, vous pouvez élaborer et gérer les charges horaire des enseignants.</p>
                
                <div class="section-chief-actions">
                    <button class="btn btn-primary" onclick="showForm()"><i class="fas fa-plus-circle"></i> Nouvelle Charge Horaire</button>
                    <button class="btn btn-success" onclick="showForm()"><i class="fas fa-user-plus"></i> Affecter un Enseignant</button>
                    <button class="btn btn-warning" onclick="showForm()"><i class="fas fa-sync-alt"></i> Réviser les Charges</button>
                </div>

                <div class="section-chief-form" id="chargeForm" style="display:none;">
                    <h3 class="form-title">Formulaire de Charge Horaire</h3>
                    <form id="chargeHoraireForm" method="POST" action="scripts/add_charge_horaire.php">
                        <input type="hidden" id="chargeId" name="chargeId" value="">
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="enseignant" class="form-label">Enseignant</label>
                                    <select id="enseignant" name="enseignant" class="form-control" required>
                                        <option value="">Sélectionnez un enseignant</option>
                                        <?php foreach ($enseignants as $enseignant): ?>
                                            <option value="<?php echo htmlspecialchars($enseignant['matriculeEnseignant']); ?>">
                                                <?php echo htmlspecialchars($enseignant['nom'] . ' ' . $enseignant['postnom'] . ' ' . $enseignant['prenom']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="cours" class="form-label">Cours</label>
                                    <select id="cours" name="cours" class="form-control" required>
                                        <option value="">Sélectionnez un cours</option>
                                        <?php foreach ($cours as $cour): ?>
                                            <option value="<?php echo htmlspecialchars($cour['code_cours']); ?>">
                                                <?php echo htmlspecialchars($cour['nomComplet']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="promotion" class="form-label">Promotion</label>
                                    <select id="promotion" name="promotion" class="form-control" required>
                                        <option value="">Sélectionnez une promotion</option>
                                        <?php foreach ($promotions as $promo): ?>
                                            <option value="<?php echo htmlspecialchars($promo['sigle_promotion']); ?>">
                                                <?php echo htmlspecialchars($promo['nomComplet']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="observation" class="form-label">Observation</label>
                                    <input type="text" id="observation" name="observation" class="form-control" placeholder="Entrez une observation">
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer la Charge</button>
                        <button type="button" class="btn btn-secondary" onclick="hideForm()">Annuler</button>
                    </form>
                </div>
            </div>

            <div class="section-chief-section">
                <h3 class="section-title"><i class="fas fa-list"></i> Charges Horaire Actuelles</h3>
                <p>Liste des charges horaire attribuées aux enseignants.</p>
                
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <div class="section-chief-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Enseignant</th>
                                <th>Cours</th>
                                <th>Promotion</th>
                                <th>Observation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($charges)): ?>
                                <tr>
                                    <td colspan="5" class="text-center">Aucune charge horaire enregistrée</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($charges as $charge): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($charge['enseignant_nom'] . ' ' . $charge['enseignant_postnom'] . ' ' . $charge['enseignant_prenom']); ?></td>
                                        <td><?php echo htmlspecialchars($charge['cours_nom']); ?></td>
                                        <td><?php echo htmlspecialchars($charge['promotion_nom']); ?></td>
                                        <td><?php echo htmlspecialchars($charge['observation']); ?></td>
                                        <td class="table-actions">
                                            <button class="btn btn-sm btn-success" onclick="editCharge(<?php echo $charge['id']; ?>, '<?php echo $charge['matricule']; ?>', '<?php echo $charge['codecours']; ?>', '<?php echo $charge['codepromotion']; ?>', '<?php echo htmlspecialchars($charge['observation'], ENT_QUOTES); ?>')">
                                                <i class="fas fa-edit"></i> Modifier
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteCharge(<?php echo $charge['id']; ?>)">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <footer class="section-chief-footer">
        <p>&copy; 2025 Système de Suivi de Prestation. Tous droits réservés.</p>
    </footer>

    <script>
        function showForm() {
            document.getElementById('chargeForm').style.display = 'block';
            document.getElementById('chargeHoraireForm').reset();
            document.getElementById('chargeId').value = '';
        }

        function hideForm() {
            document.getElementById('chargeForm').style.display = 'none';
        }

        function editCharge(id, matricule, codecours, codepromotion, observation) {
            showForm();
            document.getElementById('chargeId').value = id;
            document.getElementById('enseignant').value = matricule;
            document.getElementById('cours').value = codecours;
            document.getElementById('promotion').value = codepromotion;
            document.getElementById('observation').value = observation;
        }

        function deleteCharge(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette charge horaire ?')) {
                // Créer un formulaire dynamiquement pour la suppression
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'scripts/delete_charge_horaire.php';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'id';
                input.value = id;
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Afficher le formulaire si une erreur ou un message de succès est présent
        window.onload = function() {
            <?php if (isset($_GET['success']) || isset($_GET['error'])): ?>
                showForm();
            <?php endif; ?>
        };
    </script>
</body>
</html>