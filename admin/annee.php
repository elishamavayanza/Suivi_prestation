<?php 
    include("nav_dash.php");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Gestion des Années Académiques</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>Années académiques</li>
        </ul>
    </div>

    <div class="admin-form">
        <h3 class="form-title">Ajouter une nouvelle année académique</h3>
        <?php
            include("../formulaire/formannee.php"); 
        ?>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Liste des années académiques</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Année académique</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>2024-2025</td>
                    <td>01/10/2024</td>
                    <td>30/06/2025</td>
                    <td class="table-actions">
                        <button class="btn btn-primary"><i class="fas fa-edit"></i> Modifier</button>
                        <button class="btn btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>2025-2026</td>
                    <td>01/10/2025</td>
                    <td>30/06/2026</td>
                    <td class="table-actions">
                        <button class="btn btn-primary"><i class="fas fa-edit"></i> Modifier</button>
                        <button class="btn btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</main>

<?php 
    include("nav_footer_dash.php");
?>