<div class="formulaire">
    <form id="signupForm" action="../script/addEnseignant.php" method="POST">
        <div class="form-row">
            <div class="form-col">
                <label for="matricule">Matricule :</label>
                <input type="text" id="matricule" name="matricule" class="form-control" required>
            </div>
            
            <div class="form-col">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" class="form-control" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="post_nom">Post-Nom :</label>
                <input type="text" id="post_nom" name="postnom" class="form-control" required>
            </div>
            
            <div class="form-col">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" class="form-control" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="genre">Genre :</label> 
                <select name="genre" id="genre" class="form-control">
                    <option value="masculin">Masculin</option>
                    <option value="feminin">Féminin</option>
                </select>
            </div>
            
            <div class="form-col">
                <label for="date_naiss">Date de naissance :</label>
                <input type="date" id="date_naiss" name="date_naissance" class="form-control" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="etat">État civil :</label>
                <input type="text" id="etat" name="etat_civil" class="form-control" required>
            </div>
            
            <div class="form-col">
                <label for="nationalite">Nationalité :</label>
                <input type="text" id="nationalite" name="nationalite" class="form-control" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="adresse">Adresse :</label>
                <input type="text" id="adresse" name="adresse" class="form-control" required>
            </div>
            
            <div class="form-col">
                <label for="mail">E-Mail :</label>
                <input type="email" id="mail" name="mail" class="form-control" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="telephone">Téléphone :</label>
                <input type="text" id="telephone" name="telephone" class="form-control" required>
            </div>
            
            <div class="form-col">
                <label for="grade">Grade :</label>
                <input type="text" id="grade" name="grade" class="form-control" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <label for="domaine">Domaine :</label>
                <input type="text" id="domain" name="domaine" class="form-control" required>
            </div>
            
            <div class="form-col">
                <label for="file">Photo de profil :</label>
                <input type="file" id="file" name="file" class="form-control" required>
            </div>
        </div>
        
        <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Ajouter Enseignant</button>
    </form>
</div>