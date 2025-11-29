<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Chefdesection') {
    header("Location: ../login.php");
    exit();
}

include '../config/connexion.php';

// Récupérer les horaires depuis la base de données
$horaires = [];
try {
    $stmt = $pdo->query("SELECT * FROM horaire ORDER BY idhoraire");
    $horaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des horaires : " . $e->getMessage();
}

// Récupérer les salles uniques depuis la base de données
$salles = [];
try {
    $stmt = $pdo->query("SELECT DISTINCT site AS nom_salle FROM horaire WHERE site IS NOT NULL AND site != ''");
    $salles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des salles : " . $e->getMessage();
}

// Récupérer les cours pour le dropdown
$cours_list = [];
try {
    $stmt = $pdo->query("SELECT code_cours, nomComplet FROM cours ORDER BY code_cours");
    $cours_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des cours : " . $e->getMessage();
}

// Récupérer les mentions pour le dropdown
$mentions_list = [];
try {
    $stmt = $pdo->query("SELECT code_mention, nomComplet FROM mention ORDER BY code_mention");
    $mentions_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des mentions : " . $e->getMessage();
}

// Récupérer les promotions pour le dropdown
$promotions_list = [];
try {
    $stmt = $pdo->query("SELECT sigle_promotion, nomComplet FROM promotion ORDER BY sigle_promotion");
    $promotions_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des promotions : " . $e->getMessage();
}

