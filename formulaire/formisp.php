<div class="formulaire">
    <form id="ispForm" action="../script/addisp.php" method="POST">
        <div class="form-row">
            <div class="form-col">
                <label for="arrete">Numéro ministère :</label>
                <input type="text" id="arrete" name="arrete" class="form-control" required placeholder="Ex: Min-ESU/CAB-234">
            </div>
            
            <div class="form-col">
                <label for="sigle">Sigle :</label>
                <input type="text" id="sigle" name="sigle" class="form-control" required placeholder="Ex: ISP-MB">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="nomComplet">Dénomination complète :</label>
                <input type="text" id="nomComplet" name="nomComplet" class="form-control" required placeholder="Ex: Institut Supérieur Pédagogique de Muhanga">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="description">Description :</label>
                <input type="text" id="description" name="description" class="form-control" required placeholder="Description de l'ISP">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="boitepostale">Boîte postale :</label>
                <input type="text" id="boitepostale" name="boitepostale" class="form-control" required placeholder="Ex: B.P. 234">
            </div>
            
            <div class="form-col">
                <label for="dtcreation">Date de création :</label>
                <input type="date" id="dtcreation" name="dtcreation" class="form-control" required>
            </div>
        </div>
        
        <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Ajouter ISP</button>
    </form>
</div>