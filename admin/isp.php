<?php 
    include("nav_dash.php");
    
    // Connexion à la base de données
    include("../script/connexion.php");
    
    // Requête pour obtenir tous les ISP
    $isps_result = mysqli_query($con, "SELECT * FROM isp");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Gestion des ISP</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>ISP-Muhanga</li>
        </ul>
    </div>

    <div class="admin-form">
        <h3 class="form-title">Ajouter un nouvel ISP</h3>
        <?php
            include("../formulaire/formisp.php"); 
        ?>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Liste des ISP</h3>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Sigle</th>
                    <th>Nom complet</th>
                    <th>Date de création</th>
                    <th>Boîte postale</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($isp = mysqli_fetch_assoc($isps_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($isp['code_isp']); ?></td>
                    <td><?php echo htmlspecialchars($isp['sigle']); ?></td>
                    <td><?php echo htmlspecialchars($isp['nomComplet']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($isp['dt_creation'])); ?></td>
                    <td><?php echo htmlspecialchars($isp['boitepostal']); ?></td>
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