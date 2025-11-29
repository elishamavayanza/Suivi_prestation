<?php 
    include("menu.php");
?>
<style>
/* Home Page Custom Styles */
.home-hero {
    position: relative;
    height: 400px;
    overflow: hidden;
    border-radius: 10px;
    margin-bottom: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.carousel-item {
    height: 400px;
}

.carousel-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.carousel-caption {
    background: rgba(44, 62, 80, 0.85);
    padding: 20px;
    border-radius: 8px;
    max-width: 80%;
    margin: 0 auto;
    bottom: 20%;
}

.home-section {
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    padding: 30px;
    margin-bottom: 30px;
    color: #333;
}

.home-section h1 {
    color: #2c3e50;
    border-bottom: 2px solid #3498db;
    padding-bottom: 10px;
    margin-bottom: 20px;
    font-weight: 600;
}

.program-list {
    list-style-type: none;
    padding: 0;
}

.program-list > li {
    margin-bottom: 25px;
    padding: 15px;
    border-left: 4px solid #3498db;
    background: #f8f9fa;
    border-radius: 0 8px 8px 0;
}

.program-list ol {
    list-style-type: decimal;
    padding-left: 20px;
    margin-top: 10px;
}

.program-list ol ol {
    list-style-type: lower-alpha;
}

.program-list li {
    margin-bottom: 8px;
    padding: 8px;
}

.intro-text {
    text-align: center;
    font-size: 1.2rem;
    margin: 20px 0;
    color: #2c3e50;
    font-weight: 500;
}

@media (max-width: 768px) {
    .home-hero {
        height: 250px;
    }
    
    .carousel-item {
        height: 250px;
    }
    
    .home-section {
        padding: 20px;
    }
    
    .carousel-caption {
        bottom: 10%;
        max-width: 95%;
    }
    
    .carousel-caption h5 {
        font-size: 1.2rem;
    }
    
    .carousel-caption p {
        font-size: 0.9rem;
    }
}
</style>

<div class="wrapper container-body">
    <section id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <ol class="carousel-indicators">
            <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></li>
            <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></li>
            <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></li>
        </ol>
        <div class="carousel-inner" id="dimension_carousel">
            <div class="carousel-item active">
                <img class="d-block w-100 image-carousel" src="image/affiche.jpg" alt="Première slide">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Institut Supérieur Pédagogique MUHANGI A BUTEMBO</h5>
                    <p>Engagé pour une éducation de qualité depuis plusieurs années</p>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100 image-carousel" src="image/logo.jpg" alt="Deuxième slide">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Excellence Académique</h5>
                    <p>Formation de futurs enseignants et professionnels compétents</p>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100 image-carousel" src="image/affiche.jpg" alt="Troisième slide">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Développement et Patriotisme</h5>
                    <p>Eduquer pour construire un avenir meilleur</p>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Précédent</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Suivant</span>
        </a>
    </section>
    
    <div class="home-section">
        <h1 class="text-center">Bienvenue à l'ISP MUHANGI</h1>
        <p class="intro-text">L'Institut Supérieur Pédagogique MUHANGI A BUTEMBO est un établissement d'enseignement supérieur reconnu pour son excellence académique et sa contribution à la formation des enseignants et professionnels qualifiés.</p>
        
        <h1>Filières Organisées</h1>
        <ol class="program-list">
            <li>
                <strong>SECTION OU DOMAINE DE LETTRES, LANGUES ET ARTS</strong>
                <ol>
                    <li>ANGLAIS-CULTURES AFRICAINES : 1er ET 2ème CYCLE</li>
                    <li>FRANÇAIS-LANGUES AFRICAINES : 1er ET 2ème CYCLE</li>
                    <li>FRANÇAIS-LATIN : 1er CYCLE</li>
                </ol>
            </li>
            <li>
                <strong>SECTION OU DOMAINE DE SCIENCES ET TECHNOLOGIES</strong>
                <ol>
                    <li>BIOLOGIE-CHIMIE: 1er ET 2ème CYCLE</li>
                    <li>MATHEMATIQUE-PHYSIQUE: 1er ET 2ème CYCLE</li>
                    <li>GEOGRAPHIE ET GESTION DE L'ENVIRONNEMENT : 1er ET 2ème CYCLE</li>
                    <li>CONCEPTION DES SYSTEMES D'INFORMATION : 1er ET 2ème CYCLE</li>
                    <li>CHIMIE PHYSIQUE: 1er ET 2ème CYCLE</li>
                    <li>PHYSIQUE-TECHNOLOGIE: 1er CYCLE</li>
                    <li>PHYTOTECHNIE ET DEFENSE DES CULTURES: AGRONOMIE: 1er CYCLE</li>
                    <li>PRODUCTION ET SANTE ANIMALE: VETERINAIRE: 1er CYCLE</li>
                    <li>SCIENCES ET TECHNOLOGIE DES ALIMENTS : 1er CYCLE</li>
                    <li>
                        <ol type="a">
                            <li>TECHNIQUES DES SERVICES HOTELIERS: 1er CYCLE</li>
                            <li>TECHNIQUES DES SERVICES DE RESTAURATION : 1er CYCLE</li>
                        </ol>
                    </li>
                </ol>
            </li>
            <li>
                <strong>SECTION OU DOMAINE DES SCIENCES ECONOMIQUES ET GESTION</strong>
                <ol>
                    <li>GESTION COMMERCIALE ET ADMINISTRATIVE: 1er ET 2ème CYCLE</li>
                </ol>
            </li>
            <li>
                <strong>SECTION OU DOMAINE DES SCIENCES DE L'HOMME ET DE LA SOCIETE</strong>
                <ol>
                    <li>HISTOIRE, GESTION DU PATRIMOINE ET DEVELOPPEMENT: 1er ET 2ème CYCLE</li>
                </ol>
            </li>
        </ol>
    </div>
</div>

<?php 
    include("footer.php");
?>