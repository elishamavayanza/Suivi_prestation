    <div class="formulaire">
        <form id="signupForm" action="../script/addEtudiant.php" method="POST">
            <div class="form-group">
                <label for="matricule">Matricule</label>
                <input type="text" id="matricule" name="matricule" required class="form-control">
            </div>
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required class="form-control">
            </div>
            <div class="form-group">
                <label for="post_nom">Post-Nom</label>
                <input type="text" id="post_nom" name="postnom" required class="form-control">
            </div>
            <div class="form-group">
                <label for="prenom">Prenom</label>
                <input type="text" id="prenom" name="prenom" required class="form-control">
            </div>
            <div class="form-group">
                <label for="genre">Genre</label>
                <select name="genre" id="genre" class="form-control">
                    <option value="masculin">Masculin</option>
                    <option value="feminin">Feminin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="date_naissance">Date naissance</label>
                <input type="date" id="date_naissance" name="date_naissance" required class="form-control">
            </div>
            <div class="form-group">
                <label for="etat_civil">Etat civil</label>
                <input type="text" id="etat_civil" name="etat_civil" required class="form-control">
            </div>
            <div class="form-group">
                <label for="nationalite">Nationalite</label>
                <input type="text" id="nationalite" name="nationalite" required class="form-control">
            </div>
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" id="adresse" name="adresse" required class="form-control">
            </div>
            <div class="form-group">
                <label for="telephone">Telephone</label>
                <input type="tel" id="telephone" name="telephone" required class="form-control">
            </div>
            <div class="form-group">
                <label for="mail">E-Mail</label>
                <input type="email" id="mail" name="mail" required class="form-control">
            </div>
            <div class="form-group">
                <label for="file">Photo profil</label>
                <input type="file" id="file" name="file" required class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
    </div>