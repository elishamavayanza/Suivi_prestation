<?php
session_start();
if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'Academique' && $_SESSION['role'] != 'SGA')) {
    header("Location: ../login.php");
    exit();
}

include '../config/connexion.php';

// Récupérer les données pourles recherches
$enseignants = [];
$salles = [];
$cours = [];

//Données pour l'emploi du temps global
$horaires_global = [];

try {
    // Récupérer tous les enseignants
    $stmt = $pdo->prepare("SELECT matriculeEnseignant, nom, postnom, prenom FROM enseignant ORDER BY nom");
    $stmt->execute();
    $enseignants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer toutes les salles (distinct)
    $stmt = $pdo->prepare("SELECT DISTINCT site FROM horaire WHERE site IS NOT NULL AND site != '' ORDER BY site");
    $stmt->execute();
$salles = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Récupérer tous les cours
    $stmt = $pdo->prepare("SELECT code_cours, nomComplet FROM cours ORDER BY nomComplet");
    $stmt->execute();
    $cours = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
// Récupérer tous les horaires pour l'affichage global
    $stmt = $pdo->prepare("
        SELECT h.*, c.nomComplet as nom_cours, e.nom as nom_enseignant, e.postnom, e.prenom
        FROM horaire h
        LEFT JOIN cours cON h.idcours = c.code_cours
        LEFT JOIN enseignant e ON h.enseignant = e.matriculeEnseignant
        ORDER BY h.jourheure
    ");
    $stmt->execute();
    $horaires_global = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des données: " . $e->getMessage();
}

// Variables pour les résultats de recherche
$resultats_horaire = [];
$type_recherche = "";

// Traitement de la recherche
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        switch ($_POST['action']) {
            case 'recherche_enseignant':
                $enseignant_id = $_POST['enseignant_id'] ?? '';
                if (!empty($enseignant_id)) {
                    $stmt = $pdo->prepare("
                        SELECT h.*, c.nomComplet as nom_cours, e.nom as nom_enseignant, e.postnom, e.prenom
                        FROM horaire h
                        LEFT JOIN cours c ON h.idcours = c.code_cours
                        LEFT JOIN enseignant e ON h.enseignant = e.matriculeEnseignantWHERE h.enseignant = ?
                        ORDER BY h.jourheure
                    ");
                    $stmt->execute([$enseignant_id]);
                    $resultats_horaire = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $type_recherche = "enseignant";
                }
                break;

            case 'recherche_salle':
                $salle = $_POST['salle'] ?? '';
                if (!empty($salle)) {
                    $stmt = $pdo->prepare("
                        SELECT h.*, c.nomComplet as nom_cours, e.nom as nom_enseignant, e.postnom, e.prenom
                       FROM horaire h
                        LEFT JOIN cours c ON h.idcours = c.code_cours
                        LEFT JOIN enseignant e ON h.enseignant = e.matriculeEnseignant
                        WHERE h.site= ?
                        ORDER BY h.jourheure
                    ");
                    $stmt->execute([$salle]);
                    $resultats_horaire = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $type_recherche = "salle";
                }
                break;

            case 'recherche_cours':
                $cours_id = $_POST['cours_id'] ?? '';
                if (!empty($cours_id)) {
                    $stmt = $pdo->prepare("
                        SELECT h.*, c.nomComplet as nom_cours, e.nom as nom_enseignant, e.postnom, e.prenom
                        FROM horaire h
LEFT JOIN cours c ON h.idcours = c.code_cours
                        LEFT JOIN enseignant e ON h.enseignant = e.matriculeEnseignant
                        WHERE h.idcours = ?
                        ORDER BY h.jourheure
                    ");
                    $stmt->execute([$cours_id]);
                    $resultats_horaire = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $type_recherche = "cours";
                }
                break;
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la recherche: " . $e->getMessage();
    }
}

// Fonction pour organiser les horaires dans un tableau à 2 dimensions (jour x heure)
function organiserHoraires($horaires) {
    $jours = ['LUNDI', 'MARDI', 'MERCREDI', 'JEUDI', 'VENDREDI', 'SAMEDI'];
    $heures = ['08:00 - 09:00', '09:00 - 10:00', '10:00 - 11:00', '11:00 - 12:00'];
    
    $emploi_du_temps = array_fill_keys($heures, array_fill_keys($jours, ''));
    
    foreach ($horaires as $horaire) {
        // Extraire les informations de jourheure
        $jourheure = $horaire['jourheure'] ?? '';
        if (!$jourheure) continue;
// Pour simplifier, on suppose un format "JOUR - HEURES"
        // Dans une vraie application, cela devrait être mieux structuré
        $parts = explode(' - ', $jourheure);
        if (count($parts) >= 2) {
            $jour = strtoupper($parts[0]);
            $heure = $parts[1];
            
            // Associer avec nos plages horaires prédéfinies
            $heure_key = '';
            if (strpos($heure, '08:') !== false) {
                $heure_key = '08:00 - 09:00';
            } else if (strpos($heure, '09:') !== false) {
                $heure_key = '09:00 - 10:00';
            } else if (strpos($heure, '10:') !== false) {
                $heure_key = '10:00 - 11:00';
            } else if (strpos($heure, '11:') !== false) {
                $heure_key = '11:00 - 12:00';
            }
            
            if ($heure_key && in_array($jour, $jours)){
                $cours = htmlspecialchars($horaire['nom_cours'] ?? $horaire['idcours']);
                $salle = htmlspecialchars($horaire['site'] ?? '');
                $enseignant = htmlspecialchars(($horaire['nom_enseignant'] ?? '') . ' ' . substr($horaire['postnom'] ?? '', 0, 1) . '.');
                
                $emploi_du_temps[$heure_key][$jour] = "$cours<br>($salle)<br><small>$enseignant</small>";
            }
        } else {
            // Gérer d'autres formats possibles
            foreach ($jours as $j) {
                if (stripos($jourheure, $j) !== false) {
                    $cours = htmlspecialchars($horaire['nom_cours'] ?? $horaire['idcours']);
                    $salle = htmlspecialchars($horaire['site'] ?? '');
                    $enseignant = htmlspecialchars(($horaire['nom_enseignant'] ?? '') . ' ' . substr($horaire['postnom'] ?? '', 0, 1) . '.');
                    
                    $emploi_du_temps['08:00 - 09:00'][$j] = "$cours<br>($salle)<br><small>$enseignant</small>";
                    break;
                }
            }
        }
    }
    
    return $emploi_du_temps;
}

$emploi_du_temps = organiserHoraires($horaires_global);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Horaires - Service Académique</title>
    <link rel="stylesheet" href="academic_admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .search-form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius:5px;
            margin-bottom: 20px;
        }
        .search-form select, .search-form button {
            margin-right: 10px;
            padding: 8px 12px;
        }
        .results-section {
            margin-top: 30px;
        }
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
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            border-radius: 5px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="academic-header">
        <h1><i class="fas fa-clock"></i> Gestion des Horaires - Service Académique</h1>
        <div class="header-actions">
            <div class="user-info">
                <div class="user-avatar"><?php echo substr($_SESSION['username'], 0, 1); ?></div>
                <span><?php echo $_SESSION['username']; ?> (SGA)</span>
            </div>
            <a href="index.php"><i class="fas fa-home"></i> <span>Accueil</span></a>
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
                    <li><a href="horaire.php" class="active"><i class="fas fa-clock"></i> <span>Gestion des Horaires</span></a></li>
                    <li><a href="chargehoraire.php"><i class="fas fa-hourglass-half"></i> <span>Charges Horaire</span></a></li>
                    <li><a href="academique_prestation.php"><i class="fas fa-file-invoice"></i> <span>Fiches de Prestation</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="academic-main">
            <div class="content-header">
                <h2>Gestion des Horaires</h2>
                <ul class="breadcrumb">
                    <li><a href="index.php">Accueil</a></li>
                    <li>Gestion des Horaires</li>
                </ul>
            </div>

            <div class="academic-alert academic-alert-info">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>Service Académique:</strong> 
                    En tant que membre du service académique, vous pouvez consulter les emplois du temps et effectuer des validations.
                </div>
            </div>

            <div class="academic-section">
                <h3 class="section-title"><i class="fas fa-search"></i> Recherche d'Horaires</h3>
                <div class="search-form">
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="recherche_enseignant">
<select name="enseignant_id" required>
                            <option value="">Sélectionner un enseignant</option>
                            <?php foreach ($enseignants as $enseignant): ?>
                                <option value="<?php echo htmlspecialchars($enseignant['matriculeEnseignant']); ?>">
                                    <?php echo htmlspecialchars($enseignant['nom'] . ' ' . $enseignant['postnom'] . ' ' . $enseignant['prenom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Rechercher par Enseignant</button>
                    </form>

                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="recherche_salle">
                        <select name="salle" required>
                            <option value="">Sélectionner une salle</option>
                            <?php foreach ($salles as $salle): ?>
                                <option value="<?php echo htmlspecialchars($salle); ?>"><?php echo htmlspecialchars($salle); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-success"><i class="fas fa-search"></i> Rechercher par Salle</button>
                    </form>

                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="recherche_cours">
                        <select name="cours_id" required>
                            <option value="">Sélectionner un cours</option>
                            <?php foreach ($cours as $cour): ?>
                                <option value="<?php echo htmlspecialchars($cour['code_cours']); ?>">
                                    <?php echo htmlspecialchars($cour['nomComplet']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <buttontype="submit" class="btn btn-info"><i class="fas fa-search"></i> Rechercher par Cours</button>
                    </form>
                </div>

                <?php if (!empty($resultats_horaire)): ?>
                <div class="results-section">
                    <h3>Résultats de la recherche (par <?php echohtmlspecialchars($type_recherche); ?>)</h3>
                    <div class="academic-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Jour/Horaire</th>
                                    <th>Cours</th>
                                    <th>Enseignant</th>
                                    <th>Mention/Promotion</th>
                                    <th>Salle</th>
                                    <th>Période</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resultats_horaire as $horaire): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($horaire['jourheure'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($horaire['nom_cours'] ?? $horaire['idcours']); ?></td>
                                    <td><?php echo htmlspecialchars(($horaire['nom_enseignant'] ?? '') . ' ' . ($horaire['postnom']?? '') . ' ' . ($horaire['prenom'] ?? '')); ?></td>
                                    <td><?php echo htmlspecialchars($horaire['codemention'] ?? '') . '/' . htmlspecialchars($horaire['codepromotion'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($horaire['site'] ??''); ?></td>
                                    <td><?php echo htmlspecialchars($horaire['periode'] ?? ''); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
               <div class="academic-alert academic-alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>Aucun résultat trouvé pour cette recherche.</div>
                </div>
                <?php endif; ?>

                <h3 class="section-title"><i class="fas fa-calendar-alt"></i> Emploi du Temps Global</h3>
                <p>Consultation des emplois du temps de tous les départements.</p>
                
                <div class="academic-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Heures</th>
                                <th>Lundi</th>
                                <th>Mardi</th>
                                <th>Mercredi</th>
                                <th>Jeudi</th>
                                <th>Vendredi</th>
                                <th>Samedi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($emploi_du_temps as $heure => $jours_data): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($heure); ?></td>
                                <td><?php echo $jours_data['LUNDI']; ?></td>
                                <td><?php echo $jours_data['MARDI']; ?></td>
                               <td><?php echo $jours_data['MERCREDI']; ?></td>
                                <td><?php echo $jours_data['JEUDI']; ?></td>
                                <td><?php echo $jours_data['VENDREDI']; ?></td>
                                <td><?php echo $jours_data['SAMEDI'];?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
</div>

<div class="academic-section">
                <h3 class="section-title"><i class="fas fa-door-open"></i> Gestion des Salles</h3>
                <p>Attribution des salles aux différents cours.</p>
                
                <div class="academic-actions">
                    <button class="btn btn-primary" onclick="openModal('add')"><i class="fas fa-plus-circle"></i> Ajouter une salle</button>
                    <button class="btn btn-success" onclick="openModal('update')"><i class="fas fa-edit"></i> Modifier une salle</button>
                </div>
<div class="academic-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Nom de la Salle</th>
                                <th>Type</th>
                                <th>Capacité</th>
                                <th>Équipements</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($salles as $salle): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($salle); ?></td>
                                <td>Salle de cours</td>
                                <td>30 étudiants</td>
                                <td>Tableau, Chaises</td>
                                <td class="table-actions">
                                    <button class="btn btn-sm btn-success" onclick="openModal('update', '<?php echo htmlspecialchars($salle); ?>')"><i class="fas fa-edit"></i> Modifier</button>
<button class="btn btn-sm btn-danger" onclick="deleteSalle('<?php echo htmlspecialchars($salle); ?>')"><i class="fas fa-trash"></i> Supprimer</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal pour ajouter/modifier une salle -->
    <div id="salleModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3 id="modalTitle">Gestion des Salles</h3>
            <form id="salleForm">
                <input type="hidden" id="modalAction" name="action">
                <div id="formFields">
                    <!-- Les champs seront remplis dynamiquement -->
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Annuler</button>
            </form>
        </div>
    </div>

    <footer class="academic-footer">
        <p>&copy; 2025 Système de Suivi de Prestation. Tous droits réservés.</p>
    </footer>

    <script>
        // Gestion des modals
        function openModal(action, salleName = null) {
            const modal = document.getElementById('salleModal');
           const title = document.getElementById('modalTitle');
            const formFields = document.getElementById('formFields');
            const modalAction = document.getElementById('modalAction');
            
            if (action === 'add') {
                title.textContent = 'Ajouter une salle';
                modalAction.value = 'add_salle';
                formFields.innerHTML= `
                    <label for="nom_salle">Nom de la salle :</label>
                    <input type="text" id="nom_salle" name="nom_salle" class="form-control" required>
                `;
            } else if (action === 'update') {
                title.textContent = 'Modifier une salle';
                modalAction.value = 'update_salle';
                formFields.innerHTML = `
                    <label for="ancien_nom">Ancien nom de la salle :</label>
                    <input type="text" id="ancien_nom" name="ancien_nom" class="form-control" value="${salleName ||''}" ${salleName ? 'readonly' : 'required'}>
                    <label for="nouveau_nom" style="margin-top: 10px;">Nouveau nom de la salle :</label>
                    <input type="text" id="nouveau_nom" name="nouveau_nom"class="form-control" required>
                `;
            }
            
            modal.style.display = 'block';
        }
        
        function closeModal() {
            document.getElementById('salleModal').style.display = 'none';
        }
        
        function deleteSalle(salleName) {
            if (confirm('Êtes-vous sûr devouloir supprimer la salle "' + salleName + '" ?')) {
                // Envoyer une requête AJAX pour supprimer la salle
                const formData = new FormData();
                formData.append('action', 'delete_salle');
                formData.append('nom_salle', salleName);
                
                fetch('../chefsection/scripts/manage_salle.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Salle supprimée avec succès');
                        location.reload();
                    } else {
                        alert('Erreur lors de la suppression : ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Erreur lors de la suppression : ' + error.message);
                });
            }
        }
        
        // Fermer le modal quand on clique en dehors
        window.onclick = function(event) {
const modal = document.getElementById('salleModal');
            if (event.target == modal) {
                closeModal();
            }
        }
        
        // Gestion du formulaire
        document.getElementById('salleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
fetch('../chefsection/scripts/manage_salle.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Opération réussie : ' + data.message);
                    closeModal();
location.reload();
                } else {
                    alert('Erreur : ' + data.message);
                }
            })
            .catch(error => {
                alert('Erreur lors de l\'opération : ' + error.message);
            });
        });
    </script>
</body>
</html>