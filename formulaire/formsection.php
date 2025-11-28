<?php
// Connexion à la base de données
include("../script/connexion.php");

// Récupérer les ISP pour la liste déroulante
$isps = mysqli_query($con, "SELECT * FROM isp");
?>

<div class="formulaire">
    <form id="sectionForm" action="../script/addsection.php" method="POST">
        <div class="form-row">
            <div class="form-col">
                <label for="fac">ISP :</label>
                <select name="code" id="fac" class="form-control" required>
                    <option value="">Sélectionner un ISP</option>
                    <?php while($isp = mysqli_fetch_assoc($isps)): ?>
                    <option value="<?php echo $isp['code_isp']; ?>">
                        <?php echo htmlspecialchars($isp['nomComplet']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-col">
                <label for="sigle">Sigle :</label>
                <input type="text" id="sigle" name="sigle" class="form-control" required placeholder="Ex: IG">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="nomComplet">Dénomination complète :</label>
                <input type="text" id="nomComplet" name="nomComplet" class="form-control" required placeholder="Ex: Informatique de Gestion">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="description">Description :</label>
                <input type="text" id="description" name="description" class="form-control" required placeholder="Description de la section">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="dtcreation">Date de création :</label>
                <input type="date" id="dtcreation" name="dtcreation" class="form-control" required>
            </div>
        </div>
        
        <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Ajouter Section</button>
    </form>
</div>