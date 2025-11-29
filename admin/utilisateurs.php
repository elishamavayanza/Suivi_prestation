<?php 
    include("nav_dash.php");
    include("../script/connexion.php");
    
    // Définir la page active pour la navigation
    $current_page = basename($_SERVER['PHP_SELF']);
    
    // Messages d'erreurs et de succès
    $message = '';
    $message_type = '';
// Traitement du formulaire d'ajout d'utilisateur
    if (isset($_POST['add_user'])) {
        $matricule = mysqli_real_escape_string($con, $_POST['matricule']);
        $username = mysqli_real_escape_string($con, $_POST['username']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $role = mysqli_real_escape_string($con, $_POST['role']);
        
        // Vérifier si l'utilisateur existe déjà
        $check_query = "SELECT * FROM utilisateur WHERE matricule='$matricule' OR username='$username'";
        $check_result = mysqli_query($con, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $message = "Un utilisateur avec ce matricule ou ce nom d'utilisateur existe déjà.";
            $message_type = "error";
        } else {
            // Insérer lenouvel utilisateur
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_query = "INSERT INTO utilisateur (matricule, username, password, role) VALUES ('$matricule', '$username', '$hashed_password', '$role')";
            
            if (mysqli_query($con, $insert_query)){
$message = "Utilisateur ajouté avec succès.";
                $message_type = "success";
            } else {
                $message = "Erreur lors de l'ajout de l'utilisateur : " . mysqli_error($con);
                $message_type = "error";
            }
        }
    }
    
   // Traitement dela suppression d'utilisateur
    if (isset($_GET['delete'])) {
        $id = intval($_GET['delete']);
        $delete_query = "DELETE FROM utilisateur WHERE id=$id";
        
        if (mysqli_query($con, $delete_query)) {
            $message = "Utilisateur suppriméavec succès.";
            $message_type = "success";
        } else {
            $message = "Erreur lors de la suppression de l'utilisateur : " . mysqli_error($con);
            $message_type = "error";
        }
        
// Redirection pour éviter le rechargement de la suppression
        header("Location: utilisateurs.php");
        exit();
    }
    
    // Traitement de la modification d'utilisateur
    if (isset($_POST['edit_user'])) {
        $id = intval($_POST['id']);
        $matricule = mysqli_real_escape_string($con, $_POST['matricule']);
        $username= mysqli_real_escape_string($con, $_POST['username']);
        $role= mysqli_real_escape_string($con, $_POST['role']);
        
        // Mettre à jour les informations de l'utilisateur
        $update_query= "UPDATE utilisateur SET matricule='$matricule', username='$username', role='$role'WHERE id=$id";
        
        if (mysqli_query($con,$update_query)) {
            // Si unnouveau mot de passe est fourni, le mettre à jour
            if (!empty($_POST['password'])) {
                $new_password = mysqli_real_escape_string($con, $_POST['password']);
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
               $password_query = "UPDATE utilisateur SET password='$hashed_password' WHERE id=$id";
                mysqli_query($con, $password_query);
            }
            
            $message = "Utilisateur modifié avec succès.";
            $message_type = "success";
       } else {
            $message = "Erreur lors de la modificationde l'utilisateur : " . mysqli_error($con);
            $message_type = "error";
        }
        
// Redirection pour actualiser la liste
        header("Location: utilisateurs.php");
        exit();
    }
    
    // Récupérertous les utilisateurs
    $users_query = "SELECT *FROM utilisateur ORDER BY id DESC";
    $users_result = mysqli_query($con, $users_query);
?>

<main class="main-content">
    <style>
.btn-small {
            padding: 5px 10px;
            font-size: 0.85rem;
            border-radius: 3px;
}
        
        .btn-edit {
            background-color: #3498db;
            color:white;
            border: none;
           margin-right: 5px;
        }
        
        .btn-edit:hover {
            background-color: #2980b9;
        }
        
        .btn-danger {
background-color: #e74c3c;
            color: white;
            border: none;
            text-decoration: none;
           display: inline-block;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }
        
        .modal {
            display:none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
           padding: 20px;
border: 1px solid #888;
            width: 50%;
            border-radius: 5px;
           position: relative;
            color: #333;
        }
        
        .close {
            color: #aaa;
            float: right;
           font-size: 28px;
            font-weight: bold;
            position: absolute;
            right: 15px;
            top: 10px;
        }
        
        .close:hover,
       .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
       }
        
        @media (max-width: 768px) {
            .modal-content {
                width: 90%;
            }
        }
    </style>
    <div class="content-header">
