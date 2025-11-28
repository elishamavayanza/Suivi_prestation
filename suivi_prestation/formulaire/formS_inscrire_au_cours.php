
    <div class="formulaire">
        
        <form id="connexionForm" action="" method="POST">
            <label for="fac">Section : </label>
            <select name="fac" id="fac">
                <option value="">..........</option>
            </select>
            <label for="depart">Option : </label>
            <select name="dapart" id="depart">
                <option value="">..........</option>
            </select>
            <label for="promotion">Promotion : </label>
            <select name="promotion" id="promotion">
                <option value=""> ........... </option>
            </select>
            <label for="code">Code cours</label>
             <select name="codeCours" id="codecours">
                <option value=""> ........... </option>
            </select>
            <label for="nomComplet">Date debut </label>
            <input type="date" id="datedebut" name="datedebut" required placeholder="">
            <label for="nomComplet">Date fin </label>
            <input type="date" id="datefin" name="datefin" required placeholder="">
            <label for="description">Description : </label>
            <input type="text" id="description" name="description" required placeholder="">
             <label for="sigle">Matricule Etudiant </label>
            <input type="text" id="sigle" name="mat_enseignant" required placeholder="">
            <label for="sigle"> Titulaire du cours </label>
            <input type="text" id="sigle" name="mat_enseignant" required placeholder="">
            <button type="submit">preogrammer Cours</button>
        </form>
    </div>
