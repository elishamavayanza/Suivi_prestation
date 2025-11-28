<?php 
    include("nav_dash.php");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Gestion des Horaires</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>Horaires</li>
        </ul>
    </div>

    <div class="admin-form">
        <h3 class="form-title">Programmer un cours</h3>
        <?php
            include("../formulaire/formhoraire.php"); 
        ?>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Emploi du temps</h3>
        <table>
            <thead>
                <tr>
                    <th>Cours</th>
                    <th>Mention</th>
                    <th>Promotion</th>
                    <th>Jour & Heure</th>
                    <th>Enseignant</th>
                    <th>Site</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Algorithmique</td>
                    <td>Informatique</td>
                    <td>L1</td>
                    <td>Lundi 08h00-10h00</td>
                    <td>Jean Dupont</td>
                    <td>Muhanga</td>
                    <td class="table-actions">
                        <button class="btn btn-primary"><i class="fas fa-edit"></i> Modifier</button>
                        <button class="btn btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
                    </td>
                </tr>
                <tr>
                    <td>Mathématiques</td>
                    <td>Mathématiques</td>
                    <td>L2</td>
                    <td>Mardi 10h00-12h00</td>
                    <td>Marie Lambert</td>
                    <td>Muhanga</td>
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