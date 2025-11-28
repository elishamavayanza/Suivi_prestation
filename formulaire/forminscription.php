<?php
// Connexion à la base de données
include("../script/connexion.php");

// Récupérer les données pour les listes déroulantes
$sections = mysqli_query($con, "SELECT * FROM section");
$mentions = mysqli_query($con, "SELECT * FROM mention");
$promotions = mysqli_query($con, "SELECT * FROM promotion");
?>

<div class="formulaire">
    <form id="inscriptionForm" action="../script/inscrire.php" method="POST">
        <div class="form-row">
            <div class="form-col">
                <label for="Section">Section :</label>
                <select name="Section" id="Section" class="form-control" required>
                    <option value="">Sélectionner une section</option>
                    <?php while($section = mysqli_fetch_assoc($sections)): ?>
                    <option value="<?php echo $section['code_section']; ?>">
                        <?php echo htmlspecialchars($section['nomComplet']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-col">
                <label for="dapartement">Mention :</label>
                <select name="dapartement" id="dapartement" class="form-control" required>
                    <option value="">Sélectionner une mention</option>
                    <?php while($mention = mysqli_fetch_assoc($mentions)): ?>
                    <option value="<?php echo $mention['code_mention']; ?>">
                        <?php echo htmlspecialchars($mention['nomComplet']); ?>
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
                    <?php while($promo = mysqli_fetch_assoc($promotions)): ?>
                    <option value="<?php echo $promo['sigle_promotion']; ?>">
                        <?php echo htmlspecialchars($promo['nomComplet']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-col">
                <label for="matricule">Matricule :</label>
                <input type="text" id="matricule" name="matricule" class="form-control" required placeholder="Matricule de l'étudiant">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="dtinscription">Date d'inscription :</label>
                <input type="date" id="dtinscription" name="dtinscription" class="form-control" required>
            </div>
            
            <div class="form-col">
                <label for="description">Description :</label>
                <input type="text" id="description" name="description" class="form-control" placeholder="Description de l'inscription">
            </div>
        </div>
        
        <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Inscrire Étudiant</button>
    </form>
</div>