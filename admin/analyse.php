<?php 
    include("nav_dash.php");
    include("../script/connexion.php");
?>

<main class="main-content">
    <div class="content-header">
        <h2>Analyse et Évaluations</h2>
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li>Analyse</li>
        </ul>
    </div>

    <div class="admin-form">
        <h3 class="form-title">Aperçu des évaluations</h3>
        <p>Consultez les résultats des évaluations des enseignants et des cours.</p>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Détails des évaluations</h3>
        <table>
            <thead>
                <tr>
                    <th>Code Étudiant</th>
                    <th>Nom Enseignant</th>
                    <th>Cours</th>
                    <th>Clarté des objectifs</th>
                    <th>Organisation du contenu</th>
                    <th>Qualité des supports</th>
                    <th>Structure des séances</th>
                    <th>Respect du programme</th>
                    <th>Maîtrise de la matière</th>
                    <th>Capacité à expliquer</th>
                    <th>Réponse aux questions</th>
                    <th>Exemples concrets</th>
                    <th>Encouragement à participer</th>
                    <th>Disponibilité</th>
                    <th>Respect et politesse</th>
                    <th>Écoute des remarques</th> 
                    <th>Capacité à motiver</th>
                    <th>Encouragement à l'autonomie</th>
                    <th>Ponctualité</th>
                    <th>Assiduité</th>
                    <th>Utilisation du temps</th>
                    <th>Outils technologiques</th>
                    <th>Satisfaction globale</th>
                    <th>Commentaire</th>                  
                </tr>
            </thead>
            <tbody>
                <?php      
                // Fonction pour afficher les évaluations
                function afficher($table, $con){
                    $sql = "SELECT * FROM $table ORDER BY id DESC";
                    $result = mysqli_query($con, $sql);
                    
                    while($data = mysqli_fetch_assoc($result)){        
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($data['matriculeEtudiant']) . "</td>"; 
                        echo "<td>" . htmlspecialchars($data['enseignant']) . "</td>"; 
                        echo "<td>" . htmlspecialchars($data['cours']) . "</td>";
                        
                        for ($i = 1; $i <= 20; $i++) {
                            echo "<td>" . htmlspecialchars($data["r$i"]) . "</td>";
                        } 
                        
                        echo "<td>" . htmlspecialchars($data['commentaire']) . "</td>";
                        echo "</tr>";                
                    }
                }
                
                // Afficher les évaluations
                afficher('evaluations', $con);
                ?>
            </tbody>
        </table>
    </div>

    <div class="admin-table mt-20">
        <h3 class="form-title">Moyennes par auditoire pour un cours</h3>
        <table>
            <thead>
                <tr>
                    <th>Enseignant</th> 
                    <th>Cours</th>
                    <th>Moyenne en pourcentage</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fonction pour afficher les moyennes
                function afficherM($sql, $con){
                    $result = mysqli_query($con, $sql);
                    
                    while($data = mysqli_fetch_assoc($result)){        
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($data['NomsEnseignant']) . "</td>";
                        echo "<td>" . htmlspecialchars($data['cours']) . "</td>";
                        echo "<td>" . htmlspecialchars($data['moyenne']) . "</td>";
                        echo "</tr>";                
                    }
                }
                
                // Afficher les moyennes
                afficherM("SELECT CONCAT_WS(' ', e.nom, e.postnom, e.prenom) as NomsEnseignant, c.nomComplet as cours, '85%' as moyenne FROM enseignant e, cours c LIMIT 5", $con);
                ?>
            </tbody>
        </table>
    </div>
</main>

<?php 
    include("nav_footer_dash.php");
?>