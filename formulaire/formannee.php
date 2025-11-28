
    <div  class="formulaire">
        <!--div class="modal" id="overlay">
        <button id="closeBtn" onclick="closeModal()">X</button>
        <div class="modalcontent"-->
            <form id="connexionForm" action="../script/addyear.php" method="POST">
            <h2>Nouvelle annee academique</h2>
            <label for="arreter">Code Annee</label>
            <input type="text" id="arrete" name="code_annee" required placeholder="">
            <label for="_date">Debut </label>
            <input type="date" id="_date" name="dt_debut" required placeholder="">
            <label for="date_">Fin : </label>
            <input type="date" id="date_" name="dt_fin" required placeholder="">
            <label for="description">Description : </label>
            <input type="description" id="description" name="description" required placeholder="">
            <button type="submit"> Nouvelle Annee</button>
        </form>
        <!--/div>
        </div-->
    </div>
