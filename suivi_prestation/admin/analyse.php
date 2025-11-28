
<?php 
    include("nav_dash.php");
    include("../config/connexion.php");
    ?>
<div class="content-body">
    <div>
        <?php 
            include("nav.php");
        ?>    
    </div>
    <div class="panel-body">
        <!--div id="content" class="content"-->
            <h1>Analyse</h1>
           <table class="tab">
                <tr class="tr_tab">
                    <th>CodeEtudiant</th>
                    <th>Nom Enseignant</th>
                    <th>Cours</th>
                    
                    <th>Clarté des objectifs du cours </th>
                    <th>Organisation du contenu </th>
                    <th>Qualité des supports de cours</th>
                    <th>Structure des séances</th>
                    <th>Respect du programme</th>
                    <th> Maîtrise de la matière</th>
                    <th> Capacité à expliquer clairement</th>
                    <th> Capacité à répondre aux questions</th>
                    <th> Utilisation d’exemples concrets </th>
                    <th> Encouragement à la participation</th>
                    <th>Disponibilité </th>
                    <th>Respect et politesse</th>
                    <th>Écoute des remarques</th> 
                    <th>Capacité à motiver </th>
                    <th>Encouragement à l’autonomie </th>
                    <th>Ponctualité</th>
                    <th>Assiduité</th>
                    <th>Utilisation efficace du temps</th>
                    <th>Utilisation des outils technologiques</th>
                    <th>Satisfaction globale </th>
                    <th>Commentaire</th>                  
                </tr>
                          <?php      
    function afficher($table,$con){
        $sql ="select *from $table order by id desc";
        $stmt=$con->prepare($sql);
        $stmt->execute(array());
        $data = array();
        $j = 0;
        while($data=$stmt->fetch()){        
               echo "<tr class="."tr_tab".">
                <td> ".$data['matriculeEtudiant'] ."<br>"; 
                    echo "</td>
                    <td> ".$data['enseignant'] ."<br>"; 
                    echo "</td>
                    <td> ".$data['cours'] ."<br> </td>";
                    for ($i=1; $i<=20; $i++) {
                      echo "<td>".$data["r$i"]."</td>";
                    } 
                    
                echo "</tr>";                
        }
    }
       
     
    afficher('evaluations',$pdo);
                        
                             //var_dump($data);
                         //}
                        ?>
                         </table>
        <!--/div-->

        <div class="ananlyse">
              <h1>
          Tableau pour la moyenenne par auditoire pour un cours !  
        </h1>
        <table class="tab">
            <thead class="tr_tab">
                <th>
                    Enseignant
                </th> 
                <th>
                    Cours 
                </th>
                <th>
                    Moyennement en pourcentage
                </th>
            </thead>
            <?php
               function afficherM($sql,$con){
        # $sql ="select *from $table order by id desc";
        $stmt=$con->prepare($sql);
        $stmt->execute(array());
        $data = array();
        $j = 0;
        while($data=$stmt->fetch()){        
               echo "<tr class="."tr_tab".">
                <td> ".$data['NomsEnseignant'] ."<br>"; 
                    echo "</td>
                    <td> ".$data['cours'] ."<br>"; 
                    echo "</td>"; 
                     echo "</td>
                    <td> ".$data['moyenne'] ."<br>"; 
                    echo "</td>" ;                 
                    
                echo "</tr>";                
        }
    }
    afficherM("select concat_ws(' ',enseignant.nom,enseignant.postnom,enseignant.prenom) as NomsEnseignant , cours.nomComplet as cours from enseignant, cours ",$pdo);
       ?>
        </table>
        </div>
    </div>
      
</div>
   <?php 
    include("../footer.php");
    
      ?>