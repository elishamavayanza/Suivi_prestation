<link rel="stylesheet" href="layout/styles/elegant_footer_improved.css">

<div class="container-footer">
    <div class="wrapper row4">
        <div id="footer" class="clear">
            <div class="footer-wrapper">
                <div class="footer-content">
                    <div class="footer-column">
                        <div class="footer-widget">
                            <h3>Nous trouver</h3>
                            <figure class="center">
                                <img class="worldmap-img" src="images/demo/worldmap.png" alt="World Map">
                                <figcaption>
                                    <a class="figcaption-link" href="#">
                                        Trouvez nous sur Google Maps &raquo;
                                    </a>
                                </figcaption>
                            </figure>
                        </div>
                    </div>

                    <div class="footer-column">
                        <div class="footer-widget">
                            <h3>Contact</h3>
                            <address>
                                ISP-MUHANGI A BUTEMBO<br>
                                ville de Butembo<br>
                                Arrêté MINESU/NUM MINESU/CABMIN/030/2004 du 09 mars 2004<br>
                                <br>
                                <br>
                                <i class="fa fa-phone pright-10"></i> +243.............<br>
                                <i class="fa fa-envelope-o pright-10"></i>
                                <a href="mailto:ispmuhangi@gmail.com">ispmuhangi@gmail.com</a>
                            </address>
                        </div>
                    </div>

                    <div class="footer-column">
                        <div class="footer-widget">
                            <h3>Rester connecté</h3>
                            <p class="nospace btmspace-10">Suivez-nous sur les réseaux sociaux</p>
                            <ul class="social-icons">
                                <li><a class="faicon-twitter" href="#" title="Twitter"><i class="fa fa-twitter"></i></a></li>
                                <li><a class="faicon-linkedin" href="#" title="LinkedIn"><i class="fa fa-linkedin"></i></a></li>
                                <li><a class="faicon-facebook" href="#" title="Facebook"><i class="fa fa-facebook"></i></a></li>
                                <li><a class="faicon-flickr" href="#" title="Flickr"><i class="fa fa-flickr"></i></a></li>
                                <li><a class="faicon-rss" href="#" title="RSS"><i class="fa fa-rss"></i></a></li>
                            </ul>

                            <form class="newsletter-form clear" method="post" action="#">
                                <fieldset>
                                    <legend>Souscrire à notre Newsletter:</legend>
                                    <div class="input-group">
                                        <input type="email" placeholder="Entrez votre email ici&hellip;" required>
                                        <button class="fa fa-sign-in" type="submit" title="S'inscrire">
                                            <em>Souscrire</em>
                                        </button>
                                    </div>
                                    <div class="scroll-indicator"></div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper row5">
      <div id="copyright" class="clear">
        <div class="copyright-content">
          <div class="copyright-text">
            <p class="fl_left">Copyright &copy; 2024 - Tout droit reserve - <a href="#">ISP-MUHANGI</a></p>
          </div>
          <div class="copyright-links">
            <p class="fl_right">Designed by <a target="_blank" href="http://www.os-templates.com/" title="Free Website Templates">ISP_MUHANGI</a></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top" title="Retour en haut">&uarr;</a>
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

    // Back to top button functionality
    const backToTopButton = document.querySelector('.back-to-top');

    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.add('visible');
        } else {
            backToTopButton.classList.remove('visible');
        }
    });
    
    backToTopButton.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Newsletter form scroll indicator
    const newsletterInput = document.querySelector('.newsletter-form input');
    const scrollIndicator = document.querySelector('.scroll-indicator');

    if (newsletterInput) {
        newsletterInput.addEventListener('focus', () => {
            scrollIndicator.style.width = '100%';
        });

        newsletterInput.addEventListener('blur', () => {
            scrollIndicator.style.width = '0';
        });
    }
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