<h2>Gestion des Utilisateurs</h2>
        <ul class="breadcrumb">
<li><a href="index.php">Accueil</a></li>
            <li>Gérer les Utilisateurs</li>
        </ul>
    </div>
    
<?php if ($message): ?>
<div class="alert alert-<?php echo $message_type; ?>">
        <?php echo $message; ?>
    </div>
    <?php endif; ?>
    
    <div class="admin-form">
        <h3 class="form-title">Ajouter un nouvel utilisateur</h3>
        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group">
<label for="matricule">Matricule</label>
                    <input type="text" id="matricule" name="matricule" required class="form-control">
               </div>
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
<input type="text" id="username" name="username" required class="form-control">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="role">Rôle</label>
                    <select id="role" name="role" required class="form-control">
                        <option value="">Sélectionner unrôle</option>
                        <option value="admin">Administrateur</option>
                        <option value="etudiant">Étudiant</option>
                        <option value="enseignant">Enseignant</option>
                        <option value="Chefpromotion">Chef de Promotion</option>
                        <option value="Chefdesection">Chef de Section</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" name="add_user" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter l'utilisateur</button>
            </div>
        </form>
    </div>
    
    <div class="admin-table mt-20">
        <h3 class="form-title">Liste des utilisateurs</h3>
        <table>
<thead>
                <tr>
                    <th>ID</th>
<th>Matricule</th>
                    <th>Nom d'utilisateur</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
               <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['matricule']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                   <td><?php echo htmlspecialchars($user['role']); ?></td>
<td>
                        <button class="btn btn-small btn-edit" onclick="editUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['matricule']); ?>', '<?php echo htmlspecialchars($user['username']);?>', '<?php echo htmlspecialchars($user['role']); ?>')">
<i class="fas fa-edit"></i> Modifier
                        </button>
                        <a href="?delete=<?php echo $user['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                            <i class="fas fa-trash"></i> Supprimer
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Modal pour modifier un utilisateur -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3 class="form-title">Modifier l'utilisateur</h3>
        <form method="POST" action="">
            <input type="hidden" id="edit_id" name="id">
            
            <div class="form-group">
                <label for="edit_matricule">Matricule</label>
                <input type="text" id="edit_matricule" name="matricule" required>
            </div>
            
            <div class="form-group">
                <label for="edit_username">Nom d'utilisateur</label>
                <input type="text"id="edit_username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="edit_password">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                <input type="password" id="edit_password" name="password">
            </div>
            
            <div class="form-group">
                <label for="edit_role">Rôle</label>
                <select id="edit_role" name="role" required>
                    <option value="">Sélectionner un rôle</option>
                    <option value="admin">Administrateur</option>
                    <option value="etudiant">Étudiant</option>
                    <option value="enseignant">Enseignant</option>
                    <option value="Chefpromotion">Chef de Promotion</option>
                   <option value="Chefdesection">Chef de Section</option>
                </select>
            </div>
            
            <div class="form-group">
                <button type="submit" name="edit_user" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer lesmodifications</button>
                <button type="button" class="btn btn-secondary" id="cancelEdit">
                    <i class="fas fa-times"></i> Annuler
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fonction pour ouvrirle modal d'édition
    function editUser(id, matricule, username, role) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_matricule').value = matricule;
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_role').value = role;
        
        document.getElementById('editModal').style.display = 'block';
   }
    
    // Fermer le modal
    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('editModal');
        var span = document.getElementsByClassName('close')[0];
        var cancelBtn = document.getElementById('cancelEdit');
        
        span.onclick = function() {
            modal.style.display = 'none';
        }
        
        cancelBtn.onclick = function() {
            modal.style.display = 'none';
        }
        
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    });
</script>

<?php include("nav_footer_dash.php"); ?>