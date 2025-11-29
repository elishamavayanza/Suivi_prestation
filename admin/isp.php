<?php 
    include("nav_dash.php");
    
    // Connexion à la base de données
    include("../script/connexion.php");
    
    // Requête pour obtenir tous les ISP
    $isps_result = mysqli_query($con, "SELECT * FROM isp");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Gestion des ISP</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>ISP-Muhanga</li>
        </ul>
    </div>

    <div class="admin-form">
        <h3 class="form-title">Ajouter un nouvel ISP</h3>
        <?php
            include("../formulaire/formisp.php"); 
        ?>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Liste des ISP</h3>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Sigle</th>
                    <th>Nom complet</th>
                    <th>Date de création</th>
                    <th>Boîte postale</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($isp = mysqli_fetch_assoc($isps_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($isp['code_isp']); ?></td>
                    <td><?php echo htmlspecialchars($isp['sigle']); ?></td>
                    <td><?php echo htmlspecialchars($isp['nomComplet']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($isp['dt_creation'])); ?></td>
                    <td><?php echo htmlspecialchars($isp['boitepostal']); ?></td>
                    <td class="table-actions">
                        <!-- Bouton Modifier -->
                        <a href="#" class="btn btn-primary" onclick="editIsp('<?php echo addslashes($isp['code_isp']); ?>', '<?php echo addslashes($isp['sigle']); ?>', '<?php echo addslashes($isp['nomComplet']); ?>', '<?php echo addslashes($isp['description']); ?>', '<?php echo addslashes($isp['boitepostal']); ?>', '<?php echo $isp['dt_creation']; ?>')">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <!-- Bouton Supprimer -->
                        <a href="../script/delete_isp.php?id=<?php echo urlencode($isp['code_isp']); ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet ISP ?')">
                            <i class="fas fa-trash"></i> Supprimer
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Formulaire caché pour la modification -->
<div id="editModal" style="display:none;">
    <div class="admin-form">
        <h3 class="form-title">Modifier un ISP</h3>
        <div class="formulaire">
            <form id="editIspForm" action="../script/updateisp.php" method="POST">
                <input type="hidden" id="edit_id" name="id">
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="edit_arrete">Numéro ministère :</label>
                        <input type="text" id="edit_arrete" name="arrete" class="form-control" required>
                    </div>
                    
                    <div class="form-col">
                        <label for="edit_sigle">Sigle :</label>
                        <input type="text" id="edit_sigle" name="sigle" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="edit_nomComplet">Dénomination complète :</label>
                        <input type="text" id="edit_nomComplet" name="nomComplet" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="edit_description">Description :</label>
                        <input type="text" id="edit_description" name="description" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="edit_boitepostale">Boîte postale :</label>
                        <input type="text" id="edit_boitepostale" name="boitepostale" class="form-control" required>
                    </div>
                    
                    <div class="form-col">
                        <label for="edit_dtcreation">Date de création :</label>
                        <input type="date" id="edit_dtcreation" name="dtcreation" class="form-control" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success"><i class="fas fa-sync"></i> Mettre à jour</button>
                <button type="button" class="btn btn-secondary" onclick="cancelEdit()"><i class="fas fa-times"></i> Annuler</button>
            </form>
        </div>
    </div>
</div>

<script>
function editIsp(id, sigle, nomComplet, description, boitepostale, dtcreation) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_arrete').value = id;
    document.getElementById('edit_sigle').value = sigle;
    document.getElementById('edit_nomComplet').value = nomComplet;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_boitepostale').value = boitepostale;
    document.getElementById('edit_dtcreation').value = dtcreation;
    
    // Afficher le formulaire de modification
    document.getElementById('editModal').style.display = 'block';
    
    // Faire défiler jusqu'au formulaire
    document.getElementById('editModal').scrollIntoView({behavior: 'smooth'});
}

function cancelEdit() {
    document.getElementById('editModal').style.display = 'none';
}
</script>

<?php 
    include("nav_footer_dash.php");
?>