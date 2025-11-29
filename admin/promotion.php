<?php 
    include("nav_dash.php");
    
    // Connexion à la base de données
    include("../script/connexion.php");
    
    // Vérifier si on est en mode édition
    $editing_promotion = null;
    if (!empty($_GET['edit'])) {
        $edit_id = $_GET['edit'];
        $edit_result = mysqli_query($con, "
            SELECT p.*, m.nomComplet as mention_nom 
            FROM promotion p 
            LEFT JOIN mention m ON p.code_mention = m.code_mention
            WHERE p.id = '$edit_id'
        ");
        $editing_promotion = mysqli_fetch_assoc($edit_result);
    }
    
    // Requête pour obtenir toutes les promotions
    $promotions_result = mysqli_query($con, "
        SELECT p.*, m.nomComplet as mention_nom 
        FROM promotion p 
        LEFT JOIN mention m ON p.code_mention = m.code_mention
    ");
    
    // Récupérer les mentions pour la liste déroulante
    $mentions = mysqli_query($con, "SELECT * FROM mention");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Gestion des Promotions</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>Promotions</li>
        </ul>
    </div>

    <div class="admin-form">
        <h3 class="form-title"><?php echo $editing_promotion ? 'Modifier une promotion' : 'Ajouter une nouvelle promotion'; ?></h3>
        <div class="formulaire">
            <form id="promotionForm" action="../script/<?php echo $editing_promotion ? 'update_promotion.php' : 'addpromotion.php'; ?>" method="POST">
                <?php if ($editing_promotion): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($editing_promotion['id']); ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="codedepart">Mention :</label>
                        <select name="codedepart" id="codedepart" class="form-control" required>
                            <option value="">Sélectionner une mention</option>
                            <?php 
                            // Ré-exécuter la requête car elle a pu être consommée
                            $mentions = mysqli_query($con, "SELECT * FROM mention");
                            while($mention = mysqli_fetch_assoc($mentions)): ?>
                            <option value="<?php echo $mention['code_mention']; ?>" 
                                <?php echo ($editing_promotion && $editing_promotion['code_mention'] == $mention['code_mention']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($mention['nomComplet']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-col">
                        <label for="sigle">Sigle :</label>
                        <input type="text" id="sigle" name="sigle" class="form-control" required 
                               placeholder="Ex: L1GI" 
                               value="<?php echo $editing_promotion ? htmlspecialchars($editing_promotion['sigle_promotion']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="nomComplet">Dénomination complète :</label>
                        <input type="text" id="nomComplet" name="nomComplet" class="form-control" required 
                               placeholder="Ex: Première année de Licence en Gestion Informatique" 
                               value="<?php echo $editing_promotion ? htmlspecialchars($editing_promotion['nomComplet']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="description">Description :</label>
                        <input type="text" id="description" name="description" class="form-control" required 
                               placeholder="Description de la promotion" 
                               value="<?php echo $editing_promotion ? htmlspecialchars($editing_promotion['description']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="dtcreation">Date de création :</label>
                        <input type="date" id="dtcreation" name="dtcreation" class="form-control" required 
                               value="<?php echo $editing_promotion ? htmlspecialchars($editing_promotion['dtcreation']) : ''; ?>">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-<?php echo $editing_promotion ? 'sync' : 'plus'; ?>"></i> 
                    <?php echo $editing_promotion ? 'Modifier Promotion' : 'Ajouter Promotion'; ?>
                </button>
                
                <?php if ($editing_promotion): ?>
                    <a href="promotion.php" class="btn btn-secondary"><i class="fas fa-times"></i> Annuler</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Liste des promotions</h3>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Sigle</th>
                    <th>Nom complet</th>
                    <th>Mention</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Ré-exécuter la requête car elle a pu être consommée
                $promotions_result = mysqli_query($con, "
                    SELECT p.*, m.nomComplet as mention_nom 
                    FROM promotion p 
                    LEFT JOIN mention m ON p.code_mention = m.code_mention
                ");
                while ($promotion = mysqli_fetch_assoc($promotions_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($promotion['id']); ?></td>
                    <td><?php echo htmlspecialchars($promotion['sigle_promotion']); ?></td>
                    <td><?php echo htmlspecialchars($promotion['nomComplet']); ?></td>
                    <td><?php echo htmlspecialchars($promotion['mention_nom']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($promotion['dtcreation'])); ?></td>
                    <td class="table-actions">
                        <a href="promotion.php?edit=<?php echo $promotion['id']; ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="../script/delete_promotion.php?id=<?php echo $promotion['id']; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette promotion ?')">
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