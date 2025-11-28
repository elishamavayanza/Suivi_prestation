<div class="formulaire">
    <form id="anneeForm" action="../script/addyear.php" method="POST">
        <div class="form-row">
            <div class="form-col">
                <label for="annee_academique">Année académique :</label>
                <input type="text" id="annee_academique" name="annee_academique" class="form-control" placeholder="Ex: 2025-2026" required>
            </div>
            
            <div class="form-col">
                <label for="date_debut">Date de début :</label>
                <input type="date" id="date_debut" name="date_debut" class="form-control" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="date_fin">Date de fin :</label>
                <input type="date" id="date_fin" name="date_fin" class="form-control" required>
            </div>
        </div>
        
        <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Ajouter Année</button>
    </form>
</div>