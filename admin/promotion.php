<?php 
    include("nav_dash.php");
    
    // Connexion à la base de données
    include("../script/connexion.php");
    
    // Requête pour obtenir toutes les promotions
    $promotions_result = mysqli_query($con, "
        SELECT p.*, m.nomComplet as mention_nom 
        FROM promotion p 
        LEFT JOIN mention m ON p.code_mention = m.code_mention
    ");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Gestion des Promotions</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>Promotions</li>
        </ul>
    </div>

    <div class="admin-form">
        <h3 class="form-title">Ajouter une nouvelle promotion</h3>
        <?php
            include("../formulaire/formpromotion.php"); 
        ?>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Liste des promotions</h3>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Sigle</th>
                    <th>Nom complet</th>
                    <th>Mention</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($promotion = mysqli_fetch_assoc($promotions_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($promotion['id']); ?></td>
                    <td><?php echo htmlspecialchars($promotion['sigle_promotion']); ?></td>
                    <td><?php echo htmlspecialchars($promotion['nomComplet']); ?></td>
                    <td><?php echo htmlspecialchars($promotion['mention_nom']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($promotion['dtcreation'])); ?></td>
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