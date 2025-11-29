<?php 
    include("nav_dash.php");
    
    // Connexion à la base de données
    include("../script/connexion.php");
    
    // Vérifier si on est en mode édition
    $editing_mention = null;
    if (!empty($_GET['edit'])) {
        $edit_id = $_GET['edit'];
        $edit_result = mysqli_query($con, "
            SELECT m.*, s.nomComplet as section_nom 
            FROM mention m 
            LEFT JOIN section s ON m.code_section = s.code_section
            WHERE m.code_mention = '$edit_id'
        ");
        $editing_mention = mysqli_fetch_assoc($edit_result);
    }
    
    // Requête pour obtenir toutes les mentions
    $mentions_result = mysqli_query($con, "
        SELECT m.*, s.nomComplet as section_nom 
        FROM mention m 
        LEFT JOIN section s ON m.code_section = s.code_section
    ");
    
    // Récupérer les sections pour la liste déroulante
    $sections = mysqli_query($con, "SELECT * FROM section");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Gestion des Mentions</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>Mentions</li>
        </ul>
    </div>

    <div class="admin-form">
        <h3 class="form-title"><?php echo $editing_mention ? 'Modifier une mention' : 'Ajouter une nouvelle mention'; ?></h3>
        <div class="formulaire">
            <form id="mentionForm" action="../script/<?php echo $editing_mention ? 'update_mention.php' : 'addmention.php'; ?>" method="POST">
                <?php if ($editing_mention): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($editing_mention['code_mention']); ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="codefac">Section :</label>
                        <select name="codefac" id="codefac" class="form-control" required>
                            <option value="">Sélectionner une section</option>
                            <?php 
                            // Ré-exécuter la requête car elle a pu être consommée
                            $sections = mysqli_query($con, "SELECT * FROM section");
                            while($section = mysqli_fetch_assoc($sections)): ?>
                            <option value="<?php echo $section['code_section']; ?>" 
                                <?php echo ($editing_mention && $editing_mention['code_section'] == $section['code_section']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($section['nomComplet']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-col">
                        <label for="sigle">Sigle :</label>
                        <input type="text" id="sigle" name="sigle" class="form-control" required 
                               placeholder="Ex: GI" 
                               value="<?php echo $editing_mention ? htmlspecialchars($editing_mention['sigle']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="nomComplet">Dénomination complète :</label>
                        <input type="text" id="nomComplet" name="nomComplet" class="form-control" required 
                               placeholder="Ex: Gestion Informatique" 
                               value="<?php echo $editing_mention ? htmlspecialchars($editing_mention['nomComplet']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="description">Description :</label>
                        <input type="text" id="description" name="description" class="form-control" required 
                               placeholder="Description de la mention" 
                               value="<?php echo $editing_mention ? htmlspecialchars($editing_mention['description']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="dtcreation">Date de création :</label>
                        <input type="date" id="dtcreation" name="dtcreation" class="form-control" required 
                               value="<?php echo $editing_mention ? htmlspecialchars($editing_mention['dtcreation']) : ''; ?>">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-<?php echo $editing_mention ? 'sync' : 'plus'; ?>"></i> 
                    <?php echo $editing_mention ? 'Modifier Mention' : 'Ajouter Mention'; ?>
                </button>
                
                <?php if ($editing_mention): ?>
                    <a href="mention.php" class="btn btn-secondary"><i class="fas fa-times"></i> Annuler</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Liste des mentions</h3>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Sigle</th>
                    <th>Nom complet</th>
                    <th>Section</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Ré-exécuter la requête car elle a pu être consommée
                $mentions_result = mysqli_query($con, "
                    SELECT m.*, s.nomComplet as section_nom 
                    FROM mention m 
                    LEFT JOIN section s ON m.code_section = s.code_section
                ");
                while ($mention = mysqli_fetch_assoc($mentions_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($mention['code_mention']); ?></td>
                    <td><?php echo htmlspecialchars($mention['sigle']); ?></td>
                    <td><?php echo htmlspecialchars($mention['nomComplet']); ?></td>
                    <td><?php echo htmlspecialchars($mention['section_nom']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($mention['dtcreation'])); ?></td>
                    <td class="table-actions">
                        <a href="mention.php?edit=<?php echo $mention['code_mention']; ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="../script/delete_mention.php?id=<?php echo $mention['code_mention']; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette mention ?')">
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