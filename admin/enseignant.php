<?php 
    include("nav_dash.php");
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
                <tr>
                    <td>ENS001</td>
                    <td>Jean Dupont</td>
                    <td>jean.dupont@univ.edu</td>
                    <td>+243 999 888 777</td>
                    <td>Professeur titulaire</td>
                    <td>Informatique</td>
                    <td class="table-actions">
                        <button class="btn btn-primary"><i class="fas fa-edit"></i> Modifier</button>
                        <button class="btn btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
                    </td>
                </tr>
                <tr>
                    <td>ENS002</td>
                    <td>Marie Lambert</td>
                    <td>marie.lambert@univ.edu</td>
                    <td>+243 888 777 666</td>
                    <td>Maître assistant</td>
                    <td>Mathématiques</td>
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