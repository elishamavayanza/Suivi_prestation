<?php 
    include("nav_dash.php");
    
    // Connexion à la base de données
    include("../script/connexion.php");
    
    // Requête pour obtenir tous les étudiants
    $etudiants_result = mysqli_query($con, "SELECT * FROM etudiant");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Gestion des Étudiants</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>Étudiants</li>
        </ul>
    </div>

    <div class="admin-form">
        <h3 class="form-title">Ajouter un nouvel étudiant</h3>
        <?php
            include("../formulaire/formAddEtudiant.php"); 
        ?>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Liste des étudiants</h3>
        <table>
            <thead>
                <tr>
                    <th>Matricule</th>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Genre</th>
                    <th>Date de naissance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($etudiant = mysqli_fetch_assoc($etudiants_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($etudiant['matriculeEtudiant']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['postnom'] . ' ' . $etudiant['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['adresseMail']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['telephone']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['genre']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($etudiant['dtnaissance'])); ?></td>
                    <td class="table-actions">
                        <button class="btn btn-primary"><i class="fas fa-edit"></i> Modifier</button>
                        <button class="btn btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php 
    include("nav_footer_dash.php");
?>