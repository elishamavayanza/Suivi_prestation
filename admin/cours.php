<?php 
    include("nav_dash.php");
    
    // Connexion à la base de données
    include("../script/connexion.php");
    
    // Vérifier si on est en mode édition
    $editing_cours = null;
    if (!empty($_GET['edit'])) {
        $edit_id = $_GET['edit'];
        $edit_result = mysqli_query($con, "
            SELECT * FROM cours WHERE id = '$edit_id'
        ");
        $editing_cours = mysqli_fetch_assoc($edit_result);
    }
    
    // Requête pour obtenir tous les cours
    $cours_result = mysqli_query($con, "SELECT c.*, m.nomComplet as mention_nom FROM cours c LEFT JOIN mention m ON c.code_mention = m.code_mention");
    
    // Récupérer les mentions, sections et promotions pour les listes déroulantes
    $mentions = mysqli_query($con, "SELECT * FROM mention");
    $sections = mysqli_query($con, "SELECT * FROM section");
    $promotions = mysqli_query($con, "SELECT * FROM promotion");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Gestion des Cours</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>Cours</li>
        </ul>
    </div>

    <div class="admin-form">
        <h3 class="form-title"><?php echo $editing_cours ? 'Modifier un cours' : 'Ajouter un nouveau cours'; ?></h3>
        <div class="formulaire">
            <form id="coursForm" action="../script/<?php echo $editing_cours ? 'update_cours.php' : 'addcours.php'; ?>" method="POST">
                <?php if ($editing_cours): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($editing_cours['id']); ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="code_cours">Code du cours :</label>
                        <input type="text" id="code_cours" name="code_cours" class="form-control" required
                               value="<?php echo $editing_cours ? htmlspecialchars($editing_cours['code_cours']) : ''; ?>">
                    </div>
                    
                    <div class="form-col">
                        <label for="nomComplet">Nom complet :</label>
                        <input type="text" id="nomComplet" name="nomComplet" class="form-control" required
                               value="<?php echo $editing_cours ? htmlspecialchars($editing_cours['nomComplet']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="nbreHeure">Nombre d'heures :</label>
                        <input type="number" id="nbreHeure" name="nbreHeure" class="form-control" required
                               value="<?php echo $editing_cours ? htmlspecialchars($editing_cours['nbreHeure']) : ''; ?>">
                    </div>
                    
                    <div class="form-col">
                        <label for="ponderation">Pondération :</label>
                        <input type="number" id="ponderation" name="ponderation" class="form-control" required
                               value="<?php echo $editing_cours ? htmlspecialchars($editing_cours['ponderation']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="code_mention">Mention :</label>
                        <select name="code_mention" id="code_mention" class="form-control" required>
                            <option value="">Sélectionner une mention</option>
                            <?php 
                            // Ré-exécuter la requête car elle a pu être consommée
                            $mentions = mysqli_query($con, "SELECT * FROM mention");
                            while($mention = mysqli_fetch_assoc($mentions)): ?>
                            <option value="<?php echo $mention['code_mention']; ?>"
                                <?php echo ($editing_cours && $editing_cours['code_mention'] == $mention['code_mention']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($mention['nomComplet']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-col">
                        <label for="code_section">Section :</label>
                        <select name="code_section" id="code_section" class="form-control" required>
                            <option value="">Sélectionner une section</option>
                            <?php 
                            // Ré-exécuter la requête car elle a pu être consommée
                            $sections = mysqli_query($con, "SELECT * FROM section");
                            while($section = mysqli_fetch_assoc($sections)): ?>
                            <option value="<?php echo $section['code_section']; ?>"
                                <?php echo ($editing_cours && $editing_cours['code_section'] == $section['code_section']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($section['nomComplet']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="promotion">Promotion :</label>
                        <select name="promotion" id="promotion" class="form-control" required>
                            <option value="">Sélectionner une promotion</option>
                            <?php 
                            // Ré-exécuter la requête car elle a pu être consommée
                            $promotions = mysqli_query($con, "SELECT * FROM promotion");
                            while($promo = mysqli_fetch_assoc($promotions)): ?>
                            <option value="<?php echo $promo['sigle_promotion']; ?>"
                                <?php echo ($editing_cours && $editing_cours['promotion'] == $promo['sigle_promotion']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($promo['nomComplet']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-col">
                        <label for="description">Description :</label>
                        <input type="text" id="description" name="description" class="form-control" required
                               value="<?php echo $editing_cours ? htmlspecialchars($editing_cours['description']) : ''; ?>">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-<?php echo $editing_cours ? 'sync' : 'plus'; ?>"></i> 
                    <?php echo $editing_cours ? 'Modifier Cours' : 'Ajouter Cours'; ?>
                </button>
                
                <?php if ($editing_cours): ?>
                    <a href="cours.php" class="btn btn-secondary"><i class="fas fa-times"></i> Annuler</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Liste des cours</h3>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Nom complet</th>
                    <th>Nombre d'heures</th>
                    <th>Mention</th>
                    <th>Promotion</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Ré-exécuter la requête car elle a pu être consommée
                $cours_result = mysqli_query($con, "SELECT c.*, m.nomComplet as mention_nom FROM cours c LEFT JOIN mention m ON c.code_mention = m.code_mention");
                while ($cour = mysqli_fetch_assoc($cours_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($cour['code_cours']); ?></td>
                    <td><?php echo htmlspecialchars($cour['nomComplet']); ?></td>
                    <td><?php echo htmlspecialchars($cour['nbreHeure']); ?></td>
                    <td><?php echo htmlspecialchars($cour['mention_nom']); ?></td>
                    <td><?php echo htmlspecialchars($cour['promotion']); ?></td>
                    <td class="table-actions">
                        <a href="cours.php?edit=<?php echo $cour['id']; ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="../script/delete_cours.php?id=<?php echo $cour['code_cours']; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')">
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