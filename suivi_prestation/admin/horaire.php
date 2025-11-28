
<?php 
    include("nav_dash.php");
    ?>
<div class="content-body">
    <div>
        <?php 
            include("nav.php");
        ?>    
    </div>
    <div class="panel-body">
        <!--div id="content" class="content"-->
            <h1>Horaire de cours</h1>
            <?php
                include("../formulaire/formhoraire.php"); 
                ?>
        <!--/div-->

    </div>
</div>
   <?php 
    include("../footer.php");
    ?>