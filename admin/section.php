<?php 
    include("nav_dash.php");
    
    // Connexion à la base de données
    include("../script/connexion.php");
    
    // Vérifier si on est en mode édition
    $editing_section = null;
    if (!empty($_GET['edit'])) {
        $edit_id = $_GET['edit'];
        $edit_result = mysqli_query($con, "
            SELECT s.*, i.nomComplet as isp_nom 
            FROM section s 
            LEFT JOIN isp i ON s.code_isp = i.code_isp
            WHERE s.code_section = '$edit_id'
        ");
        $editing_section = mysqli_fetch_assoc($edit_result);
    }
    
    // Requête pour obtenir toutes les sections
    $sections_result = mysqli_query($con, "
        SELECT s.*, i.nomComplet as isp_nom 
        FROM section s 
        LEFT JOIN isp i ON s.code_isp = i.code_isp
    ");
    
    // Récupérer les ISP pour la liste déroulante
    $isps = mysqli_query($con, "SELECT * FROM isp");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Gestion des Sections</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>Sections</li>
        </ul>
    </div>

    <div class="admin-form">
        <h3 class="form-title"><?php echo $editing_section ? 'Modifier une section' : 'Ajouter une nouvelle section'; ?></h3>
        <div class="formulaire">
            <form id="sectionForm" action="../script/<?php echo $editing_section ? 'update_section.php' : 'addsection.php'; ?>" method="POST">
                <?php if ($editing_section): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($editing_section['code_section']); ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="code">ISP :</label>
                        <select name="code" id="code" class="form-control" required>
                            <option value="">Sélectionner un ISP</option>
                            <?php 
                            // Ré-exécuter la requête car elle a pu être consommée
                            $isps = mysqli_query($con, "SELECT * FROM isp");
                            while($isp = mysqli_fetch_assoc($isps)): ?>
                            <option value="<?php echo $isp['code_isp']; ?>" 
                                <?php echo ($editing_section && $editing_section['code_isp'] == $isp['code_isp']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($isp['nomComplet']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-col">
                        <label for="sigle">Sigle :</label>
                        <input type="text" id="sigle" name="sigle" class="form-control" required 
                               placeholder="Ex: IG" 
                               value="<?php echo $editing_section ? htmlspecialchars($editing_section['sigle']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="nomComplet">Dénomination complète :</label>
                        <input type="text" id="nomComplet" name="nomComplet" class="form-control" required 
                               placeholder="Ex: Informatique de Gestion" 
                               value="<?php echo $editing_section ? htmlspecialchars($editing_section['nomComplet']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="description">Description :</label>
                        <input type="text" id="description" name="description" class="form-control" required 
                               placeholder="Description de la section" 
                               value="<?php echo $editing_section ? htmlspecialchars($editing_section['description']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="dtcreation">Date de création :</label>
                        <input type="date" id="dtcreation" name="dtcreation" class="form-control" required 
                               value="<?php echo $editing_section ? htmlspecialchars($editing_section['dt_creation']) : ''; ?>">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-<?php echo $editing_section ? 'sync' : 'plus'; ?>"></i> 
                    <?php echo $editing_section ? 'Modifier Section' : 'Ajouter Section'; ?>
                </button>
                
                <?php if ($editing_section): ?>
                    <a href="section.php" class="btn btn-secondary"><i class="fas fa-times"></i> Annuler</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Liste des sections</h3>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Sigle</th>
                    <th>Nom complet</th>
                    <th>ISP</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Ré-exécuter la requête car elle a pu être consommée
                $sections_result = mysqli_query($con, "
                    SELECT s.*, i.nomComplet as isp_nom 
                    FROM section s 
                    LEFT JOIN isp i ON s.code_isp = i.code_isp
                ");
                while ($section = mysqli_fetch_assoc($sections_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($section['code_section']); ?></td>
                    <td><?php echo htmlspecialchars($section['sigle']); ?></td>
                    <td><?php echo htmlspecialchars($section['nomComplet']); ?></td>
                    <td><?php echo htmlspecialchars($section['isp_nom']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($section['dt_creation'])); ?></td>
                    <td class="table-actions">
                        <a href="section.php?edit=<?php echo $section['code_section']; ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="../script/delete_section.php?id=<?php echo $section['code_section']; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette section ?')">
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