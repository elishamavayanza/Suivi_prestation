        <footer class="admin-footer">
            <p>&copy; 2025 Système de suivi des prestations de cours. Tous droits réservés.</p>
        </footer>
    </div> <!-- Fermeture de .admin-container -->
</body>
</html>
<script>
    function openModal(url){
        fetch(url)
            .then(reponse=> reponse.text())
            .then(html=>{
                document.getElementById("modalcontent").innerHTML=html;
                document.getElementById("overlay").style.display="block";
            })
            .catch(err => console.error("Erreur chargement formulaire : ", err));  
    }

    function closeModal(){
        document.getElementById("overlay").style.display="none";
         document.getElementById("modalcontent").innerHTML="";
    }
</script>