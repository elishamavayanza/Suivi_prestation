    <div class="formulaire">
        
        <form id="signupForm" action="../script/addEnseignant.php" method="POST">
             <label for="username">Matricule :</label>
            <input type="text" id="matricule" name="matricule" required>
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>
            <label for="prenom">Post-Nom :</label>
            <input type="text" id="post_nom" name="postnom" required>
            <label for="prenom">Prenom :</label>
            <input type="text" id="prenom" name="prenom" required>
            <label for="genre">Genre :</label> 
            <select name="genre" id="genre">
                <option value="masculn">Masculin</option>
                <option value="fiminin">Feminin</option>
            </select>
            <label for="date_naiss">Date naissance :</label>
            <input type="date" id="date" name="date_naissance" required>
            <label for="etat">Etat civil :</label>
            <input type="etat" id="etat" name="etat_civil" required>
            <label for="nationalite">Nationalite :</label>
            <input type="nationalite" id="nationalite" name="nationalite" required>
            <label for="adresse">Adresse :</label>
            <input type="text" id="adresse" name="adresse" required>
            <label for="mail">E-Mail :</label>
            <input type="mail" id="mail" name="mail" required> 
            <label for="telephone">Telephone :</label>
            <input type="text" id="telephone" name="telephone" required>
            <label for="grade">Grade :</label>
            <input type="text" id="grade" name="grade" required>
            <label for="domaine">Domaine :</label>
            <input type="text" id="domain" name="domaine" required>           
            <label for="profil">Photo profil :</label>
            <input type="file" id="file" name="file" required>
            <button type="submit">Ajouter Enseignant</button>
        </form>
    </div>
    
