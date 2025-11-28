<?php 
    include("nav_dash.php");
    
    // Connexion à la base de données
    include("../script/connexion.php");
    
    // Requête pour obtenir toutes les mentions
    $mentions_result = mysqli_query($con, "
        SELECT m.*, s.nomComplet as section_nom 
        FROM mention m 
        LEFT JOIN section s ON m.code_section = s.code_section
    ");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Gestion des Mentions</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>Mentions</li>
        </ul>
    </div>

    <div class="admin-form">
        <h3 class="form-title">Ajouter une nouvelle mention</h3>
        <?php
            include("../formulaire/formmention.php"); 
        ?>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Liste des mentions</h3>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Sigle</th>
                    <th>Nom complet</th>
                    <th>Section</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($mention = mysqli_fetch_assoc($mentions_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($mention['code_mention']); ?></td>
                    <td><?php echo htmlspecialchars($mention['sigle']); ?></td>
                    <td><?php echo htmlspecialchars($mention['nomComplet']); ?></td>
                    <td><?php echo htmlspecialchars($mention['section_nom']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($mention['dtcreation'])); ?></td>
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