<?php 
    include("nav_dash.php");
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
                    <th>Mention</th>
                    <th>Promotion</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ETU001</td>
                    <td>Pierre Martin</td>
                    <td>pierre.martin@univ.edu</td>
                    <td>+243 999 111 222</td>
                    <td>Informatique</td>
                    <td>L1</td>
                    <td class="table-actions">
                        <button class="btn btn-primary"><i class="fas fa-edit"></i> Modifier</button>
                        <button class="btn btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
                    </td>
                </tr>
                <tr>
                    <td>ETU002</td>
                    <td>Sophie Dubois</td>
                    <td>sophie.dubois@univ.edu</td>
                    <td>+243 888 222 333</td>
                    <td>Mathématiques</td>
                    <td>L2</td>
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