<?php
// Connexion à la base de données
include("../script/connexion.php");

// Récupérer les mentions pour la liste déroulante
$mentions = mysqli_query($con, "SELECT * FROM mention");
?>

<div class="formulaire">
    <form id="promotionForm" action="../script/addpromotion.php" method="POST">
        <div class="form-row">
            <div class="form-col">
                <label for="codedepart">Mention :</label>
                <select name="codedepart" id="codedepart" class="form-control" required>
                    <option value="">Sélectionner une mention</option>
                    <?php while($mention = mysqli_fetch_assoc($mentions)): ?>
                    <option value="<?php echo $mention['code_mention']; ?>">
                        <?php echo htmlspecialchars($mention['nomComplet']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-col">
                <label for="sigle">Sigle :</label>
                <input type="text" id="sigle" name="sigle" class="form-control" required placeholder="Ex: L1GI">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="nomComplet">Dénomination complète :</label>
                <input type="text" id="nomComplet" name="nomComplet" class="form-control" required placeholder="Ex: Première année de Licence en Gestion Informatique">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="description">Description :</label>
                <input type="text" id="description" name="description" class="form-control" required placeholder="Description de la promotion">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="dtcreation">Date de création :</label>
                <input type="date" id="dtcreation" name="dtcreation" class="form-control" required>
            </div>
        </div>
        
        <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Ajouter Promotion</button>
    </form>
</div>