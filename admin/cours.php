<?php 
    include("nav_dash.php");
    
    // Connexion à la base de données
    include("../script/connexion.php");
    
    // Requête pour obtenir tous les cours
    $cours_result = mysqli_query($con, "SELECT c.*, m.nomComplet as mention_nom FROM cours c LEFT JOIN mention m ON c.code_mention = m.code_mention");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Gestion des Cours</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>Cours</li>
        </ul>
    </div>

    <div class="admin-form">
        <h3 class="form-title">Ajouter un nouveau cours</h3>
        <?php
            include("../formulaire/formcours.php"); 
        ?>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Liste des cours</h3>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Nom complet</th>
                    <th>Nombre d'heures</th>
                    <th>Mention</th>
                    <th>Promotion</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($cour = mysqli_fetch_assoc($cours_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($cour['code_cours']); ?></td>
                    <td><?php echo htmlspecialchars($cour['nomComplet']); ?></td>
                    <td><?php echo htmlspecialchars($cour['nbreHeure']); ?></td>
                    <td><?php echo htmlspecialchars($cour['mention_nom']); ?></td>
                    <td><?php echo htmlspecialchars($cour['promotion']); ?></td>
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