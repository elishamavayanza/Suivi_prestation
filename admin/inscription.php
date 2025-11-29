<?php 
    include("nav_dash.php");
    
    // Connexion à la base de données
    include("../script/connexion.php");
    
    // Requête pour obtenir toutes les inscriptions
    $inscriptions_result = mysqli_query($con, "
        SELECT i.*, e.nom as etudiant_nom, e.postnom as etudiant_postnom, 
               p.nomComplet as promotion_nom, m.nomComplet as mention_nom, s.nomComplet as section_nom
        FROM inscription i
        LEFT JOIN etudiant e ON i.matriculeEtudiant = e.matriculeEtudiant
        LEFT JOIN promotion p ON i.codepromotion = p.sigle_promotion
        LEFT JOIN mention m ON i.code_mention = m.code_mention
        LEFT JOIN section s ON i.code_section = s.code_section
    ");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Gestion des Inscriptions</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>Inscriptions</li>
        </ul>
    </div>

    <div class="admin-form">
        <h3 class="form-title">Inscrire un étudiant</h3>
        <?php
            include("../formulaire/forminscription.php"); 
        ?>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Liste des inscriptions</h3>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Étudiant</th>
                    <th>Promotion</th>
                    <th>Mention</th>
                    <th>Section</th>
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($inscription = mysqli_fetch_assoc($inscriptions_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($inscription['code_inscription']); ?></td>
                    <td><?php echo htmlspecialchars($inscription['etudiant_nom'] . ' ' . $inscription['etudiant_postnom']); ?></td>
                    <td><?php echo htmlspecialchars($inscription['promotion_nom']); ?></td>
                    <td><?php echo htmlspecialchars($inscription['mention_nom']); ?></td>
                    <td><?php echo htmlspecialchars($inscription['section_nom']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($inscription['date_inscription'])); ?></td>
                    <td class="table-actions">
                        <!-- Bouton Modifier -->
                        <a href="#" class="btn btn-primary" onclick="editInscription('<?php echo $inscription['code_inscription']; ?>', '<?php echo addslashes($inscription['matriculeEtudiant']); ?>', '<?php echo addslashes($inscription['codepromotion']); ?>', '<?php echo $inscription['code_mention']; ?>', '<?php echo $inscription['code_section']; ?>', '<?php echo $inscription['date_inscription']; ?>', '<?php echo addslashes($inscription['description']); ?>')">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <!-- Bouton Supprimer -->
                        <a href="../script/delete_inscription.php?id=<?php echo $inscription['code_inscription']; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription ?')">
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
        <h3 class="form-title">Modifier une inscription</h3>
        <div class="formulaire">
            <form id="editInscriptionForm" action="../script/update_inscription.php" method="POST">
                <input type="hidden" id="edit_id" name="id">
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="edit_Section">Section :</label>
                        <select name="Section" id="edit_Section" class="form-control" required>
                            <option value="">Sélectionner une section</option>
                            <?php 
                            // Ré-exécuter la requête car elle a pu être consommée
                            $sections = mysqli_query($con, "SELECT * FROM section");
                            while($section = mysqli_fetch_assoc($sections)): ?>
                            <option value="<?php echo $section['code_section']; ?>">
                                <?php echo htmlspecialchars($section['nomComplet']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-col">
                        <label for="edit_dapartement">Mention :</label>
                        <select name="dapartement" id="edit_dapartement" class="form-control" required>
                            <option value="">Sélectionner une mention</option>
                            <?php 
                            // Ré-exécuter la requête car elle a pu être consommée
                            $mentions = mysqli_query($con, "SELECT * FROM mention");
                            while($mention = mysqli_fetch_assoc($mentions)): ?>
                            <option value="<?php echo $mention['code_mention']; ?>">
                                <?php echo htmlspecialchars($mention['nomComplet']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="edit_promotion">Promotion :</label>
                        <select name="promotion" id="edit_promotion" class="form-control" required>
                            <option value="">Sélectionner une promotion</option>
                            <?php 
                            // Ré-exécuter la requête car elle a pu être consommée
                            $promotions = mysqli_query($con, "SELECT * FROM promotion");
                            while($promo = mysqli_fetch_assoc($promotions)): ?>
                            <option value="<?php echo $promo['sigle_promotion']; ?>">
                                <?php echo htmlspecialchars($promo['nomComplet']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-col">
                        <label for="edit_matricule">Matricule :</label>
                        <input type="text" id="edit_matricule" name="matricule" class="form-control" required placeholder="Matricule de l'étudiant">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="edit_dtinscription">Date d'inscription :</label>
                        <input type="date" id="edit_dtinscription" name="dtinscription" class="form-control" required>
                    </div>
                    
                    <div class="form-col">
                        <label for="edit_description">Description :</label>
                        <input type="text" id="edit_description" name="description" class="form-control" placeholder="Description de l'inscription">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success"><i class="fas fa-sync"></i> Mettre à jour</button>
                <button type="button" class="btn btn-secondary" onclick="cancelEdit()"><i class="fas fa-times"></i> Annuler</button>
            </form>
        </div>
    </div>
</div>

<script>
function editInscription(id, matricule, promotion, mention, section, date_inscription, description) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_matricule').value = matricule;
    document.getElementById('edit_promotion').value = promotion;
    document.getElementById('edit_dapartement').value = mention;
    document.getElementById('edit_Section').value = section;
    document.getElementById('edit_dtinscription').value = date_inscription;
    document.getElementById('edit_description').value = description;
    
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