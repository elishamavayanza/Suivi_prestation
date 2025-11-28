    <div class="contaire-footer">
    <div class="wrapper row4">
        <div class="rounded">
            <footer id="footer" class="clear"> 
            
            <div class="one_third first">
                <figure class="center"><img class="btmspace-15" src="images/demo/worldmap.png" alt="">
                <figcaption><a href="#">Trouvez nous  Google Maps &raquo;</a></figcaption>
                </figure>
            </div>
            <div class="one_third">
                <address>
                ISP-MUHANGI A BUTEMBO<br>
                ville de Butembo<br>
                Arrêté MINESU/NUM MINESU/CABMIN/030/2004 du 09 mars 2004<br>
                <br>
                <br>
                <i class="fa fa-phone pright-10"></i> +243.............<br>
                <i class="fa fa-envelope-o pright-10"></i> <a href="#">ispmuhangi@gmail.com</a>
                </address>
            </div>
            <div class="one_third">
                <p class="nospace btmspace-10">Nous suivre</p>
                <ul class="faico clear">
                <li><a class="faicon-twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                <li><a class="faicon-linkedin" href="#"><i class="fa fa-linkedin"></i></a></li>
                <li><a class="faicon-facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                <li><a class="faicon-flickr" href="#"><i class="fa fa-flickr"></i></a></li>
                <li><a class="faicon-rss" href="#"><i class="fa fa-rss"></i></a></li>
                </ul>
                <form class="clear" method="post" action="#">
                <fieldset>
                    <legend>Souscrire à notre  Newsletter:</legend>
                    <input type="text" value="" placeholder="Enter Email Here&hellip;">
                    <button class="fa fa-sign-in" type="submit" title="Sign Up"><em>Souscrire</em></button>
                </fieldset>
                </form>
            </div>
        
            </footer>
    </div>
</div>

<div class="wrapper row5">
  <div id="copyright" class="clear"> 
    
    <p class="fl_left">Copyright &copy; 2024 - Tout droit reserve - <a href="#">ISP-MUHANGI</a></p>
    <p class="fl_right">Designed by <a target="_blank" href="http://www.os-templates.com/" title="Free Website Templates">ISP_MUHANGI</a></p>
    
  </div>
</div>


    </div>
</div>
<script src="layout/scripts/jquery.min.js"></script> 
<script src="layout/scripts/jquery.fitvids.min.js"></script> 
<script src="layout/scripts/jquery.mobilemenu.js"></script> 
<script src="layout/scripts/tabslet/jquery.tabslet.min.js"></script>

   <script>
        // Adjust carousel image height to match window height
        function adjustCarouselHeight() {
            var windowHeight = window.innerHeight;
            var carouselImages = document.querySelectorAll('.carousel-inner img');
            carouselImages.forEach(function(img) {
                img.style.height = 25 + 'rem';
            });
        }

        // Adjust height on page load and window resize
        window.addEventListener('load', adjustCarouselHeight);
        window.addEventListener('resize', adjustCarouselHeight);
    </script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script> 
    	    <script> 
            // FENETRE MODALE
                var $dialog = document.getElementById('mydialog'); 
                if(!('show' in $dialog)){ 
                        document.getElementById('promptCompat').className = 'no_dialog'; 
                } 
                $dialog.addEventListener('close', function() { 
                        console.log('Fermeture. ', this.returnValue); 
                }); 
        </script> 

</body>
</html>
