
    <div class="formulaire">
      
        <form id="connexionForm" action="../script/addisp.php" method="POST">
            <label for="arreter">Numero min</label>
            <input type="text" id="arrete" name="arrete" required placeholder="">
            <label for="sigle">Sigle</label>
            <input type="Text" id="sigle" name="sigle" required placeholder="">
            <label for="nomComplet">Denomination</label>
            <input type="Text" id="nomComplet" name="nomComplet" required placeholder="">
            <label for="adresse">Description</label>
            <input type="Text" id="description" name="description" required placeholder="">
             <label for="adresse">Boite Postal</label>
            <input type="Text" id="boitepostale" name="boitepostale" required placeholder="">
             <label for="adresse">Date Creation</label>
            <input type="date" id="dtcreation" name="dtcreation" required placeholder="">
            <button type="submit">Ajouter ISP</button>
        </form>
    </div>
