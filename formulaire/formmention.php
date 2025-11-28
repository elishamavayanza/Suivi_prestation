<?php
// Connexion à la base de données
include("../script/connexion.php");

// Récupérer les sections pour la liste déroulante
$sections = mysqli_query($con, "SELECT * FROM section");
?>

<div class="formulaire">
    <form id="mentionForm" action="../script/addmention.php" method="POST">
        <div class="form-row">
            <div class="form-col">
                <label for="codefac">Section :</label>
                <select name="codefac" id="codefac" class="form-control" required>
                    <option value="">Sélectionner une section</option>
                    <?php while($section = mysqli_fetch_assoc($sections)): ?>
                    <option value="<?php echo $section['code_section']; ?>">
                        <?php echo htmlspecialchars($section['nomComplet']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-col">
                <label for="sigle">Sigle :</label>
                <input type="text" id="sigle" name="sigle" class="form-control" required placeholder="Ex: GI">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="nomComplet">Dénomination complète :</label>
                <input type="text" id="nomComplet" name="nomComplet" class="form-control" required placeholder="Ex: Gestion Informatique">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="description">Description :</label>
                <input type="text" id="description" name="description" class="form-control" required placeholder="Description de la mention">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="dtcreation">Date de création :</label>
                <input type="date" id="dtcreation" name="dtcreation" class="form-control" required>
            </div>
        </div>
        
        <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Ajouter Mention</button>
    </form>
</div>