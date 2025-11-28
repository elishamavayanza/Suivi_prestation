<?php 
    include("nav_dash.php");
    
    // Connexion à la base de données
    include("../script/connexion.php");
    
    // Requête pour obtenir tous les enseignants
    $enseignants_result = mysqli_query($con, "SELECT * FROM enseignant");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Gestion des Enseignants</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>Enseignants</li>
        </ul>
    </div>

    <div class="admin-form">
        <h3 class="form-title">Ajouter un nouvel enseignant</h3>
        <?php
            include("../formulaire/formAddEnseignant.php"); 
        ?>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Liste des enseignants</h3>
        <table>
            <thead>
                <tr>
                    <th>Matricule</th>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Grade</th>
                    <th>Domaine</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($enseignant = mysqli_fetch_assoc($enseignants_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($enseignant['matriculeEnseignant']); ?></td>
                    <td><?php echo htmlspecialchars($enseignant['nom'] . ' ' . $enseignant['postnom'] . ' ' . $enseignant['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($enseignant['adresseMail']); ?></td>
                    <td><?php echo htmlspecialchars($enseignant['telephone']); ?></td>
                    <td><?php echo htmlspecialchars($enseignant['grade']); ?></td>
                    <td><?php echo htmlspecialchars($enseignant['domainEnseignant']); ?></td>
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