// Récupérer les enseignants pour le dropdown
$enseignants_list = [];
try {
    $stmt = $pdo->query("SELECT matriculeEnseignant, CONCAT(nom, ' ', postnom, ' ', prenom) as nom_complet FROM enseignant ORDER BY nom");
    $enseignants_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des enseignants : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Horaires - Chef de Section</title>
    <link rel="stylesheet" href="chef_section_cp_style.css">
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
            background-color: rgba(0,0,0,0.4);
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
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
            cursor: pointer;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        
        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        
        .form-group select {
            height: 40px;
        }
        
        .form-actions {
            text-align: right;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .btn-modal {
            padding: 10px 15px;
            margin-left: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-primary-modal {
            background-color: #007bff;
            color: white;
        }
        
        .btn-primary-modal:hover {
            background-color: #0056b3;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #545b62;
        }
        
        .modal-header {
            margin-top: 0;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
            color: #333;
        }
        
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        
        .form-col {
            flex: 1;
            padding: 0 10px;
            min-width: 250px;
        }
        
        @media (max-width: 768px) {
            .form-col {
                min-width: 100%;
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="section-chief-header">
        <h1><i class="fas fa-clock"></i> Gestion des Horaires</h1>
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
                    <li><a href="horaire.php" class="active"><i class="fas fa-clock"></i> <span>Gestion des Horaires</span></a></li>
                    <li><a href="chargehoraire.php"><i class="fas fa-hourglass-half"></i> <span>Charges Horaire</span></a></li>
                    <li><a href="prestation.php"><i class="fas fa-file-invoice"></i> <span>Fiches de Prestation</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="section-chief-main">
            <div class="content-header">
                <h2>Gestion des Horaires</h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Accueil</a></li>
                    <li>Gestion des Horaires</li>
                </ul>
            </div>

            <?php if (isset($_GET['message'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_GET['message']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <div class="section-chief-section">
                <h3 class="section-title"><i class="fas fa-calendar-alt"></i> Emploi du Temps</h3>
                <p>En tant que chef de section, vous pouvez consulter et gérer les emplois du temps des enseignants.</p>
                
                <div class="section-chief-actions">
                    <button class="btn btn-primary" onclick="openAddHoraireModal()"><i class="fas fa-plus-circle"></i> Créer un nouvel horaire</button>
                    <button class="btn btn-success" onclick="openEditHoraireModal()"><i class="fas fa-edit"></i> Modifier un horaire</button>
                    <button class="btn btn-danger" onclick="deleteHoraire()"><i class="fas fa-trash"></i> Supprimer un horaire</button>
                </div>

                <div class="section-chief-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Code Cours</th>
                                <th>Jour/Heure</th>
                                <th>Mention</th>
                                <th>Promotion</th>
                                <th>Enseignant</th>
                                <th>Site</th>
                                <th>Période</th>
                                <th>Observation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($horaires)): ?>
                                <tr>
                                    <td colspan="9" class="text-center">Aucun horaire trouvé</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($horaires as $horaire): ?>
                                    <tr data-id="<?php echo $horaire['idhoraire']; ?>">
                                        <td><?php echo htmlspecialchars($horaire['idhoraire']); ?></td>
                                        <td><?php echo htmlspecialchars($horaire['idcours']); ?></td>
                                        <td><?php echo htmlspecialchars($horaire['jourheure']); ?></td>
                                        <td><?php echo htmlspecialchars($horaire['codemention']); ?></td>
                                        <td><?php echo htmlspecialchars($horaire['codepromotion']); ?></td>
                                        <td><?php echo htmlspecialchars($horaire['enseignant']); ?></td>
                                        <td><?php echo htmlspecialchars($horaire['site']); ?></td>
                                        <td><?php echo htmlspecialchars($horaire['periode']); ?></td>
                                        <td><?php echo htmlspecialchars($horaire['observation']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="section-chief-section">
                <h3 class="section-title"><i class="fas fa-door-open"></i> Gestion des Salles</h3>
                <p>Attribution des salles aux différents cours.</p>
                
                <div class="section-chief-actions">
                    <button class="btn btn-primary" onclick="openAddSalleModal()"><i class="fas fa-plus-circle"></i> Ajouter une salle</button>
                    <button class="btn btn-success" onclick="openEditSalleModal()"><i class="fas fa-edit"></i> Modifier une salle</button>
                </div>
                
                <div class="section-chief-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Nom de la Salle</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($salles)): ?>
                                <tr>
                                    <td colspan="2" class="text-center">Aucune salle trouvée</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($salles as $salle): ?>
                                    <tr data-salle="<?php echo htmlspecialchars($salle['nom_salle']); ?>">
                                        <td><?php echo htmlspecialchars($salle['nom_salle']); ?></td>
                                        <td class="table-actions">
                                            <button class="btn btn-sm btn-success" onclick="openEditSalleModal('<?php echo htmlspecialchars($salle['nom_salle']); ?>')"><i class="fas fa-edit"></i> Modifier</button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteSalle('<?php echo htmlspecialchars($salle['nom_salle']); ?>')"><i class="fas fa-trash"></i> Supprimer</button>
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

    <!-- Modal pour ajouter un horaire -->
    <div id="addHoraireModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addHoraireModal')">&times;</span>
            <h2 class="modal-header">Ajouter un nouvel horaire</h2>
            <form action="scripts/add_horaire.php" method="POST">
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="idcours">Code Cours:</label>
                            <select id="idcours" name="idcours" required>
                                <option value="">Sélectionner un cours</option>
                                <?php foreach ($cours_list as $cours): ?>
                                    <option value="<?php echo htmlspecialchars($cours['code_cours']); ?>">
                                        <?php echo htmlspecialchars($cours['code_cours'] . ' - ' . $cours['nomComplet']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="jourheure">Jour/Heure:</label>
                            <input type="text" id="jourheure" name="jourheure" placeholder="Ex: Lundi - Mardi 08:00-10:00" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="codemention">Code Mention:</label>
                            <select id="codemention" name="codemention" required>
                                <option value="">Sélectionner une mention</option>
                                <?php foreach ($mentions_list as $mention): ?>
                                    <option value="<?php echo htmlspecialchars($mention['code_mention']); ?>">
                                        <?php echo htmlspecialchars($mention['code_mention'] . ' - ' . $mention['nomComplet']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="codepromotion">Code Promotion:</label>
                            <select id="codepromotion" name="codepromotion" required>
                                <option value="">Sélectionner une promotion</option>
                                <?php foreach ($promotions_list as $promotion): ?>
                                    <option value="<?php echo htmlspecialchars($promotion['sigle_promotion']); ?>">
                                        <?php echo htmlspecialchars($promotion['sigle_promotion'] . ' - ' . $promotion['nomComplet']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="enseignant">Enseignant:</label>
                            <select id="enseignant" name="enseignant" required>
                                <option value="">Sélectionner un enseignant</option>
                                <?php foreach ($enseignants_list as $enseignant): ?>
                                    <option value="<?php echo htmlspecialchars($enseignant['matriculeEnseignant']); ?>">
                                        <?php echo htmlspecialchars($enseignant['matriculeEnseignant'] . ' - ' . $enseignant['nom_complet']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="site">Site/Salle:</label>
                            <input type="text" id="site" name="site">
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="periode">Période:</label>
                            <select id="periode" name="periode" required>
                                <option value="">Sélectionner une période</option>
                                <option value="AM">AM (Matin)</option>
                                <option value="PM">PM (Après-midi)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="observation">Observation:</label>
                            <textarea id="observation" name="observation" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-modal btn-secondary" onclick="closeModal('addHoraireModal')">Annuler</button>
                    <button type="submit" class="btn-modal btn-primary-modal">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal pour modifier un horaire -->
    <div id="editHoraireModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editHoraireModal')">&times;</span>
            <h2 class="modal-header">Modifier un horaire</h2>
            <form action="scripts/update_horaire.php" method="POST">
                <input type="hidden" id="edit_id" name="id">
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="edit_idcours">Code Cours:</label>
                            <select id="edit_idcours" name="idcours" required>
                                <option value="">Sélectionner un cours</option>
                                <?php foreach ($cours_list as $cours): ?>
                                    <option value="<?php echo htmlspecialchars($cours['code_cours']); ?>">
                                        <?php echo htmlspecialchars($cours['code_cours'] . ' - ' . $cours['nomComplet']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="edit_jourheure">Jour/Heure:</label>
                            <input type="text" id="edit_jourheure" name="jourheure" placeholder="Ex: Lundi - Mardi 08:00-10:00" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="edit_codemention">Code Mention:</label>
                            <select id="edit_codemention" name="codemention" required>
                                <option value="">Sélectionner une mention</option>
                                <?php foreach ($mentions_list as $mention): ?>
                                    <option value="<?php echo htmlspecialchars($mention['code_mention']); ?>">
                                        <?php echo htmlspecialchars($mention['code_mention'] . ' - ' . $mention['nomComplet']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="edit_codepromotion">Code Promotion:</label>
                            <select id="edit_codepromotion" name="codepromotion" required>
                                <option value="">Sélectionner une promotion</option>
                                <?php foreach ($promotions_list as $promotion): ?>
                                    <option value="<?php echo htmlspecialchars($promotion['sigle_promotion']); ?>">
                                        <?php echo htmlspecialchars($promotion['sigle_promotion'] . ' - ' . $promotion['nomComplet']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="edit_enseignant">Enseignant:</label>
                            <select id="edit_enseignant" name="enseignant" required>
                                <option value="">Sélectionner un enseignant</option>
                                <?php foreach ($enseignants_list as $enseignant): ?>
                                    <option value="<?php echo htmlspecialchars($enseignant['matriculeEnseignant']); ?>">
                                        <?php echo htmlspecialchars($enseignant['matriculeEnseignant'] . ' - ' . $enseignant['nom_complet']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="edit_site">Site/Salle:</label>
                            <input type="text" id="edit_site" name="site">
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="edit_periode">Période:</label>
                            <select id="edit_periode" name="periode" required>
                                <option value="">Sélectionner une période</option>
                                <option value="AM">AM (Matin)</option>
                                <option value="PM">PM (Après-midi)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="edit_observation">Observation:</label>
                            <textarea id="edit_observation" name="observation" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-modal btn-secondary" onclick="closeModal('editHoraireModal')">Annuler</button>
                    <button type="submit" class="btn-modal btn-primary-modal">Modifier</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal pour ajouter une salle -->
    <div id="addSalleModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addSalleModal')">&times;</span>
            <h2 class="modal-header">Ajouter une salle</h2>
            <form id="addSalleForm">
                <div class="form-group">
                    <label for="nom_salle">Nom de la salle:</label>
                    <input type="text" id="nom_salle" name="nom_salle" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-modal btn-secondary" onclick="closeModal('addSalleModal')">Annuler</button>
                    <button type="submit" class="btn-modal btn-primary-modal">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal pour modifier une salle -->
    <div id="editSalleModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editSalleModal')">&times;</span>
            <h2 class="modal-header">Modifier une salle</h2>
            <form id="editSalleForm">
                <input type="hidden" id="ancien_nom" name="ancien_nom">
                <div class="form-group">
                    <label for="nouveau_nom">Nouveau nom de la salle:</label>
                    <input type="text" id="nouveau_nom" name="nouveau_nom" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-modal btn-secondary" onclick="closeModal('editSalleModal')">Annuler</button>
                    <button type="submit" class="btn-modal btn-primary-modal">Modifier</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="section-chief-footer">
        <p>&copy; 2025 Système de Suivi de Prestation. Tous droits réservés.</p>
    </footer>

    <script>
        // Fonctions pour ouvrir les modals
        function openAddHoraireModal() {
            document.getElementById('addHoraireModal').style.display = 'block';
        }
        
        function openEditHoraireModal() {
            const selectedRow = document.querySelector('tbody tr.selected');
            if (!selectedRow) {
                alert('Veuillez sélectionner un horaire à modifier.');
                return;
            }
            
            const id = selectedRow.getAttribute('data-id');
            const cells = selectedRow.querySelectorAll('td');
            
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_idcours').value = cells[1].textContent;
            document.getElementById('edit_jourheure').value = cells[2].textContent;
            document.getElementById('edit_codemention').value = cells[3].textContent;
            document.getElementById('edit_codepromotion').value = cells[4].textContent;
            document.getElementById('edit_enseignant').value = cells[5].textContent;
            document.getElementById('edit_site').value = cells[6].textContent;
            document.getElementById('edit_periode').value = cells[7].textContent;
            document.getElementById('edit_observation').value = cells[8].textContent;
            
            document.getElementById('editHoraireModal').style.display = 'block';
        }
        
        function deleteHoraire() {
            const selectedRow = document.querySelector('tbody tr.selected');
            if (!selectedRow) {
                alert('Veuillez sélectionner un horaire à supprimer.');
                return;
            }
            
            const id = selectedRow.getAttribute('data-id');
            if (confirm('Êtes-vous sûr de vouloir supprimer cet horaire ?')) {
                window.location.href = 'scripts/delete_horaire.php?supp=' + id;
            }
        }
        
        function openAddSalleModal() {
            document.getElementById('addSalleModal').style.display = 'block';
        }
        
        function openEditSalleModal(nomSalle) {
            if (nomSalle) {
                document.getElementById('ancien_nom').value = nomSalle;
                document.getElementById('nouveau_nom').value = nomSalle;
            } else {
                const selectedRow = document.querySelector('.section-chief-section:nth-child(4) tbody tr.selected');
                if (!selectedRow) {
                    alert('Veuillez sélectionner une salle à modifier.');
                    return;
                }
                
                const nomSalle = selectedRow.getAttribute('data-salle');
                document.getElementById('ancien_nom').value = nomSalle;
                document.getElementById('nouveau_nom').value = nomSalle;
            }
            
            document.getElementById('editSalleModal').style.display = 'block';
        }
        
        function deleteSalle(nomSalle) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette salle ? Cela affectera tous les horaires qui l\'utilisent.')) {
                // Pour le moment, on affiche un message car la suppression n'est pas implémentée côté serveur
                alert('Fonctionnalité de suppression de salle non implémentée. Cette fonctionnalité nécessite une mise à jour du script manage_salle.php.');
            }
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        // Fermer les modals en cliquant en dehors
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
        
        // Sélection des lignes dans les tableaux
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                row.addEventListener('click', function() {
                    // Retirer la sélection des autres lignes
                    rows.forEach(r => r.classList.remove('selected'));
                    // Ajouter la sélection à la ligne cliquée
                    this.classList.add('selected');
                });
            });
            
            // Gestion du formulaire d'ajout de salle
            const addSalleForm = document.getElementById('addSalleForm');
            if (addSalleForm) {
                addSalleForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const nomSalle = document.getElementById('nom_salle').value;
                    
                    fetch('scripts/manage_salle.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'action=add_salle&nom_salle=' + encodeURIComponent(nomSalle)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            closeModal('addSalleModal');
                            location.reload();
                        } else {
                            alert('Erreur: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur s\'est produite lors de l\'ajout de la salle.');
                    });
                });
            }
            
            // Gestion du formulaire de modification de salle
            const editSalleForm = document.getElementById('editSalleForm');
            if (editSalleForm) {
                editSalleForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const ancienNom = document.getElementById('ancien_nom').value;
                    const nouveauNom = document.getElementById('nouveau_nom').value;
                    
                    fetch('scripts/manage_salle.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'action=update_salle&ancien_nom=' + encodeURIComponent(ancienNom) + '&nouveau_nom=' + encodeURIComponent(nouveauNom)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            closeModal('editSalleModal');
                            location.reload();
                        } else {
                            alert('Erreur: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur s\'est produite lors de la modification de la salle.');
                    });
                });
            }
        });
    </script>
</body>
</html>