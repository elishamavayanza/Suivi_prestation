<?php 
    include("nav_dash.php");
    
    // Connexion à la base de données
    include("../script/connexion.php");
    
    // Vérifier si on est en mode édition
    $editing_horaire = null;
    if (!empty($_GET['edit'])) {
        $edit_id = $_GET['edit'];
        $edit_result = mysqli_query($con, "
            SELECT h.*, c.nomComplet as cours_nom, m.nomComplet as mention_nom, p.nomComplet as promo_nom
            FROM horaire h
            LEFT JOIN cours c ON h.idcours = c.code_cours
            LEFT JOIN mention m ON h.codemention = m.code_mention
            LEFT JOIN promotion p ON h.codepromotion = p.sigle_promotion
            WHERE h.idhoraire = '$edit_id'
        ");
        $editing_horaire = mysqli_fetch_assoc($edit_result);
    }
    
    // Requête pour obtenir tous les horaires
    $horaires_result = mysqli_query($con, "
        SELECT h.*, c.nomComplet as cours_nom, m.nomComplet as mention_nom, p.nomComplet as sigle_promotion
        FROM horaire h
        LEFT JOIN cours c ON h.idcours = c.code_cours
        LEFT JOIN mention m ON h.codemention = m.code_mention
        LEFT JOIN promotion p ON h.codepromotion = p.sigle_promotion
    ");
    
    // Récupérer les données pour les listes déroulantes
    $cours = mysqli_query($con, "SELECT * FROM cours");
    $mentions = mysqli_query($con, "SELECT * FROM mention");
    $promotions = mysqli_query($con, "SELECT * FROM promotion");
    $enseignants = mysqli_query($con, "SELECT * FROM enseignant");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Gestion des Horaires</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>Horaires</li>
        </ul>
    </div>

    <div class="admin-form">
        <h3 class="form-title"><?php echo $editing_horaire ? 'Modifier un horaire' : 'Programmer un cours'; ?></h3>
        <div class="formulaire">
            <form id="horaireForm" action="<?php echo $editing_horaire ? '../script/update_horaire.php' : '../script/add_horaire.php'; ?>" method="POST">
                <?php if ($editing_horaire): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($editing_horaire['idhoraire']); ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="cours">Cours :</label>
                        <select name="idcours" id="cours" class="form-control" required>
                            <option value="">Sélectionner un cours</option>
                            <?php 
                            // Ré-exécuter la requête car elle a pu être consommée
                            $cours = mysqli_query($con, "SELECT * FROM cours");
                            while($c = mysqli_fetch_assoc($cours)): ?>
                            <option value="<?php echo $c['code_cours']; ?>" 
                                <?php echo ($editing_horaire && $editing_horaire['idcours'] == $c['code_cours']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c['nomComplet']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-col">
                        <label for="mention">Mention :</label>
                        <select name="codemention" id="mention" class="form-control" required>
                            <option value="">Sélectionner une mention</option>
                            <?php 
                            // Ré-exécuter la requête car elle a pu être consommée
                            $mentions = mysqli_query($con, "SELECT * FROM mention");
                            while($m = mysqli_fetch_assoc($mentions)): ?>
                            <option value="<?php echo $m['code_mention']; ?>" 
                                <?php echo ($editing_horaire && $editing_horaire['codemention'] == $m['code_mention']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($m['nomComplet']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="promotion">Promotion :</label>
                        <select name="codepromotion" id="promotion" class="form-control" required>
                            <option value="">Sélectionner une promotion</option>
                            <?php 
                            // Ré-exécuter la requête car elle a pu être consommée
                            $promotions = mysqli_query($con, "SELECT * FROM promotion");
                            while($p = mysqli_fetch_assoc($promotions)): ?>
                            <option value="<?php echo $p['sigle_promotion']; ?>" 
                                <?php echo ($editing_horaire && $editing_horaire['codepromotion'] == $p['sigle_promotion']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($p['nomComplet']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-col">
                        <label for="periode">Période :</label>
                        <select name="periode" id="periode" class="form-control" required>
                            <option value="">Sélectionner une période</option>
                            <option value="AM" <?php echo ($editing_horaire && $editing_horaire['periode'] == 'AM') ? 'selected' : ''; ?>>Avant Midi</option>
                            <option value="PM" <?php echo ($editing_horaire && $editing_horaire['periode'] == 'PM') ? 'selected' : ''; ?>>Après Midi</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="jourheure">Jour & Heure :</label>
                        <input type="text" id="jourheure" name="jourheure" class="form-control" required 
                               placeholder="Ex: Lundi 8h-12h" 
                               value="<?php echo $editing_horaire ? htmlspecialchars($editing_horaire['jourheure']) : ''; ?>">
                    </div>
                    
                    <div class="form-col">
                        <label for="datejour">Date :</label>
                        <input type="date" id="datejour" name="datejour" class="form-control" 
                               value="<?php echo $editing_horaire ? htmlspecialchars($editing_horaire['datejour']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="enseignant">Enseignant :</label>
                        <select name="enseignant" id="enseignant" class="form-control" required>
                            <option value="">Sélectionner un enseignant</option>
                            <?php 
                            // Ré-exécuter la requête car elle a pu être consommée
                            $enseignants = mysqli_query($con, "SELECT * FROM enseignant");
                            while($e = mysqli_fetch_assoc($enseignants)): ?>
                            <option value="<?php echo $e['matriculeEnseignant']; ?>" 
                                <?php echo ($editing_horaire && $editing_horaire['enseignant'] == $e['matriculeEnseignant']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($e['nom'] . ' ' . $e['postnom'] . ' ' . $e['prenom']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-col">
                        <label for="site">Site :</label>
                        <input type="text" id="site" name="site" class="form-control" 
                               placeholder="Ex: Campus principal" 
                               value="<?php echo $editing_horaire ? htmlspecialchars($editing_horaire['site']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="observation">Observation :</label>
                        <textarea id="observation" name="observation" class="form-control" rows="3" placeholder="Observations"><?php echo $editing_horaire ? htmlspecialchars($editing_horaire['observation']) : ''; ?></textarea>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-<?php echo $editing_horaire ? 'sync' : 'plus'; ?>"></i> 
                    <?php echo $editing_horaire ? 'Modifier Horaire' : 'Ajouter Horaire'; ?>
                </button>
                
                <?php if ($editing_horaire): ?>
                    <a href="horaire.php" class="btn btn-secondary"><i class="fas fa-times"></i> Annuler</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Emploi du temps</h3>
        <table>
            <thead>
                <tr>
                    <th>Cours</th>
                    <th>Mention</th>
                    <th>Promotion</th>
                    <th>Jour & Heure</th>
                    <th>Enseignant</th>
                    <th>Site</th>
                    <th>Période</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Ré-exécuter la requête car elle a pu être consommée
                $horaires_result = mysqli_query($con, "
                    SELECT h.*, c.nomComplet as cours_nom, m.nomComplet as mention_nom, p.nomComplet as sigle_promotion
                    FROM horaire h
                    LEFT JOIN cours c ON h.idcours = c.code_cours
                    LEFT JOIN mention m ON h.codemention = m.code_mention
                    LEFT JOIN promotion p ON h.codepromotion = p.sigle_promotion
                ");
                while ($horaire = mysqli_fetch_assoc($horaires_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($horaire['cours_nom'] ?? $horaire['idcours']); ?></td>
                    <td><?php echo htmlspecialchars($horaire['mention_nom'] ?? $horaire['codemention']); ?></td>
                    <td><?php echo htmlspecialchars($horaire['sigle_promotion'] ?? $horaire['codepromotion']); ?></td>
                    <td><?php echo htmlspecialchars($horaire['jourheure']); ?></td>
                    <td><?php echo htmlspecialchars($horaire['enseignant']); ?></td>
                    <td><?php echo htmlspecialchars($horaire['site']); ?></td>
                    <td><?php echo htmlspecialchars($horaire['periode']); ?></td>
                    <td class="table-actions">
                        <a href="horaire.php?edit=<?php echo $horaire['idhoraire']; ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="../script/delete_horaire.php?supp=<?php echo $horaire['idhoraire']; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet horaire ?')">
                            <i class="fas fa-trash"></i> Supprimer
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php 
    include("nav_footer_dash.php");
?>